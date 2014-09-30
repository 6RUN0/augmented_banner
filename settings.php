<?php

/**
 * $Id$
 *
 * @category Classes
 * @package  Augmented_Banner
 * @author   Squizz Caphinator, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

class pAugmentedBannerSettings extends pageAssembly
{

    public $page;
    private $_opt = array();
    private $_err = array();

    /**
     * Constructor methods for this classes.
     */
    function __construct()
    {
        parent::__construct();
        $this->queue('update');
        $this->queue('start');
        $this->queue('form');
    }

    /**
     * Conver old options.
     *
     * @return none
     */
    function update()
    {
        $this->_opt = config::get('augmented_banner_options');
        if (empty($this->_opt)) {
            $this->_opt = array(
                'num_days' => $this->_move('augmented_banner_numDays', 7),
                'max_displayed' => $this->_move('augmented_banner_maxDisplayed', 27),
                'display_corps' => $this->_move('augmented_banner_displayCorps', 'true'),
                'display_pilots' => $this->_move('augmented_banner_displayPilots', 'true'),
                'display_type' => $this->_move('augmented_banner_displayType', 'mixed'),
            );
        }
    }

    /**
     * Preparation of the form.
     *
     * @return none
     */
    function start()
    {
        $this->page = new Page();
        $this->page->setTitle('Settings - Augmented Banner');
        $this->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/augmented_banner/css/settings.css" />');

        if (isset($_POST['submit'])) {
            $this->_opt = $_POST['options'];
            $this->_err['text'] = '';
            $this->_opt['num_days'] = $this->_is_natural($this->_opt['num_days']);
            if (!$this->_opt['num_days']) {
                $this->_opt['num_days'] = '';
                $this->_err['text'] .= 'Error. Number of days it is natural number<br/>';
                $this->_err['num_days'] = 'augmented-banner-err';
            }
            $this->_opt['max_displayed'] = $this->_is_natural($this->_opt['max_displayed']);
            if (!$this->_opt['max_displayed']) {
                $this->_opt['max_displayed'] = '';
                $this->_err['text'] .= 'Error. Number of images it is natural nubber<br/>';
                $this->_err['max_displayed'] = 'augmented-banner-err';
            }
            config::set('augmented_banner_options', $this->_opt);
        }
    }

    /**
     * Render of the form.
     *
     * @return string html
     */
    function form()
    {
        global $smarty;
        $smarty->assign('augmented_banner_options', $this->_opt);
        $alliance = config::get('cfg_allianceid');
        if (!empty($alliance)) {
            $smarty->assign('augmented_banner_showcorp', true);
        }
        $smarty->assign('augmented_banner_err', $this->_err);
        return $smarty->fetch(get_tpl('./mods/augmented_banner/settings'));
    }

    /**
     * Build context.
     *
     * @return none
     */
    function context()
    {
        parent::__construct();
        $this->queue('menu');
    }

    /**
     * Render of admin menu.
     *
     * @return string html
     */
    function menu()
    {
        include 'common/admin/admin_menu.php';
        return $menubox->generate();
    }

    /**
     * Delete old key and return the value of key.
     *
     * @param string $opt     option name
     * @param mixed  $default default value
     *
     * @return mixed value of option or default value
     */
    private function _move($opt, $default = null)
    {
        $val = config::get($opt);
        if (isset($val)) {
            config::del($opt);
            return $val;
        }
        return $default;
    }

    /**
     * Finds whether the type of the given variable is natural number.
     *
     * @param string|int $num - number
     *
     * @return unsigned integer|false natular number or false
     */
    private function _is_natural($num) {
        $num = intval($num);
        if (is_int($num) && $num > 0) {
            return $num;
        }
        return false;
    }

}

$pageAssembly = new pAugmentedBannerSettings();
event::call('pAugmentedBannerSettings_assembling', $pageAssembly);
$html = $pageAssembly->assemble();
$pageAssembly->page->setContent($html);

$pageAssembly->context();
event::call('pAugmentedBannerSettings_context_assembling', $pageAssembly);
$context = $pageAssembly->assemble();
$pageAssembly->page->addContext($context);

$pageAssembly->page->generate();
