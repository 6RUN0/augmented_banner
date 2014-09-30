<?php

/**
 * $Id$
 *
 * @category Classes
 * @package  Augmented_Banner
 * @author   Squizz Caphinator, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

/**
 * Provides callback for event::register.
 */
class AugmentedBanner
{

    /**
     * Render the augmented banner.
     *
     * @param Page $home object of Page class
     *
     * @return none
     */
    function add($home)
    {
        global $smarty;
        $options = config::get('augmented_banner_options');
        if (empty($options)) {
            $options = array(
                'num_days' => '7',
                'max_displayed' => '27',
                'display_corps' => 'true',
                'display_pilots' => 'true',
                'display_type' => 'mixed',
            );
        }

        $alliID = config::get('cfg_allianceid');
        $corpID = config::get('cfg_corpid');
        $pilotID = config::get('cfg_pilotid');
        $podNoobs = config::get('podnoobs');

        $items = array();

        if (!empty($alliID) && $options['display_corps'] == 'true') {
            $corpList = new TopCorpKillsList();
            $corpList->addInvolvedAlliance($alliID);
            $corpList->setLimit($options['max_displayed']);
            $corpList->setPodsNoobShips($podNoobs);
            $corpList->setStartDate(self::_date($options['num_days']));
            $corpList->generate();
            while ($row = $corpList->getRow()) {
                $corp = new Corporation($row['crp_id']);
                $items[] = array(
                    'cnt' => $row['cnt'],
                    'obj' => $corp,
                );
            }
        }

        if ($options['display_pilots'] == 'true') {
            $list = new TopKillsList();
            $list->setLimit($options['max_displayed']);
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
            $list->setStartDate(self::_date($options['num_days']));
            $list->generate();
            while ($row = $list->getRow() ) {
                $pilot = new Pilot($row['plt_id']);
                $items[] = array(
                    'cnt' => $row['cnt'],
                    'obj' => $pilot,
                );
            }
        }

        if (!empty($items)) {
            if ($options['display_type'] == 'mixed') {
                uasort(
                    $items,
                    function ($a, $b) {
                        if ($a['cnt'] == $b['cnt']) {
                            return 0;
                        }
                        return ($a['cnt'] > $b['cnt']) ? -1 : 1;
                    }
                );
            }
            $html = '';
            $numListed = 0;
            foreach ($items as $item) {
                $src = $item['obj']->getPortraitURL(32);
                $href = $item['obj']->getDetailsURL();
                $name = $item['obj']->getName();
                $count = $item['cnt'];
                $alt = "$name - $count kills over the last " . $options['num_days'] . ' days';
                $html .= "<div class=\"augmented-banner-item\"><a href=\"${href}\"><img class=\"augmented-banner-img\" src=\"${src}\" alt=\"${alt}\" title=\"${alt}\" /></a></div>";
                if (++$numListed >= $options['max_displayed']) break;
            }
            $html = "<div class=\"augmented-banner-wrap\">${html}</div>";
            $smarty->assign('augmented_banner', $html);
            $home->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/augmented_banner/css/style.css" />');
        }
    }

    /**
     * Format date.
     *
     * @param int|string $days count of days
     *
     * @return string date in format '%Y-%m-%d %H:%i'
     */
    private static function _date($days)
    {
        return date('Y-m-d H:i', strtotime('- ' . $days . ' days'));
    }
}
