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

class CRM_Selfservice_SendLinkProfile {

  /**
   * @var self[] $_profiles
   *   Caches the profile objects.
   */
  protected static ?array $_profiles;

  /**
   * @var string $name
   *   The name of the profile.
   */
  protected string $name;

  /**
   * @var array $data
   *   The properties of the profile.
   */
  protected array $data;

  /**
   * CRM_Selfservice_Profile constructor.
   *
   * @param string $name
   *   The name of the profile.
   * @param array $data
   *   The properties of the profile
   */
  public function __construct(string $name, array $data) {
    $this->name = $name;
    $allowed_attributes = self::allowedAttributes();
    $this->data = $data + array_combine(
        $allowed_attributes,
        array_fill(0, count($allowed_attributes), NULL)
      );
  }

  /**
   * Returns an array of attributes allowed for a profile.
   *
   * @return array
   */
  public static function allowedAttributes(): array {
    return [
      'template_contact_known',
      'template_contact_unknown',
      'template_contact_ambiguous',
      'sender',
    ];
  }

  /**
   * Returns the default profile with "factory" defaults.
   *
   * @param string $name
   *   The profile's name. Defaults to "default".
   *
   * @return self
   *
   * @throws \CRM_Core_Exception
   */
  public static function createDefaultProfile(string $name = 'default'): self {
    return new self($name, [
      'template_contact_known' => NULL,
      'template_contact_unknown' => NULL,
      'template_contact_ambiguous' => NULL,
      // TODO: Remove backwards-compatibility soon.
      //       CRM_Core_BAO_Domain::getFromEmail() is available from 5.44.
      'sender' => method_exists('CRM_Core_BAO_Domain', 'getFromEmail')
        ? CRM_Core_BAO_Domain::getFromEmail()
        : current(CRM_Core_BAO_Domain::getNameAndEmail(FALSE, TRUE)),
    ]);
  }

  /**
   * Retrieves the profile with the given name.
   *
   * @param $name
   *
   * @return self | NULL
   */
  public static function getProfile($name): ?self {
    $profiles = self::getProfiles();
    return $profiles[$name] ?? NULL;
  }

  /**
   * Retrieves an attribute of the profile.
   *
   * @param string $attribute_name
   * @param mixed $default
   *
   * @return mixed | NULL
   */
  public function getAttribute(string $attribute_name, $default = NULL) {
    if (isset($this->data[$attribute_name])) {
      return $this->data[$attribute_name];
    }
    else {
      return $default;
    }
  }

  /**
   * Retrieves the list of all profiles persisted within the current CiviCRM
   * settings, including the default profile.
   *
   * @return self[]
   *
   * @throws \CRM_Core_Exception
   */
  public static function getProfiles(): array {
    if (!isset(self::$_profiles)) {
      self::$_profiles = [];
      if ($profiles_data = Civi::settings()->get(E::SHORT_NAME . '_sendlink_profiles')) {
        foreach ($profiles_data as $profile_name => $profile_data) {
          self::$_profiles[$profile_name] = new self($profile_name, $profile_data);
        }
      }
    }

    // Include the default profile if it was not overridden within the settings.
    if (!isset(self::$_profiles['default'])) {
      self::$_profiles['default'] = self::createDefaultProfile();
      self::storeProfiles();
    }

    return self::$_profiles;
  }

  /**
   * Verifies whether the profile is valid (i.e. consistent and not colliding
   * with other profiles).
   *
   * @throws Exception
   *   When the profile could not be successfully validated.
   */
  public function validate() {
    // TODO: check
    //  data of this profile consistent?
    //  conflicts with other profiles?
  }

  /**
   * Persists the profile within the CiviCRM settings.
   */
  public function save() {
    self::$_profiles[$this->getName()] = $this;
    $this->validate();
    self::storeProfiles();
  }

  /**
   * Deletes the profile from the CiviCRM settings.
   */
  public function delete() {
    unset(self::$_profiles[$this->getName()]);
    self::storeProfiles();
  }

  public static function storeProfiles() {
    if (isset(self::$_profiles)) {
      $profile_data = [];
      foreach (self::$_profiles as $profile_name => $profile) {
        $profile_data[$profile_name] = $profile->data;
      }
      Civi::settings()->set(E::SHORT_NAME . '_sendlink_profiles', $profile_data);
    }
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

  /**
   * @param string $name
   */
  public function setName(string $name): void {
    $this->name = $name;
  }

  /**
   * @return array
   */
  public function getData() {
    return $this->data;
  }

  /**
   * Sets an attribute of the profile.
   *
   * @param string $attribute_name
   * @param mixed $value
   *
   * @throws \Exception
   *   When the attribute name is not known.
   */
  public function setAttribute(string $attribute_name, $value) {
    if (!in_array($attribute_name, self::allowedAttributes())) {
      throw new Exception(E::ts('Unknown attribute %1.', array(1 => $attribute_name)));
    }
    // TODO: Check if value is acceptable.
    $this->data[$attribute_name] = $value;
  }

}
