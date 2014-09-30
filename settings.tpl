<div class="block-header2">Settings</div>
{if !empty($augmented_banner_err.text)}
    <div class="augmented-banner-msg">{$augmented_banner_err.text}</div>
{/if}
<form method="post" name="options[]">
  <div>
    <label for name="options[num_days]">Number of Days to Count:</label>
    <input {if isset($augmented_banner_err.num_days)}class="{$augmented_banner_err.num_days}" {/if}size="3" name="options[num_days]" type="text" value="{$augmented_banner_options.num_days}" />
 </div>
  <div>
    <label for name="options[max_displayed]">Maximum Images to Display:</label>
    <input {if isset($augmented_banner_err.max_displayed)}class="{$augmented_banner_err.max_displayed}" {/if}size="3" name="options[max_displayed]" type="text" value="{$augmented_banner_options.max_displayed}" />
 </div>
  {if $augmented_banner_showcorp}
    <div>
    <label for name="options[display_corps]">Display Corps?</label>
      <select name="options[display_corps]">
        <option value="true" {if $augmented_banner_options.display_corps == 'true'}selected{/if}>Yes</option>
        <option value="false" {if $augmented_banner_options.display_corps == 'false'}selected{/if}>No</option>
      </select>
    </div>
  {/if}
  <div>
    <label for name="options[display_pilots]">Display Pilots:</label>
    <select name="options[display_pilots]">
      <option value="true"{if $augmented_banner_options.display_pilots == 'true'}selected{/if}>Yes</option>
      <option value="false"{if $augmented_banner_options.display_pilots == 'false'}selected{/if}>No</option>
    </select>
  </div>
  <div>
    <label for name="options[display_type]">Display Type:</label>
    <select name="options[display_type]">
      <option value="mixed" {if $augmented_banner_options.display_type == 'mixed'}selected{/if}>Mixed Corps/Pilots</option>
      <option value="straight" {if $augmented_banner_options.display_type == 'straight'}selected{/if}>Corps then Pilots</option>
    </select>
  </div>
  <div><input type="Submit" name='submit' value="Save" /></div>
</form>
<div class="block-header2">Patch template</div>
If you have enabled this mod and don't see corps or pilots beneath your banner, then<br />
you have probably not added this line to your active template's <strong>index.tpl</strong> file:<br /><br />
<code>&#123;if isset($augmented_banner)&#125;&#123;$augmented_banner&#125;&#123;/if&#125;</code><br /><br />
Look for the first table with the class navigation and add that line prior to the table line.<br />
<div class="block-header2">About</div><i>-- Squizz Caphinator</i><br />
<a href="http://eve-id.net/forum/viewtopic.php?&t=17311">EVE ID Forum Posting</a>
