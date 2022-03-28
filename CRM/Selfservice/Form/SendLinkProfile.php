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

class CRM_Selfservice_Form_SendLinkProfile extends CRM_Core_Form {

  /**
   * @var CRM_Selfservice_SendLinkProfile $profile
   *   The profile object the form is acting on.
   */
  protected ?CRM_Selfservice_SendLinkProfile $profile;

  /**
   * @var string
   *
   * The operation to perform within the form.
   */
  protected string $_op;

  /**
   * {@inheritDoc}
   */
  public function buildQuickForm() {
    // "Create" is the default operation.
    if (!$this->_op = CRM_Utils_Request::retrieve('op', 'String', $this)) {
      $this->_op = 'create';
    }

    // Verify that a profile with the given name exists.
    if ($profile_name = CRM_Utils_Request::retrieve('name', 'String', $this)) {
      $this->profile = CRM_Selfservice_SendLinkProfile::getProfile($profile_name);
    }

    // Set redirect destination.
    $this->controller->setDestination(CRM_Utils_System::url('civicrm/admin/selfservice', 'reset=1'));

    switch ($this->_op) {
      case 'delete':
        if ($profile_name) {
          $title = $profile_name == 'default' ? E::ts('Reset SelfService SendLink default profile to factory defaults') : E::ts('Delete SelfService SendLink profile "%1"', [1 => $profile_name]);
          CRM_Utils_System::setTitle($title);
          $this->addButtons([
            [
              'type' => 'submit',
              'name' => ($profile_name == 'default' ? E::ts('Reset') : E::ts('Delete')),
              'isDefault' => TRUE,
            ],
          ]);
        }
        parent::buildQuickForm();
        return;
      case 'edit':
        // When editing without a valid profile name, edit the default profile.
        if (!$profile_name) {
          $profile_name = 'default';
          $this->profile = CRM_Selfservice_SendLinkProfile::getProfile($profile_name);
        }
        CRM_Utils_System::setTitle(E::ts('Edit Selfservice Sendlink profile <em>%1</em>', [1 => $this->profile->getName()]));
        break;
      case 'copy':
        // Retrieve the source profile name.
        $profile_name = CRM_Utils_Request::retrieve('source_name', 'String', $this);
        // When copying without a valid profile name, copy the default profile.
        if (!$profile_name) {
          $profile_name = 'default';
        }
        $this->profile = clone CRM_Selfservice_SendLinkProfile::getProfile($profile_name);

        // Propose a new name for this profile.
        $profile_name = $profile_name . '_copy';
        $this->profile->setName($profile_name);
        CRM_Utils_System::setTitle(E::ts('New SelfService SendLink profile'));
        break;
      case 'create':
        // Load factory default profile values.
        $this->profile = CRM_Selfservice_SendLinkProfile::createDefaultProfile($profile_name);
        CRM_Utils_System::setTitle(E::ts('New SelfService SendLink profile'));
        break;
    }

    // Assign template variables.
    $this->assign('op', $this->_op);
    $this->assign('profile_name', $profile_name);

    // Add form elements.
    $is_default = $profile_name == 'default';
    $this->add(
      ($is_default ? 'static' : 'text'),
      'name',
      E::ts('Profile name'),
      [],
      !$is_default
    );

    $this->add(
      'select',
      'log',
      E::ts('Log Requests'),
      [
        0 => E::ts("No"),
        1 => E::ts("Only Link Requests"),
        2 => E::ts("Everything"),
      ],
      FALSE
    );
    $this->add(
      'select',
      'permission',
      E::ts('Permission'),
      ['' => E::ts("only: 'access CiviCRM backend and API'")] + CRM_Core_Permission::basicPermissions(),
      FALSE
    );
    $templates = $this->getMessageTemplates();
    $this->add(
      'select',
      'template_contact_known',
      E::ts('E-Mail Template for Case: Email is <i>known</i>'),
      $templates,
      FALSE
    );
    $this->add(
      'select',
      'template_contact_unknown',
      E::ts('E-Mail Template for Case: Email is <i>not known</i>'),
      $templates,
      FALSE
    );
    $this->add(
      'select',
      'template_contact_ambiguous',
      E::ts('E-Mail Template for Case: Email is <i>ambiguous</i>'),
      $templates,
      FALSE
    );
    $this->add(
      'text',
      'sender',
      E::ts('Sender E-Mail'),
      ['class' => 'huge'],
      TRUE
    );

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Save'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => ts('Cancel'),
      ],
    ]);

    // Export form elements.
    parent::buildQuickForm();
  }

  /**
   * @{@inheritDoc}
   */
  public function validate(): bool {
    $values = $this->exportValues();

    // Validate new profile names.
    if (
      isset($values['name'])
      && ($values['name'] != $this->profile->getName() || $this->_op != 'edit')
      && !empty(CRM_Selfservice_SendLinkProfile::getProfile($values['name']))
    ) {
      $this->_errors['name'] = E::ts('A profile with this name already exists.');
    }

    // Restrict profile names to alphanumeric characters and the underscore.
    if (isset($values['name']) && preg_match("/[^A-Za-z0-9_]/", $values['name'])) {
      $this->_errors['name'] = E::ts('Only alphanumeric characters and the underscore (_) are allowed for profile names.');
    }

    return parent::validate();
  }

  /**
   * Set the default values (i.e. the profile's current data) in the form.
   */
  public function setDefaultValues(): ?array {
    $defaults = parent::setDefaultValues();
    if (in_array($this->_op, ['create', 'edit', 'copy'])) {
      $defaults['name'] = $this->profile->getName();
      $profile_data = $this->profile->getData();
      foreach ($profile_data as $element_name => $value) {
        $defaults[$element_name] = $value;
      }
    }
    return $defaults;
  }

  /**
   * Store the values submitted with the form in the profile.
   */
  public function postProcess() {
    $values = $this->exportValues();
    switch ($this->_op) {
      case 'create':
      case 'edit':
      case 'copy':
        if (empty($values['name'])) {
          $values['name'] = 'default';
        }
        $this->profile->setName($values['name']);
        foreach (CRM_Selfservice_SendLinkProfile::allowedAttributes() as $element_name) {
          if (isset($values[$element_name])) {
            if ($element_name == 'sender') {
              $values[$element_name] = html_entity_decode($values[$element_name]);
            }
            $this->profile->setAttribute($element_name, $values[$element_name]);
          }
        }
        $this->profile->save();
        break;
      case 'delete':
        $this->profile->delete();
        break;
    }
    parent::postProcess();
  }

  /**
   * Get a list of eligible message templates
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  protected function getMessageTemplates(): array {
    $templates = ['' => E::ts("disabled")];
    $query = civicrm_api3('MessageTemplate', 'get', [
      'option.limit' => 0,
      'is_active' => 1,
      'workflow_id' => ['IS NULL' => 1],
      'return' => 'id,msg_title',
    ]);
    foreach ($query['values'] as $template) {
      $templates[$template['id']] = $template['msg_title'];
    }
    return $templates;
  }

}
