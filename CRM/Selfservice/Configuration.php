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
      self::$config = Civi::settings()->get('selfservice_personalised_links');
      if (self::$config === NULL) {
        self::$config = []; // avoid re-loading
      }
    }

    // return requested value
    return CRM_Utils_Array::value($name, self::$config, $default_value);
  }

}
