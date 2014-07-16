<?php

$modInfo['augmented_banner']['name'] = "Augmented Banner";
$modInfo['augmented_banner']['abstract'] = "If you have enabled this mod and don't see corps or pilots beneath your banner, then you have probably not added this line to your active template's <strong>index.tpl</strong> file:<br /><br />
<code>{if isset(\$augmented_banner)}{\$augmented_banner}{/if}</code><br /><br />
Look for the first table with the class navigation and add that line prior to the table line.";
$modInfo['augmented_banner']['about'] = 'Created by Squizz Caphinator, patched by Boris Blade Artrald.<br />
<a href="https://github.com/6RUN0/augmented_banner">Get Augmented Banner</a>';

event::register("page_assembleheader", "augmented_banner::add");

class augmented_banner {
  function add($home){
    global $smarty;
    include_once('mods/augmented_banner/augmented_banner.php');
    $smarty->assign("augmented_banner", $html);
    $home->addHeader("\t<link rel=\"stylesheet\" type=\"text/css\" href=\"mods/augmented_banner/augmented_banner.css\" />");
  }
}
