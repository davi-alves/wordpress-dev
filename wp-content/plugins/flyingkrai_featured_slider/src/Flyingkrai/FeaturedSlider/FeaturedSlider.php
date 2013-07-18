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

    private static $imageSizes = array(
        'fk-big' => array('width' => 500, 'height' => 500, 'crop' => false),
        'fk-thumb' => array('width' => 110, 'height' => 100, 'crop' => false),
        'fk-admin-thumb' => array('width' => 220, 'height' => 100, 'crop' => false),
    );

    private static $uploadConfig = array();

    /**
     * init class and add wordpress hooks
     */
    protected function __construct()
    {
        self::$uploadConfig = wp_upload_dir();
        //- class helpers
        self::$mustache = Mustache::get_instance();
        //- register new image size
        add_filter( 'intermediate_image_sizes_advanced', array($this, 'add_image_sizes'));
        // add_action('wp_handle_upload', array($this, 'fix_image_sizes'));
        // add_filter('image_resize_dimensions', array($this, 'add_image_sizes'));
        //- init classes
        // new MetaBox(self::$mustache);
        new ConfigPage(self::$mustache);
    }

    /**
     * Get FeaturedSlider singleton
     *
     * @return FeaturedSlider
     */
    public static function get_instance()
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

    public static function get_images($amount = 0)
    {
        $images = get_option(self::get_images_key(), array());
        if($amount && count($images) !== 0) {
            return array_slice($images, 0, $amount);
        }

        foreach ($images as $index => $image) {
            $_img = self::get_image_by_id($image['id']);
            if (!$_img) {
                continue;
            }
            unset($image['url']);
            $images[$index] = array_merge($image, $_img);
        }

        return $images;
    }

    public static function get_image_by_id($id)
    {
        $url = wp_get_attachment_url($id);
        if (!$id) {
            return false;
        }

        return array(
            'id' => $id,
            'url' => $url,
            'sizes' => self::get_image_sizes_by_id($id)
        );
    }

    public static function get_image_sizes_by_id($id)
    {
        $sizes = array();
        foreach (self::$imageSizes as $size => $config) {
            list($url, $width, $height) = wp_get_attachment_image_src($id, $size);
            $sizes[$size] = compact('url', 'width', 'height');
        }

        return $sizes;
    }

    public static function get_images_key()
    {
        return self::PLUGIN_NAMESPACE . '_image_option';
    }

    public function add_image_sizes($sizes)
    {
        if (!is_array($sizes)) {
            $sizes = array();
        }

        return array_merge($sizes, self::$imageSizes);
    }

}

// expose public methods

function flyingkrai_get_slideshow_images($amount = 3)
{
    return FeaturedSlider::get_images($amount);
}
