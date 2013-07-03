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

    protected static function getMenuPageId()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '-admin_page';
    }

    protected static function getMenuTitle()
    {
        return FeaturedSlider::DISPLAY_NAME . ' - Config';
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
        switch ($_GET['step']) {
            case 'settings':
            default:
                $this->settingsAcction();
                break;
        }
    }

    protected function settingsAcction($value='')
    {
        $this->mustache->render(
            'backend/config_page',
            array(
                'pageTitle' => self::getMenuTitle()
            )
        );
    }
}
