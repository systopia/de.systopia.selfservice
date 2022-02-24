<?php
/*------------------------------------------------------------+
| Selfservice extension                                       |
| Copyright (C) 2022 SYSTOPIA                                 |
| Author: J. Schuppe (schuppe@systopia.de)                    |
+-------------------------------------------------------------+
| This program is released as free software under the         |
| Affero GPL license. You can redistribute it and/or          |
| modify it under the terms of this license which you         |
| can read by viewing the included agpl.txt or online         |
| at www.gnu.org/licenses/agpl.html. Removal of this          |
| copyright header is strictly prohibited without             |
| written permission from the original author(s).             |
+-------------------------------------------------------------*/

use CRM_Selfservice_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Selfservice_Upgrader extends CRM_Selfservice_Upgrader_Base {

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   */
   public function upgrade_0003(): bool {
     $this->ctx->log->info('Migrating SendLink settings to default profile.');
     $current_values = Civi::settings()->get('selfservice_configuration');
     $migrate_settings = array_fill_keys([
       'template_contact_known',
       'template_contact_unknown',
       'template_contact_ambiguous',
       'sender',
     ], NULL);
     foreach ($migrate_settings as $setting_name => &$setting) {
       $setting = $current_values['selfservice_link_request_' . $setting_name];
     }
     $default_profile = new CRM_Selfservice_SendLinkProfile(
       'default',
       $migrate_settings
     );
     $default_profile->save();
     $current_values = [
       'log' => $current_values['selfservice_link_request_log'],
     ];
     Civi::settings()->set('selfservice_configuration', $current_values);
     return TRUE;
   }

}
