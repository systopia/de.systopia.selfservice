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

use Civi\API\Exception\UnauthorizedException;

/**
 * Selfservice.getHash gives you a hash value for a contact ID
 *  if the hash is still valid.
 *
 * @return array API result array
 * @access public
 */
function civicrm_api3_selfservice_get_hash($params)
{
  $config = new CRM_Selfservice_Configuration();
  $config->log("Selfservice.get_hash", $params, CRM_Selfservice_Configuration::LOG_ALL_API);

  $contact_id = (int) $params['contact_id'];
  if (!$contact_id) {
    return civicrm_api3_create_error("No contact ID given");
  }

  $hash = CRM_Selfservice_HashLinks::getContactHash($contact_id);
  if ($hash) {
    return civicrm_api3_create_success([$contact_id => ['id' => $contact_id, 'hash' => $hash]]);
  } else {
    return civicrm_api3_create_error("Hash could not be calculated.");
  }
}

/**
 * API specs for Selfservice.getHash
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_selfservice_get_hash_spec(&$params) {
  $params['contact_id'] = array(
    'name'           => 'contact_id',
    'api.required'   => 1,
    'title'          => 'Contact ID',
    );
}
