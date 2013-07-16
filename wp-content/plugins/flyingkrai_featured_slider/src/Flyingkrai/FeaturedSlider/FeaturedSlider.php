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
use Flyingkrai\Helpers\Resizer;

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
     * @var Mustache_Engine
     */
    protected static $mustache = null;
    /**
     * @var FeaturedSlider
     */
    private static $_instance = null;

    protected static $image_sizes = array(
        'big' => array('width' => 500, 'height' => 500),
        'thumb' => array('width' => 110, 'height' => 100),
        'admin-thumb' => array('width' => 220, 'height' => 100),
    );

    /**
     * init class and add wordpress hooks
     */
    protected function __construct()
    {
        //- class helpers
        self::$mustache = Mustache::getInstance();
        //- register new image size
        add_action('wp_handle_upload', array($this, 'fixImageSizes'));
        add_action('init', array($this, 'registerImageSize'));
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

        if (null === self::$_instance) {
            self::$_instance = new self;
        }

        return self::$_instance;
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

    public function fix_image_sizes($image)
    {
        if (!in_array($image[''], array('image/jpeg', 'image/gif', 'image/png')) ) {
            return $image;
        }

        $file = $image['file'];
        $imageInfo = getimagesize($file);
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        if ($width == $height) {
            return $image;
        }

        $type = ($width > $height) ? 'width' : 'height';

        foreach (self::$image_sizes as $size) {
            Resizer::get_instace()->resize($file, $size[$type], $type);
        }
    }

    /**
     * Get wp baseurl for upload dir
     *
     * @return string url
     */
    public static function get_upload_baseurl()
    {
        $uploadConfig = wp_upload_dir();

        return $uploadConfig['baseurl'];
    }

}

// expose public methods
