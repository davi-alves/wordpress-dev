<?php
/**
 * Featured Slider
 *
 * @category   Slideshow
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
 * FeaturedSlider class
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage FeaturedSlider
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class FeaturedSlider
{
    const DISPLAY_NAME = '[FeaturedSlider]';
    const HOME_DISPLAY_LIMIT = 3;
    const PLUGIN_NAMESPACE = 'flyingkrai_featuredslider';
    const VERSION = '1.0.0';
    /**
     * @var FeaturedSlider
     */
    protected static $instance = null;
    /**
     * @var Mustache_Engine
     */
    protected static $mustache = null;
    /**
     * @var boolean
     */
    protected $updated = false;

    /**
     * init class and add wordpress hooks
     */
    protected function __construct()
    {
        //- class helpers
        self::$mustache = Mustache::getInstance();
        //- register new image size
        add_action('after_setup_theme', array($this, 'registerImageSize'));
        //- init classes
        // new MetaBox(self::$mustache);
        new ConfigPage(self::$mustache);
    }

    /**
     * Get FeaturedSlider singleton
     *
     * @return FeaturedSlider
     */
    public static function getInstance()
    {

        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Activation hook
     *
     * @return void
     */
    public static function activate()
    {
    }

    /**
     * Deactivation hook
     *
     * @return void
     */
    public static function deactivate()
    {
    }

    /**
     * Register required images sizes
     *
     * @return void
     */
    public function registerImageSize()
    {
        global $_wp_theme_features;

        if (empty($_wp_theme_features['post-thumbnails'])) {
            $_wp_theme_features['post-thumbnails'] = true;
        }

        add_image_size('featured-slider-big', 500, 500);
        add_image_size('featured-slider-thumb', 100, 100);
        add_image_size('featured-slider-admin-thumb', 220, 100);
    }

}

// expose public methods
