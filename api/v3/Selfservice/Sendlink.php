<?php
/*------------------------------------------------------------+
| Selfservice extension                                       |
| Copyright (C) 2019 SYSTOPIA                                 |
| Author: B. Endres (endres@systopia.de)                      |
+-------------------------------------------------------------+
| This program is released as free software under the         |
| Affero GPL license. You can redistribute it and/or          |
| modify it under the terms of this license which you         |
| can read by viewing the included agpl.txt or online         |
| at www.gnu.org/licenses/agpl.html. Removal of this          |
| copyright header is strictly prohibited without             |
| written permission from the original author(s).             |
+-------------------------------------------------------------*/

/**
 * Process Selfservice.sendlink
 *
 *  Send a personalised self-service link
 *
 * @return array API result array
 * @access public
 */
function civicrm_api3_selfservice_sendlink($params)
{
  $config = new CRM_Selfservice_Configuration($params['profile'] ?? NULL);
  if (!CRM_Core_Permission::check($config->getSetting('permission'))) {
    return civicrm_api3_create_error('Insufficient permissions.');
  }
  $config->log("Selfservice.sendlink", $params, CRM_Selfservice_Configuration::LOG_LINK_REQUESTS_ONLY);

  // get templates
  $template_email_known     = (int) $config->getSetting('template_contact_known');
  $template_email_unknown   = (int) $config->getSetting('template_contact_unknown');
  $template_email_ambiguous = (int) $config->getSetting('template_contact_ambiguous');

  // find contact ids for the given email
  $contact_ids = [];
  $query = civicrm_api3('Email', 'get', [
      'check_permissions' => 0,
      'option.limit'      => 0,
      'email'             => trim($params['email']),
      'return'            => 'contact_id'
  ]);
  foreach ($query['values'] as $email) {
    $contact_ids[$email['contact_id']] = TRUE;
  }

  // remove the contacts that are deleted
  if ($contact_ids) {
    $query = civicrm_api3('Contact', 'get', [
        'check_permissions' => 0,
        'option.limit'      => 0,
        'id'                => ['IN' => array_keys($contact_ids)],
        'is_deleted'        => 1,
        'return'            => 'id',
    ]);
    foreach ($query['values'] as $deleted_contact) {
      unset($contact_ids[$deleted_contact['id']]);
    }
  }

  switch (count($contact_ids)) {
    case 0: // contact unknown
      if ($template_email_unknown) {
        civicrm_api3('MessageTemplate', 'send', [
            'check_permissions' => 0,
            'id'                => $template_email_unknown,
            'from'              => $config->getSetting('sender'),
            'to_email'          => trim($params['email']),
        ]);
        return civicrm_api3_create_success("email sent");
      }
      break;

    case 1: // contact known
      if ($template_email_known) {
        $contact_id = min(array_keys($contact_ids));
        civicrm_api3('MessageTemplate', 'send', [
            'check_permissions' => 0,
            'id'                => $template_email_known,
            'to_name'           => civicrm_api3('Contact', 'getvalue', ['id' => $contact_id, 'return' => 'display_name']),
            'from'              => $config->getSetting('sender'),
            'contact_id'        => $contact_id,
            'to_email'          => trim($params['email']),
        ]);
        return civicrm_api3_create_success("email sent");
      }
      break;

    default: // contact ambiguous
      if ($template_email_ambiguous) {
        // we found a contact -> send to the one with the lowest ID
        $contact_id = min(array_keys($contact_ids));
        civicrm_api3('MessageTemplate', 'send', [
            'check_permissions' => 0,
            'id'                => $template_email_ambiguous,
            'to_name'           => civicrm_api3('Contact', 'getvalue', ['id' => $contact_id, 'return' => 'display_name']),
            'from'              => $config->getSetting('sender'),
            'contact_id'        => $contact_id,
            'to_email'          => trim($params['email']),
        ]);
        return civicrm_api3_create_success("email sent");
      }
      break;
  }

  // no template set for this case -> do nothing
  Civi::log()->debug("Selfservice.sendlink requested but not enabled. Configure the templates");
  return civicrm_api3_create_error("disabled");
}

/**
 * API specs for Selfservice.sendlink
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_selfservice_sendlink_spec(&$params) {
  // CONTACT BASE
  $params['email'] = array(
    'name'           => 'email',
    'api.required'   => 1,
    'title'          => 'email address',
    );
  $params['profile'] = [
    'name' => 'profile',
    'title' => 'Profile name',
    'default' => 'default',
    'description' => 'The name of the SendLink configuration profile to use.',
  ];
}
