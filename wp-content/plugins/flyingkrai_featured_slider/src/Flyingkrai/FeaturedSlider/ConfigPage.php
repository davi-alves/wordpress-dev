<?php
/**
 * Featured Slider Configuration Page
 *
 * @category   Admin
 * @package    Flyingkrai
 * @subpackage FeaturedSlider
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */

namespace Flyingkrai\FeaturedSlider;

use Flyingkrai\Helpers\Mustache;

/**
 * FeaturedSlider ConfigPage class
 *
 * @category   Admin
 * @package    Flyingkrai
 * @subpackage FeaturedSlider
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class ConfigPage
{
    /**
     * @var Mustache
     */
    protected $mustache = null;

    /**
     * init class and add wordpress hooks
     *
     * @param Mustache $mustache templating object
     */
    public function __construct(Mustache $mustache)
    {
        //- class helpers
        $this->mustache = $mustache;
        //- add menus
        add_action('admin_menu', array($this, 'addAdminPage'));
    }

    public static function getImages($amount = 0)
    {
        $images = get_option(self::getImagesKey(), array());
        if($amount && count($images) !== 0) {
            return array_slice($images, 0, $amount);
        }

        return $images;
    }

    protected static function getMenuPageId()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '-admin_page';
    }

    protected static function getMenuTitle()
    {
        return FeaturedSlider::DISPLAY_NAME . ' - Gerência';
    }

    protected static function getImagesKey()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '_image_option';
    }

    public function addAdminPage()
    {
        //- add admin menu
        add_menu_page(
            self::getMenuTitle(),
            FeaturedSlider::DISPLAY_NAME,
            'manage_options',
            $this->getMenuPageId(),
            array($this, 'renderAdminPage')
        );
    }

    public function renderAdminPage()
    {
        if (!isset($_GET['step'])) {
            return $this->settingsAction();
        }

        switch ($_GET['step']) {
            case 'save':
                break;
            case 'settings':
            default:
                $this->settingsAction();
                break;
        }
    }

    protected function settingsAction($value='')
    {
        $this->mustache->render(
            'backend/config_page',
            array(
                'pageTitle' => self::getMenuTitle(),
                'screenIcon' => $this->getAdminIcon(),
                'form' => array(
                    'action' => $this->getAdminAction(array('step' => 'save')),
                    'nonce' => $this->getAdminFormNonce()
                ),
                'images' => self::getImages(),
            )
        );
    }

    protected function saveAction()
    {
        $images = $_POST['images'];
        $this->validate($images);
    }

    protected function getAdminAction($params = array())
    {
        return add_query_arg($params);
    }

    protected function getAdminIcon()
    {
        return get_screen_icon('upload');
    }

    protected function getAdminFormNonce()
    {
        return $this->getNonce('admin_form');
    }

    /**
     * Generate nonce hidden inpute
     *
     * @param  string $action nonce action
     *
     * @return string         nonce html
     */
    protected function getNonce($action)
    {
        return wp_nonce_field(
            FeaturedSlider::PLUGIN_NAMESPACE . '_nonce',
            FeaturedSlider::PLUGIN_NAMESPACE . "_{$action}",
            true,
            false
        );
    }

    protected function getTmpAdminImage()
    {
        return FLYINGKRAI_FEATURED_SLIDER_URL . 'asserts/blank.gif';
    }

    protected function validate(&$images)
    {
        $_tmp = array();
        foreach ($variable as $key => $value) {
            # code...
        }

    }
}
