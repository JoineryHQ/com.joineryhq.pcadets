<?php
use CRM_Pcadets_ExtensionUtil as E;

class CRM_Pcadets_Page_PowerTheCadets extends CRM_Core_Page {

  public function run() {
    // Set the title
    CRM_Utils_System::setTitle(E::ts('Power The Cadets'));

    // Get the power the cadets settings field values
    $availableDatesId = Civi::settings()->get('pcadets_available_dates');
    $softCreditContactsId = Civi::settings()->get('pcadets_soft_credit_contacts');
    $totalMealValuePerDay = Civi::settings()->get('pcadets_total_meal_value_per_day');
    $mealSponsorshipContributionPageId = Civi::settings()->get('pcadets_meal_sponsorship_contribution_page_id');
    $sponsoredDateCustomFieldId = Civi::settings()->get('pcadets_custom_field_sponsored_date');
    $remainAnonymousCustomFieldId = Civi::settings()->get('pcadets_custom_field_remain_anonymous');
    $remainAnonymousInvertCustomFieldId = Civi::settings()->get('pcadets_custom_field_remain_anonymous_invert');
    $displayNameCustomFieldId = Civi::settings()->get('pcadets_custom_field_display_name');
    $messageCustomFieldId = Civi::settings()->get('pcadets_custom_field_message');
    $maximumDonorToShow = Civi::settings()->get('pcadets_maximum_donors_to_show');

    // Set variables for the data that we will pass in the .tpl file
    $powerTheCadetsData = [];

    // Get the optionvValues of the available_date and chain the..
    // soft_credit_contacts base on label and the available date value
    $optionValues = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addSelect('id', 'label', 'value', 'description')
      ->addWhere('option_group_id', '=', $availableDatesId)
      ->addChain('soft_credit_contact', \Civi\Api4\OptionValue::get()
        ->addSelect('value')
        ->addWhere('label', '=', '$value')
        ->addWhere('option_group_id', '=', $softCreditContactsId)
      )
      ->execute();

    // Foreach optionValues
    foreach ($optionValues as $optionValue) {
      // Store date, date_label and city
      $powerTheCadetsData[$optionValue['id']]['date'] = date("Y-m-d", strtotime($optionValue['value']));
      $powerTheCadetsData[$optionValue['id']]['date_label'] = $optionValue['label'];
      $powerTheCadetsData[$optionValue['id']]['city'] = $optionValue['description'];

      // Get the contributions base on the meal_sponsorship_contribution_page_id..
      // and return the necessary data
      $contributions = civicrm_api3('Contribution', 'get', [
        'sequential' => 1,
        'return' => [
          "custom_{$sponsoredDateCustomFieldId}",
          "custom_{$remainAnonymousCustomFieldId}",
          "custom_{$remainAnonymousInvertCustomFieldId}",
          "custom_{$displayNameCustomFieldId}",
          "custom_{$messageCustomFieldId}",
          "contact_id",
          "total_amount",
        ],
        'contribution_page_id' => $mealSponsorshipContributionPageId,
        "custom_{$sponsoredDateCustomFieldId}" => "{$optionValue['value']} 00:00:00"
      ]);

      // Init totalContribution and sponsors variable
      $totalContribution = 0;
      $sponsors = [];

      // Foreach contributions
      foreach ($contributions['values'] as $contribution) {
        // Add the total_amount to the totalContribution
        $totalContribution = $totalContribution + $contribution['total_amount'];

        $remainAnonymous = $contribution["custom_{$remainAnonymousCustomFieldId}"];

        if ($contribution["custom_{$remainAnonymousInvertCustomFieldId}"]) {
          $remainAnonymous = !$remainAnonymous;
        }

        // If remain_anonymous field is not check, store name and amount in the sponsors
        if (!$remainAnonymous) {
          $sponsors[$contribution['contact_id']]['name'] = $contribution["custom_{$displayNameCustomFieldId}"] ?? CRM_Contact_BAO_Contact::displayName($contribution['contact_id']);
          $sponsors[$contribution['contact_id']]['amount'] = $contribution['total_amount'];
        }
      }

      // Get the totalContribution percentage base on the total_meal_value_per_day
      $percentage = (int) $totalContribution / $totalMealValuePerDay * 100;
      // If $percentage is more than 100, assign it as a 100 and store it on the powerTheCadetsData
      $powerTheCadetsData[$optionValue['id']]['percentage'] = $percentage > 100 ? 100 : $percentage;
      // Store sponsors, maximum_donor_to_show and sponsors count
      $powerTheCadetsData[$optionValue['id']]['sponsors'] = $sponsors;
      $powerTheCadetsData[$optionValue['id']]['sponsors_limit'] = $maximumDonorToShow;
      $powerTheCadetsData[$optionValue['id']]['sponsors_count'] = count($sponsors);

      // Add the contribution page url parameter
      $contributionPageUrlParam = "reset=1&id={$mealSponsorshipContributionPageId}&date={$optionValue['value']}";
      // If there is a soft_credit_contact, add it on the contributionPageUrlParam
      if ($optionValue['soft_credit_contact']) {
        // Get the soft_credit_contact contact_id
        $softCreditContact = $optionValue['soft_credit_contact'][0]['value'];
        // Get the contact id details
        list($name, $email, $doNotEmail, $onHold, $isDeceased) = CRM_Contact_BAO_Contact::getContactDetails($softCreditContact);

        // Store softCreditType and soft_credit_info
        $softCreditType = 1;
        $powerTheCadetsData[$optionValue['id']]['soft_credit_info'] = "In Honor of {$name}";
        // If deceased, update softCreditType and soft_credit_info
        if ($isDeceased) {
          $powerTheCadetsData[$optionValue['id']]['soft_credit_info'] = "In Memory of {$name}";
          $softCreditType = 2;
        }

        // Update contribution page url parameter with the softCreditType and softCreditContact
        $contributionPageUrlParam .= "&sctype={$softCreditType}&sccid={$softCreditContact}";
      }

        // Assign contribution page url parameter in the contribution_page_url
      $powerTheCadetsData[$optionValue['id']]['contribution_page_url'] = CRM_Utils_System::url('civicrm/contribute/transact', $contributionPageUrlParam);
    }

    // Assign powerTheCadetsData as powerTheCadetsList
    $this->assign('powerTheCadetsList', $powerTheCadetsData);
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.pcadets', 'js/PowerTheCadets.js', 100, 'page-footer');

    parent::run();
  }

}
