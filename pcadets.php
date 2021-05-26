<?php

require_once 'pcadets.civix.php';
// phpcs:disable
use CRM_Pcadets_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function pcadets_civicrm_config(&$config) {
  _pcadets_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function pcadets_civicrm_xmlMenu(&$files) {
  _pcadets_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function pcadets_civicrm_install() {
  _pcadets_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function pcadets_civicrm_postInstall() {
  _pcadets_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function pcadets_civicrm_uninstall() {
  _pcadets_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function pcadets_civicrm_enable() {
  _pcadets_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function pcadets_civicrm_disable() {
  _pcadets_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function pcadets_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _pcadets_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function pcadets_civicrm_managed(&$entities) {
  _pcadets_civix_civicrm_managed($entities);
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
function pcadets_civicrm_caseTypes(&$caseTypes) {
  _pcadets_civix_civicrm_caseTypes($caseTypes);
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
function pcadets_civicrm_angularModules(&$angularModules) {
  _pcadets_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function pcadets_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _pcadets_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function pcadets_civicrm_entityTypes(&$entityTypes) {
  _pcadets_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function pcadets_civicrm_themes(&$themes) {
  _pcadets_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function pcadets_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function pcadets_civicrm_navigationMenu(&$menu) {
  $pages = array(
    'settings_page' => array(
      'label' => E::ts('Power the Cadets'),
      'name' => 'Power the Cadets',
      'url' => 'civicrm/admin/powerthecadets/settings?reset=1',
      'parent' => array('Administer', 'System Settings'),
      'permission' => 'access CiviCRM',
    ),
  );

  foreach ($pages as $page) {
    // Check that our item doesn't already exist.
    $menu_item_properties = array('url' => $page['url']);
    $existing_menu_items = array();
    CRM_Core_BAO_Navigation::retrieve($menu_item_properties, $existing_menu_items);
    if (empty($existing_menu_items)) {
      // Now we're sure it doesn't exist; add it to the menu.
      $menuPath = implode('/', $page['parent']);
      unset($page['parent']);
      _pcadets_civix_insert_navigation_menu($menu, $menuPath, $page);
    }
  }
}

/**
 * Log CiviCRM API errors to CiviCRM log.
 */
function _pcadets_log_api_error(API_Exception $e, string $entity, string $action, array $params) {
  $logMessage = "CiviCRM API Error '{$entity}.{$action}': " . $e->getMessage() . '; ';
  $logMessage .= "API parameters when this error happened: " . json_encode($params) . '; ';
  $bt = debug_backtrace();
  $errorLocation = "{$bt[1]['file']}::{$bt[1]['line']}";
  $logMessage .= "Error API called from: $errorLocation";
  CRM_Core_Error::debug_log_message($logMessage);
}

/**
 * CiviCRM API wrapper. Wraps with try/catch, redirects errors to log, saves
 * typing.
 */
function _pcadets_civicrmapi(string $entity, string $action, array $params, bool $silence_errors = TRUE) {
  try {
    $result = civicrm_api3($entity, $action, $params);
  }
  catch (API_Exception $e) {
    _pcadets_log_api_error($e, $entity, $action, $params);
    if (!$silence_errors) {
      throw $e;
    }
  }

  return $result;
}
