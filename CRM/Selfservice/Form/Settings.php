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

class CRM_Selfservice_Form_Settings extends CRM_Core_Form {

  const HASH_LINK_COUNT = 10;

  /**
   * {@inheritDoc}
   */
  public function buildQuickForm() {
    $this->add(
      'select',
      'log',
      E::ts('Log Requests'),
      [0 => E::ts("No"), 1 => E::ts("Only Link Requests"), 2 => E::ts("Everything")],
      FALSE
    );

    $profiles = [];
    foreach (CRM_Selfservice_SendLinkProfile::getProfiles() as $profile_name => $profile) {
      $profiles[$profile_name]['name'] = $profile_name;
      foreach (CRM_Twingle_Profile::allowedAttributes() as $attribute) {
        $profiles[$profile_name][$attribute] = $profile->getAttribute($attribute);
      }
    }
    $this->assign('sendlink_profiles', $profiles);
    CRM_Core_Resources::singleton()->addScriptFile('civicrm', 'js/crm.livePage.js', 1, 'html-header');

    // Configuration for hash links (personalised links)
    $hash_link_ids = range(1, self::HASH_LINK_COUNT);
    $this->assign("hash_links", $hash_link_ids);
    foreach ($hash_link_ids as $i) {
      $this->add(
          'text',
          "hash_link_{$i}",
          E::ts('Token Key'),
          ['placeholder' => E::ts("Enter key to activate")]
      );
      $this->addRule("hash_link_{$i}", E::ts("The name mustn't contain special characters or spaces."), 'alphanumeric');

      $this->add(
          'text',
          "hash_link_name_{$i}",
          E::ts('Token Label'),
          ['class' => 'big']
      );
      $this->add(
          'textarea',
          "hash_link_html_{$i}",
          E::ts('Link HTML'),
          ['class' => 'big']
      );
      $this->add(
          'textarea',
          "hash_link_fallback_html_{$i}",
          E::ts('Fallback HTML'),
          ['class' => 'big']
      );
    }

    $this->addButtons([
        [
            'type'      => 'submit',
            'name'      => E::ts('Save'),
            'isDefault' => TRUE,
        ],
    ]);

    // add basic settings
    $current_values = Civi::settings()->get('selfservice_configuration');
    if (is_array($current_values)) {
      unset($current_values['qfKey'], $current_values['entryURL']);
      $this->setDefaults($current_values);
    }

    // add hash link specs
    $link_specs = CRM_Selfservice_HashLinks::getLinks();
    foreach (range(1, self::HASH_LINK_COUNT) as $i) {
      if (isset($link_specs[$i - 1])) {
        $spec = $link_specs[$i - 1];
        $this->setDefaults([
            "hash_link_{$i}"               => CRM_Utils_Array::value('name', $spec, ''),
            "hash_link_name_{$i}"          => CRM_Utils_Array::value('label', $spec, ''),
            "hash_link_html_{$i}"          => CRM_Utils_Array::value('link_html', $spec, ''),
            "hash_link_fallback_html_{$i}" => CRM_Utils_Array::value('fallback_html', $spec, ''),
        ]);
      }
    }

    parent::buildQuickForm();
  }

  /**
   * {@inheritDoc}
   */
  public function postProcess()
  {
    $values = $this->exportValues(null, true);
    unset($values['qfKey'], $values['entryURL']);

    // extract and store hash link specs
    $hash_link_specs = [];
    foreach (range(1, self::HASH_LINK_COUNT) as $i) {
      if (!empty($values["hash_link_{$i}"])) {
        $hash_link_specs[] = [
            'name'          => $values["hash_link_{$i}"],
            'label'         => CRM_Utils_Array::value("hash_link_name_{$i}", $values, $values["hash_link_{$i}"]),
            'link_html'     => html_entity_decode($values["hash_link_html_{$i}"]),
            'fallback_html' => html_entity_decode($values["hash_link_fallback_html_{$i}"])
        ];
      }
      unset($values["hash_link_{$i}"], $values["hash_link_name_{$i}"], $values["hash_link_html_{$i}"], $values["hash_link_fallback_html_{$i}"]);
    }
    Civi::settings()->set('selfservice_personalised_links', $hash_link_specs);

    // Store the rest.
    Civi::settings()->set('selfservice_configuration', $values);

    parent::postProcess();
  }

  /**
   * Get a list of eligible message templates
   *
   * @return array
   *
   * @throws \CiviCRM_API3_Exception
   */
  protected function getMessageTemplates() {
    $templates = ['' => E::ts("disabled")];
    $query = civicrm_api3('MessageTemplate', 'get', [
        'option.limit' => 0,
        'is_active'    => 1,
        'workflow_id'  => ['IS NULL' => 1],
        'return'       => 'id,msg_title'
    ]);
    foreach ($query['values'] as $template) {
      $templates[$template['id']] = $template['msg_title'];
    }
    return $templates;
  }

}
