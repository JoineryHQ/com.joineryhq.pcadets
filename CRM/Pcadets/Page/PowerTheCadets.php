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
    $remainAnonymousInvert = Civi::settings()->get('pcadets_remain_anonymous_invert');
    $displayNameCustomFieldId = Civi::settings()->get('pcadets_custom_field_display_name');
    $messageCustomFieldId = Civi::settings()->get('pcadets_custom_field_message');
    $maximumDonorToShow = Civi::settings()->get('pcadets_maximum_donors_to_show');

    // Set variables for the data that we will pass in the .tpl file
    $powerTheCadetsData = [];

    $softCreditTypeHonor = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('option_group_id:name', '=', 'soft_credit_type')
      ->addWhere('value', '=', 1)
      ->execute()
      ->first();
    $softCreditTypeMemory = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('option_group_id:name', '=', 'soft_credit_type')
      ->addWhere('value', '=', 2)
      ->execute()
      ->first();

    // Get the optionvValues of the available_date and chain the..
    // soft_credit_contacts base on label and the available date value
    $optionValues = \Civi\Api4\OptionValue::get()
      ->setCheckPermissions(FALSE)
      ->addSelect('id', 'label', 'value', 'description')
      ->addWhere('option_group_id', '=', $availableDatesId)
      ->addWhere('is_active', '=', true)
      ->addChain('soft_credit_contact', \Civi\Api4\OptionValue::get()
        ->addSelect('value')
        ->addWhere('label', '=', '$value')
        ->addWhere('option_group_id', '=', $softCreditContactsId)
      )
      ->execute();

    // Foreach optionValues
    $hasCurrentDates = FALSE;
    $minDateValue = date('Ymd', strtotime('1 day ago'));
    foreach ($optionValues as $optionValue) {
      // Store date, date_label and city
      $powerTheCadetsData[$optionValue['id']]['date_raw'] = $optionValue['value'];
      $powerTheCadetsData[$optionValue['id']]['date'] = date("Y-m-d", strtotime($optionValue['value']));
      $powerTheCadetsData[$optionValue['id']]['date_label'] = $optionValue['label'];
      $powerTheCadetsData[$optionValue['id']]['city'] = $optionValue['description'];
      if ($optionValue['value'] < $minDateValue) {
        $powerTheCadetsData[$optionValue['id']]['date_is_past'] = TRUE;
      }
      else {
        $hasCurrentDates = TRUE;
      }

      // Get the contributions base on the meal_sponsorship_contribution_page_id..
      // and return the necessary data
      $contributions = civicrm_api3('Contribution', 'get', [
        'sequential' => 1,
        'return' => [
          "custom_{$sponsoredDateCustomFieldId}",
          "custom_{$remainAnonymousCustomFieldId}",
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

        if ($remainAnonymousInvert) {
          $remainAnonymous = !$remainAnonymous;
        }

        // If remain_anonymous field is not check, store name and amount in the sponsors
        if (!$remainAnonymous) {
        $sponsors[] = (!empty($contribution["custom_{$displayNameCustomFieldId}"]) ? $contribution["custom_{$displayNameCustomFieldId}"] : CRM_Contact_BAO_Contact::displayName($contribution['contact_id']));
        } else {
            $sponsors[] = "Anonymous";
        }
      }
      $sponsors = array_unique($sponsors);

      // Get the totalContribution percentage base on the total_meal_value_per_day
      $percentageRaw = (int) $totalContribution / $totalMealValuePerDay * 100;
      $percentage = ceil($percentageRaw / 10) * 10;
      // If $percentage is more than 100, assign it as a 100 and store it on the powerTheCadetsData
      $powerTheCadetsData[$optionValue['id']]['percentage'] = $percentage > 100 ? 100 : $percentage;
      $powerTheCadetsData[$optionValue['id']]['progress_stage'] = self::calculateProgressStage($percentage);
      // Store sponsors, maximum_donor_to_show and sponsors count
      $powerTheCadetsData[$optionValue['id']]['sponsors'] = $sponsors;
      $powerTheCadetsData[$optionValue['id']]['sponsors_limit'] = $maximumDonorToShow;
      $powerTheCadetsData[$optionValue['id']]['sponsors_count'] = count($sponsors);

      // Add the contribution page url parameter
      $contributionPageUrlParam = "reset=1&id={$mealSponsorshipContributionPageId}&date={$optionValue['value']}";
      // If there is a soft_credit_contact, add it on the contributionPageUrlParam
      if ($optionValue['soft_credit_contact']) {
        // Get the soft_credit_contact contact_id
        $softCreditContactId = $optionValue['soft_credit_contact'][0]['value'];
        // Get the contact id details
        list($scContactDisplayName, $scContactEmail, $scContactDoNotEmail, $scContactOnHold, $scContactIsDeceased) = CRM_Contact_BAO_Contact::getContactDetails($softCreditContactId);

        // Specify softCreditType based on contact.is_deceased
        $softCreditType = $softCreditTypeHonor;
        // If deceased, update softCreditType and soft_credit_description
        if ($scContactIsDeceased) {
          $softCreditType = $softCreditTypeMemory;
        }
        // Specify user-visible description for soft-credit
        $powerTheCadetsData[$optionValue['id']]['soft_credit_description'] = "{$softCreditType['label']} {$scContactDisplayName}";

        // Update contribution page url parameter with the softCreditType and softCreditContact
        $contributionPageUrlParam .= "&sctype={$softCreditType['value']}&sccid={$softCreditContactId}";
      }

        // Assign contribution page url parameter in the contribution_page_url
      $powerTheCadetsData[$optionValue['id']]['contribution_page_url'] = CRM_Utils_System::url('civicrm/contribute/transact', $contributionPageUrlParam);
    }

    // Assign powerTheCadetsData as powerTheCadetsList
    $this->assign('powerTheCadetsList', $powerTheCadetsData);
    $this->assign('powerTheCadetsHasCurrentDates', $hasCurrentDates);
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.pcadets', 'js/PowerTheCadets.js', 100, 'page-footer');
    CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.pcadets', 'css/PowerTheCadets.css', 100, 'page-footer');

    parent::run();
  }

  public static function calculateProgressStage($percentage) {
    $mediumMinimum = 25;
    $highMinimum = 75;
    if ($percentage >= $highMinimum) {
      return 'high';
    }
    elseif ($percentage >= $mediumMinimum) {
      return 'medium';
    }
    return 'low';
  }
}
