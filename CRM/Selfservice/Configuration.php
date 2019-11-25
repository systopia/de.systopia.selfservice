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


class CRM_Selfservice_Configuration {

  protected static $config = NULL;

  const LOG_LINK_REQUESTS_ONLY = 1;
  const LOG_ALL_API            = 2;

  /**
   * Get the given setting
   *
   * @param $name                string
   * @param null $default_value  mixed
   * @return mixed
   */
  public static function getSetting($name, $default_value = NULL) {
    // load settings
    if (self::$config === NULL) {
      self::$config = Civi::settings()->get('selfservice_configuration');
      if (self::$config === NULL) {
        self::$config = []; // avoid re-loading
      }
    }

    // return requested value
    return CRM_Utils_Array::value($name, self::$config, $default_value);
  }

  /**
   * Get the permission required to call the API
   */
  public static function getAPIPermissions() {
    $permission = self::getSetting('selfservice_link_request_permissions');
    if ($permission) {
      return [$permission, 'access CiviCRM backend and API'];
    } else {
      return ['access CiviCRM backend and API'];
    }
  }


  /**
   * Check if the following log level should be logged:
   *  1 => link requests only
   *  2 => other requests
   * @param $level integer log level, see constants
   * @return bool true if it should be logged
   */
  public static function shouldLog($level) {
    $max_level = (int) self::getSetting('selfservice_link_request_log');
    return $level <= $max_level;
  }

  /**
   * Log the given data, if the passed log level is activated
   *
   * @param $identifier  string  human readable identifier to indicate what's being logged
   * @param $data        mixed   log data
   * @param $log_level   integer log level, see ::shouldLog
   */
  public static function log($identifier, $data, $log_level) {
    if (self::shouldLog($log_level)) {
      if (!is_string($data)) {
        $data = json_encode($data);
      }
      Civi::log()->debug("{$identifier}: {$data}");
    }
  }
}
