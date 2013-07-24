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
use \stdClass;

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
    const PLUGIN_NAMESPACE = 'flyingkrai_featuredslider';
    const VERSION = '1.0.0';
    /**
     * @var Mustache_Engine
     */
    protected static $_mustache = null;
    /**
     * @var FeaturedSlider
     */
    private static $_instance = null;
    private static $_instancied = false;

    private static $_imageSizes = array(
        'fk-admin-thumb' => array('width' => 220, 'height' => 100, 'crop' => false),
    );

    private static $uploadConfig = array();

    protected function __construct()
    {
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
            self::$_instancied = true;
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
        update_option(self::get_instance()->get_admin_settings_key(), self::get_instance()->get_admin_settings());
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
     * init class and add wordpress hooks
     */
    public function init()
    {
        self::$uploadConfig = wp_upload_dir();
        //- class helpers
        self::$_mustache = Mustache::get_instance();
        //- register new image size
        add_filter( 'intermediate_image_sizes_advanced', array($this, 'add_image_sizes'));
        //- template redirect
        add_action( 'template_redirect', array($this, 'add_slideshow_to_home'));
        //- init classes
        new ConfigPage(self::$_instance, self::$_mustache);
    }

    public function get_admin_settings()
    {
        $settings = get_option(
            $this->get_admin_settings_key(),
            array(
                'slides_qty' => 3,
                'images' => array(
                    'big' => array(
                        'width' => 500,
                        'height' => 500,
                    ),
                    'thumb' => array(
                        'width' => 110,
                        'height' => 100,
                    ),
                ),
            )
        );

        return $settings;
    }

    public function get_images()
    {
        $images = get_option($this->get_images_key(), array());
        if (count($images) === 0) {
            return $images;
        }

        foreach ($images as $index => $image) {
            $_img = $this->get_image_by_id($image['id']);
            if (!$_img) {
                continue;
            }
            unset($image['url']);
            $images[$index] = array_merge($image, $_img);
        }

        return $images;
    }

    public function get_images_to_display()
    {
        $_imgs = $this->get_images();
        if (count($_imgs) === 0) {
            return $_imgs;
        }

       $images = array();
        foreach ($_imgs as $key => $image) {
            $images[$key] = new stdClass;
            foreach ($image['sizes'] as $size => $data) {
                if (strpos($size, 'admin') !== false) {
                    continue;
                }
                $size = str_replace('fk-', '', $size);
                $images[$key]->$size = (object)$data;
            }
            $images[$key]->link = $image['link'];
            $images[$key]->legend = $image['legend'];
        }

        $amount = $this->get_slides_qty_setting();

        return array_slice($images, 0, $amount);
    }

    public function get_image_by_id($id)
    {
        $url = wp_get_attachment_url($id);
        if (!$id) {
            return false;
        }

        return array(
            'id' => $id,
            'url' => $url,
            'sizes' => $this->get_image_sizes_by_id($id)
        );
    }

    private function get_image_sizes_by_id($id)
    {
        $sizes = array();
        foreach ($this->get_images_settings() as $size => $config) {
            list($url, $width, $height) = wp_get_attachment_image_src($id, $size);
            $sizes[$size] = compact('url', 'width', 'height');
        }

        return $sizes;
    }

    public function get_images_key()
    {
        return self::PLUGIN_NAMESPACE . '_image_option';
    }

    public function get_admin_settings_key()
    {
        return self::PLUGIN_NAMESPACE . '_settings_option';
    }

    public function add_image_sizes($sizes)
    {
        if (!is_array($sizes)) {
            $sizes = array();
        }
        $imageSizes = $this->get_images_settings();

        return array_merge($sizes, $imageSizes);
    }

    public function add_slideshow_to_home()
    {
        if (is_home()) {
            $themeTemplate = get_template_directory() . '/home-featuredslideshow.php';
            if (is_file($themeTemplate)) {
                include($template);
            } else {
                include($this->get_views_path() . '/frontend/home-featuredslideshow.php');
            }
            exit();
        }
    }

    protected function get_images_settings()
    {
        $settings = $this->get_admin_settings();
        $imageSizes = self::$_imageSizes;
        $imageSizes = array_merge(
            $imageSizes,
            array(
                'fk-big' => array_merge(
                    $settings['images']['big'],
                    array('crop' => false)
                ),
                'fk-thumb' => array_merge(
                    $settings['images']['thumb'],
                    array('crop' => false)
                ),
            )
        );

        return $imageSizes;
    }

    private function get_slides_qty_setting()
    {
        $settings = $this->get_admin_settings();
        return $settings['slides_qty'];
    }

    private function get_views_path()
    {
        return FLYINGKRAI_FEATURED_SLIDER_PATH . 'src/views';
    }

}
