<?php

require_once 'selfservice.civix.php';
use CRM_Selfservice_ExtensionUtil as E;



/**
 * Hook implementation: New Tokens
 */
function selfservice_civicrm_tokens( &$tokens ) {
  CRM_Selfservice_HashLinks::addTokens($tokens);
}

/**
 * Hook implementation: New Tokens
 */
function selfservice_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  CRM_Selfservice_HashLinks::tokenValues($values, $cids, $job, $tokens, $context);
}


/**
 * Set permissions for runner/engine API call
 */
function selfservice_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $selfservice_permissions = CRM_Selfservice_Configuration::getAPIPermissions();
  $permissions['self_service']['sendlink']    = $selfservice_permissions;
  $permissions['self_service']['get_contact'] = $selfservice_permissions;
  $permissions['self_service']['get_hash']    = $selfservice_permissions;
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/ 
 */
function selfservice_civicrm_config(&$config) {
  _selfservice_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function selfservice_civicrm_xmlMenu(&$files) {
  _selfservice_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function selfservice_civicrm_install() {
  _selfservice_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function selfservice_civicrm_postInstall() {
  _selfservice_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function selfservice_civicrm_uninstall() {
  _selfservice_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function selfservice_civicrm_enable() {
  _selfservice_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function selfservice_civicrm_disable() {
  _selfservice_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function selfservice_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _selfservice_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function selfservice_civicrm_managed(&$entities) {
  _selfservice_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function selfservice_civicrm_caseTypes(&$caseTypes) {
  _selfservice_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function selfservice_civicrm_angularModules(&$angularModules) {
  _selfservice_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function selfservice_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _selfservice_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function selfservice_civicrm_entityTypes(&$entityTypes) {
  _selfservice_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function selfservice_civicrm_themes(&$themes) {
  _selfservice_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 *
function selfservice_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 *
function selfservice_civicrm_navigationMenu(&$menu) {
  _selfservice_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _selfservice_civix_navigationMenu($menu);
} // */
