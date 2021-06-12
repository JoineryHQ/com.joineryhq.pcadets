<!--BEGIN HEADER BLOCK-->
<div align="center">
  <br>
  <br>
  <img width="33%" title="Power The Cadets Logo-Food 2" src="https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2.png" class="img-responsive wp-image-7670" srcset="https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2-200x60.png 200w, https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2-400x121.png 400w, https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2-600x181.png 600w, https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2-800x241.png 800w, https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2-1200x362.png 1200w, https://cadets.org/wp-content/uploads/2021/06/Power-The-Cadets-Logo-Food-2.png 1987w" sizes="(max-width: 1024px) 100vw, (max-width: 640px) 100vw, 400px"><br>
  <br>
  <br>
 <div align="center"> <a href="https://cadets.org/power">← Return</a></div>
<br>
  <p>If you would like to dedicate a date below in memory/in honor of someone, please email <a href="mailto:abroussard@cadets.org">abroussard@cadets.org.</a></p>
</div>
<!--END HEADER BLOCK-->
<div class="table-2">
  <table class="table" id="pcadets-listing-table">
    <thead id="pcadets-listing-header">
      <tr>
        <!-- Column headers. Each has a unique id, and a common class indicating it is a header for this pcadets listing. -->
        <th id="pcadets-listing-header-date" class="pcadets-listing-header">Date</th>
        <th id="pcadets-listing-header-city" class="pcadets-listing-header">City</th>
        <th id="pcadets-listing-header-mealprogress" class="pcadets-listing-header">Power Level</th>
        <th id="pcadets-listing-header-donors" class="pcadets-listing-header">Powered By</th>
        <th id="pcadets-listing-header-links" class="pcadets-listing-header">Power The Cadets</th>
      </tr>
    </thead>
    <tbody id="pcadets-listing-body">
      <!-- Each row represents an OptionValue from the TourDates option group.
           Each row has the common class "pcadets-listing-row".
           Each row as alternating "odd-row"/"even-row" class.
           Each row has a unique id "pcadets-listing-row-N", where N is the ID of the OptionValue for this date.
      -->
      <!-- TR for row 1 -->
        {foreach from=$powerTheCadetsList key=powerTheCadetsItemKey item=powerTheCadetsItem}
        <tr id="pcadets-listing-row-{$powerTheCadetsItemKey}" class="pcadets-listing-row {cycle values="odd-row,even-row"}">
          <!-- For TD ids and classes, TYPE is an indicator of the type of data it contains (date, city, etc,), and N is the same as in the row id.
               Each TD has a unique id "pcadets-listing-item-TYPE-N".
               Each TD has a class "pcadets-listing-item-TYPE".
               Each TD has the common class "pcadets-listing-item".
          -->
          <td id="pcadets-listing-item-date-{$powerTheCadetsItemKey}" class="pcadets-listing-item pcadets-listing-item-date">{$powerTheCadetsItem.date|date_format:"%B %d"}</td>
          <td id="pcadets-listing-item-city-{$powerTheCadetsItemKey}" class="pcadets-listing-item pcadets-listing-item-city">{$powerTheCadetsItem.city}</td>
          <!-- TDs with class "pcadets-listing-item-mealprogress" will have the attribute data-pcadets-listing-mealprogress-number, which has a value
               inticating the percentage of completion for meals on this date.
          -->
          <td id="pcadets-listing-item-mealprogress-{$powerTheCadetsItemKey}" class="pcadets-listing-item pcadets-listing-item-mealprogress pcadets-listing-item-mealprogress-progressstage-{$powerTheCadetsItem.progress_stage}" data-pcadets-listing-mealprogress-number="{$powerTheCadetsItem.percentage}">
            <!-- TDs with class "pcadets-listing-item-mealprogress" contain a nested div structure stylable as a progress indicator.
            -->
            <div id="pcadets-listing-mealprogress-indicator-{$powerTheCadetsItemKey}" class="pcadets-listing-mealprogress-indicator pcadets-listing-mealprogress-indicator-progressstage-{$powerTheCadetsItem.progress_stage}">
              <!-- Divs with class "pcadets-listing-mealprogress-completed" will have a "style" attribute setting the width to the appropriate
                   percentage, same as in the data-pcadets-listing-mealprogress-number attribute of the parent <td class="pcadets-listing-item-mealprogress">
              -->
              <div id="pcadets-listing-mealprogress-completed-{$powerTheCadetsItemKey}" class="pcadets-listing-mealprogress-completed pcadets-listing-mealprogress-completed-progressstage-{$powerTheCadetsItem.progress_stage}" style="width: {$powerTheCadetsItem.percentage}%;"></div>
            </div>
            <!-- Divs with class "pcadets-listing-mealprogress-number" are expected to be displayed to the user as a numeric indication of the completed
                 percentage, same as in the data-pcadets-listing-mealprogress-number attribute of the parent <td class="pcadets-listing-item-mealprogress">
            -->
            <div id="pcadets-listing-mealprogress-number-{$powerTheCadetsItemKey}" class="pcadets-listing-mealprogress-number pcadets-listing-mealprogress-number-progressstage-{$powerTheCadetsItem.progress_stage}">{$powerTheCadetsItem.percentage}%</div>
          </td>
          <!-- TDs with class "pcadets-listing-item-donors" will have the attribute data-pcadets-donorcount, which has a value
               indicating the number of non-anonymous donors for this date.
               JavaScript code can use this value to hide the child <div class="pcadets-listing-donors">, display the child <a class="pcadets-listing-donors-viewmore">,
               and cause that <a> element to display the contents of the child <div class="pcadets-listing-donors">.
          -->
          <td id="pcadets-listing-item-donors-{$powerTheCadetsItemKey}" class="pcadets-listing-item pcadets-listing-item-donors" data-pcadets-donorcount="{$powerTheCadetsItem.sponsors_count}">
            <div id="pcadets-listing-donors-{$powerTheCadetsItemKey}" class="pcadets-listing-donors"
              {if ($powerTheCadetsItem.sponsors_count > $powerTheCadetsItem.sponsors_limit)}
                style="display:none;"
              {/if}
            >
              <!-- Divs with class  "pcadets-listing-donor" represent a single NON-ANONYMOUS donor for this date. (Anonymous donors are never listed).
                   Each div has the unique id "pcadets-listing-donor-N-X", where N is the same as in the row id, and X is a serial integer 1, 2, 3, etc.
              -->
              {counter start=0 name=sponsorCounter print=false}
              {foreach from=$powerTheCadetsItem.sponsors item=sponsor}
                  <div id="pcadets-listing-donor-{$powerTheCadetsItemKey}-{counter name=sponsorCounter}" class="pcadets-listing-donor">{$sponsor}</div>
              {/foreach}
            </div>
            <!-- Links with class "pcadets-listing-donors-viewmore" have style="display:none" and are hidden upon page load; JavaScript code may
                 hide these links based on logic related to the attribute data-pcadets-donorcount (see comments on parent <td class="pcadets-listing-item-donors">).
            -->
            {if $powerTheCadetsItem.sponsors_count > $powerTheCadetsItem.sponsors_limit}
              <a href="#viewmore" id="pcadets-listing-donors-viewmore-{$powerTheCadetsItemKey}" class="pcadets-listing-donors-viewmore">
                <span>View Sponsors</span>
              </a>
            {/if}
          </td>
          <td id="pcadets-listing-item-links-{$powerTheCadetsItemKey}" class="pcadets-listing-item pcadets-listing-item-links">
            <!-- Links with the class "pcadets-listing-link-donate" will have an href value equivalent to the full contribution page URL, plus any appropriate
                 honoree query parameters, based on any relevant OptionValues in the Honoree OptionGroup.
                 For example, if the configured contribution page id is 2:
                    href="/civicrm/contribute/transact?reset=1&id=2" on a date with no honoree
                    href="/civicrm/contribute/transact?reset=1&id=2&htype=1&hcid=123" on a date configured for contact id=123 who IS NOT deceased
                    href="/civicrm/contribute/transact?reset=1&id=2&htype=2&hcid=123" on a date configured for contact id=123 who IS deceased
            -->

            {*  This block disables the Donate button if the power level is full

            {if $powerTheCadetsItem.percentage < 100}
              <a href="{$powerTheCadetsItem.contribution_page_url}" id="pcadets-listing-link-donate-{$powerTheCadetsItemKey}" class="pcadets-listing-link-donate button">
                <span>Power this day</span>
              </a>
              <!-- Divs with class pcadets-listing-honoreeinfo will ONLY be included if there is an appropriate OptionValue for this date
                   in the Honorees OptionGroup
              -->
            {else}
              <div>{ts}This date is fully powered.{/ts}</div>
            {/if}

            *}

            <!-- Display the donate button regardless of power level-->
              <a href="{$powerTheCadetsItem.contribution_page_url}" id="pcadets-listing-link-donate-{$powerTheCadetsItemKey}" class="pcadets-listing-link-donate btn-ptc">
                <span>Power this day</span>
              </a>

            {if $powerTheCadetsItem.soft_credit_description}
              <div id="pcadets-listing-honoreeinfo-{$powerTheCadetsItemKey}" class="pcadets-listing-honoreeinfo">{$powerTheCadetsItem.soft_credit_description}</div>
            {/if}
          </td>
        </tr>
        {/foreach}
    </tbody>
  </table>
</div>