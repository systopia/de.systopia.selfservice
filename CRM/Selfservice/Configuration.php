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

use CRM_Selfservice_ExtensionUtil as E;

class CRM_Selfservice_Configuration {

  protected ?CRM_Selfservice_SendLinkProfile $config;

  const LOG_LINK_REQUESTS_ONLY = 1;
  const LOG_ALL_API            = 2;

  /**
   * @param string $profile_name
   *
   * @throws \Exception
   *   When no profile with the given name exists.
   */
  public function __construct(string $profile_name = 'default') {
    if (!$this->config = CRM_Selfservice_SendLinkProfile::getProfile($profile_name)) {
      throw new Exception(E::ts('No profile with name %1', [1 => $profile_name]));
    }
  }

  /**
   * Get the given setting
   *
   * @param $name                string
   * @param null $default_value  mixed
   * @return mixed
   */
  public function getSetting($name, $default_value = NULL) {
    return $this->config->getAttribute($name, $default_value);
  }

  /**
   * Get the permission required to call the API
   */
  public function getAPIPermission() {
    return $this->config->getAttribute('permission', 'access CiviCRM backend and API');
  }


  /**
   * Check if the following log level should be logged:
   *  1 => link requests only
   *  2 => other requests
   * @param $level integer log level, see constants
   * @return bool true if it should be logged
   */
  public function shouldLog($level) {
    $max_level = (int) $this->getSetting('log');
    return $level <= $max_level;
  }

  /**
   * Log the given data, if the passed log level is activated
   *
   * @param $identifier  string  human readable identifier to indicate what's being logged
   * @param $data        mixed   log data
   * @param $log_level   integer log level, see ::shouldLog
   */
  public function log($identifier, $data, $log_level) {
    if ($this->shouldLog($log_level)) {
      if (!is_string($data)) {
        $data = json_encode($data);
      }
      Civi::log()->debug("{$identifier}: {$data}");
    }
  }

}
