<?php

require_once('common/admin/admin_menu.php');

function ab_setConfiguration($post, $key) {
  config::set($key, $post[$key]);
}

function setDefaultConfig_ab2($key, $value) {
  if (config::get($key) == null || config::get($key) == "" ) config::set($key, $value);
}

if ($_POST) {
  ab_setConfiguration($_POST, 'augmented_banner_numDays');
  ab_setConfiguration($_POST, 'augmented_banner_maxDisplayed');
  ab_setConfiguration($_POST, 'augmented_banner_displayCorps');
  ab_setConfiguration($_POST, 'augmented_banner_displayPilots');
  ab_setConfiguration($_POST, 'augmented_banner_displayType');
}

setDefaultConfig_ab2('augmented_banner_numDays', 7);
setDefaultConfig_ab2('augmented_banner_maxDisplayed', 27);
setDefaultConfig_ab2('augmented_banner_displayCorps', 'true');
setDefaultConfig_ab2('augmented_banner_displayPilots', 'true');
setDefaultConfig_ab2('augmented_banner_displayType', 'mixed');

$numDays = config::get('augmented_banner_numDays');
$maxDisplayed = config::get('augmented_banner_maxDisplayed');
$displayCorps = config::get('augmented_banner_displayCorps');
$displayPilots = config::get('augmented_banner_displayPilots');
$displayType = config::get('augmented_banner_displayType');
$alliID = (int) config::get('cfg_allianceid');

$html = '<div class="block-header2">Settings</div>';
$html .= '<form method="post">';
$html .= '<div><label for name="augmented_banner_numDays">Number of Days to Count:</label><input size="3" name="augmented_banner_numDays" type="text" value="' . $numDays . '" /></div>';
$html .= '<div><label for name="augmented_banner_maxDisplayed">Maximum Images to Display:</label><input size="3" name="augmented_banner_maxDisplayed" type="text" value="' . $maxDisplayed . '" /></div>';

if ($alliID != 0 ) {
  $selected1 = $displayCorps == 'true' ? ' selected="selected"' : '';
  $selected2 = $displayCorps == 'false' ? ' selected="selected"' : '';
  $html .= '
    <div>
    <label for name="augmented_banner_displayCorps">Display Corps?</label>
      <select name="augmented_banner_displayCorps">
        <option' . $selected1 . ' value="true" >Yes</option>
        <option' . $selected2 . ' value="false" >No</option>
      </select>
    </div>';
}

$selected1 = $displayPilots == 'true' ? ' selected="selected"' : '';
$selected2 = $displayPilots == 'false' ? ' selected="selected"' : '';
$html .= '
  <div>
    <label for name="augmented_banner_displayPilots">Display Pilots:</label>
    <select name="augmented_banner_displayPilots">
      <option value="true"' . $selected1 . ' >Yes</option>
      <option value="false"' . $selected2 . ' >No</option>
    </select>
  </div>';

$selected1 = $displayType == 'mixed' ? ' selected="selected"' : '';
$selected2 = $displayType == 'straight' ? ' selected="selected"' : '';
$html .= '
  <div>
    <label for name="augmented_banner_displayType">Display Type:</label>
    <select name="augmented_banner_displayType">
      <option' . $selected1 . ' value="mixed" >Mixed Corps/Pilots</option>
      <option' . $selected2 . ' value="straight" >Corps then Pilots</option>
    </select>
  </div>';

$html .= '<div><input type="Submit" value="Save" /></div></form>';

$html .= '<div class="block-header2">Patch template</div>';

$html .= "If you have enabled this mod and don't see corps or pilots beneath your banner, then<br />
you have probably not added this line to your active template's <strong>index.tpl</strong> file:<br /><br />
<code>{if isset(\$augmented_banner)}{\$augmented_banner}{/if}</code><br /><br />
Look for the first table with the class navigation and add that line prior to the table line.<br />";

$html .= '<div class="block-header2">About</div><i>-- Squizz Caphinator</i><br />
<a href="http://eve-id.net/forum/viewtopic.php?&t=17311">EVE ID Forum Posting</a>';

$page = new Page('Augmented Banner');
$page->setAdmin();
$page->setContent($html);
$page->addContext($menubox->generate());
$page->generate();
