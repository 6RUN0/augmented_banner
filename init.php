<?php

/**
 * $Id$
 *
 * @category Init
 * @package  Augmented_Banner
 * @author   Squizz Caphinator, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

require_once 'mods/augmented_banner/augmented_banner.php';

$modInfo['augmented_banner'] = array(
    'name' => 'Augmented Banner',
    'abstract' => "If you have enabled this mod and don't see corps or pilots beneath your banner, then you have probably not added this line to your active template's <strong>index.tpl</strong> file:<br /><br /><code>{if isset(\$augmented_banner)}{\$augmented_banner}{/if}</code><br /><br />Look for the first table with the class navigation and add that line prior to the table line.",
    'about' => 'Created by Squizz Caphinator, patched by Boris Blade Artrald.<br /><a href="https://github.com/6RUN0/augmented_banner">Get Augmented Banner</a>',
);

event::register('page_assembleheader', 'AugmentedBanner::add');
