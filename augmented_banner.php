<?php

$config = new Config(KB_SITE);

function setDefaultConfig($key, $value) {
  if (config::get($key) == NULL) config::set($key, $value);
}

function banner_sort_callback($a, $b) {
  if ($a['cnt'] == $b['cnt']) {
    return 0;
  }
  return ($a['cnt'] > $b['cnt']) ? -1 : 1;
}

setDefaultConfig('augmented_banner_numDays', 7);
setDefaultConfig('augmented_banner_maxDisplayed', 27);
setDefaultConfig('augmented_banner_displayCorps', 'true');
setDefaultConfig('augmented_banner_displayPilots', 'true');
setDefaultConfig('augmented_banner_displayType', 'mixed');

$numDays = (int) config::get('augmented_banner_numDays');
$maxDisplayed = (int) config::get('augmented_banner_maxDisplayed');
$displayCorps = 'true' == config::get('augmented_banner_displayCorps');
$displayPilots = 'true' == config::get('augmented_banner_displayPilots');
$displayType = config::get('augmented_banner_displayType');
$alliID = config::get('cfg_allianceid');
$corpID = config::get('cfg_corpid');
$pilotID = config::get('cfg_pilotid');
$podNoobs = config::get('podnoobs');

$items = array();

if (!empty($alliID) && $displayCorps) {
  $corpList = new TopCorpKillsList();
  $corpList->addInvolvedAlliance($alliID);
  $corpList->setLimit($maxDisplayed);
  $corpList->setPodsNoobShips($podNoobs);
  $corpList->setStartDate(date('Y-m-d H:i',strtotime("- $numDays days")));
  $corpList->generate();
  while ($row = $corpList->getRow()) {
    $corp = new Corporation($row['crp_id']);
    $items[] = array(
      'cnt' => $row['cnt'],
      'obj' => $corp,
    );
  }
}

if ($displayPilots) {
  $list = new TopKillsList();
  $list->setLimit($maxDisplayed);
  if (!empty($alliID)) {
    $list->addInvolvedAlliance($alliID);
  }
  if (!empty($corpID)) {
    $list->addInvolvedCorp($corpID);
  }
  if (!empty($pilotID)) {
    $list->addInvolvedPilot($pilotID);
  }
  $list->setPodsNoobShips($podNoobs);
  $list->setStartDate(date('Y-m-d H:i',strtotime("- $numDays days")));
  $list->generate();
  while ($row = $list->getRow() ) {
    $pilot = new Pilot($row['plt_id']);
    $items[] = array(
      'cnt' => $row['cnt'],
      'obj' => $pilot,
    );
  }
}

if ($displayType == 'mixed') {
  uasort($items, "banner_sort_callback");
}

$html = '';
$numListed = 0;
foreach ($items as $item) {
  $src = $item['obj']->getPortraitURL(32);
  $href = $item['obj']->getDetailsURL();
  $name = $item['obj']->getName();
  $count = $item['cnt'];
  $alt = "$name - $count kills over the last $numDays days";
  $html .= "<div class=\"augmented-banner-item\"><a href=\"${href}\"><img class=\"augmented-banner-img\" src=\"${src}\" alt=\"${alt}\" title=\"${alt}\" /></a></div>";
  if (++$numListed >= $maxDisplayed) break;
}

$html = "<div class=\"augmented-banner-wrap\">${html}</div>";
