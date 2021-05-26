<?php
use CRM_Pcadets_ExtensionUtil as E;

class CRM_Pcadets_Page_PowerTheCadets extends CRM_Core_Page {

  public function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('Power The Cadets'));

    $availableDatesId = Civi::settings()->get('pcadets_available_dates');
    $totalMealValuePerDay = Civi::settings()->get('pcadets_total_meal_value_per_day');
    $mealSponsorshipContributionPageId = Civi::settings()->get('pcadets_meal_sponsorship_contribution_page_id');

    $optionValues = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addSelect('id', 'label', 'value', 'description')
      ->addWhere('option_group_id', '=', $availableDatesId)
      ->execute();
    foreach ($optionValues as $optionValue) {
      // do something
    }

    $contributions = \Civi\Api4\Contribution::get()
      ->addWhere('contribution_page_id', '=', $mealSponsorshipContributionPageId)
      ->execute();
    foreach ($contributions as $contribution) {
      // get the total contributions here, sponsors and customfields
    }

    // Example: Assign a variable for use in a template
    $this->assign('currentTime', date('Y-m-d H:i:s'));

    parent::run();
  }

}
