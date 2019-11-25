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
 * Selfservice.getContact gives you a contact_id for a given hash,
 *  if the hash is still valid.
 *
 * @return array API result array
 * @access public
 */
function civicrm_api3_selfservice_get_contact($params)
{
  $contact_id = CRM_Selfservice_HashLinks::getContactIdFromHash($params['hash']);
  if ($contact_id) {
    return civicrm_api3_create_success([$contact_id => ['id' => $contact_id]]);
  } else {
    return civicrm_api3_create_success([]);
  }
}

/**
 * API specs for Selfservice.getContact
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_selfservice_get_contact_spec(&$params) {
  $params['hash'] = array(
    'name'           => 'hash',
    'api.required'   => 1,
    'title'          => 'Hash Value',
    'description'    => 'Hash Value as generated by the SelfService.sendlink call',
    );
}