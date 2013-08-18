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
 *
 * @wordpress-plugin
 * Plugin Name: Featured Slider
 * Description: Slider com produtos destacados.
 * Version:     1.0.0
 * Author:      Davi Alves
 * License:     GNU General Public License
 * License URI: http://www.gnu.org/copyleft/gpl.txt
 */

//- validation against direct access
if (!defined('WPINC')) {
    die('I see you.');
}

//- constants
defined('FLYINGKRAI_FEATURED_SLIDER_PATH') ||
    define('FLYINGKRAI_FEATURED_SLIDER_PATH', plugin_dir_path(__FILE__));
//- constants
defined('FLYINGKRAI_FEATURED_SLIDER_URL') ||
    define('FLYINGKRAI_FEATURED_SLIDER_URL', plugins_url('public/', __FILE__));

//- include composer autoloader
require 'vendor/autoload.php';

//- initialize class
Flyingkrai_FeaturedSlider::get_instance()->init();

//- activation rooks
register_activation_hook(__FILE__, array( 'Flyingkrai_FeaturedSlider', 'activate' ));
register_deactivation_hook(__FILE__, array( 'Flyingkrai_FeaturedSlider', 'deactivate' ));

//- expose wp loop alike funcions
$flyingkrai_slides_helper = new Flyingkrai_Helpers_Slides;

function fk_have_slides()
{
    global $flyingkrai_slides_helper;

    return $flyingkrai_slides_helper->have_slides();
}

function fk_the_slide()
{
    global $flyingkrai_slides_helper;

    $flyingkrai_slides_helper->walk();
}

function fk_get_the_slide()
{
    global $flyingkrai_slides_helper;

    return $flyingkrai_slides_helper->get_current();
}

function fk_the_title()
{
    global $flyingkrai_slides_helper;

    print esc_attr($flyingkrai_slides_helper->get_current()->title);
}

function fk_the_address()
{
    global $flyingkrai_slides_helper;

    print esc_attr($flyingkrai_slides_helper->get_current()->address);
}

function fk_the_link()
{
    global $flyingkrai_slides_helper;

    $post = $flyingkrai_slides_helper->get_current()->post;
    if ($post) {
        print get_permalink($post->link);
    }
}

function fk_the_big_url()
{
    global $flyingkrai_slides_helper;

    print $flyingkrai_slides_helper->get_current()->big->url;
}

function fk_the_thumb_url()
{
    global $flyingkrai_slides_helper;

    print $flyingkrai_slides_helper->get_current()->thumb->url;
}

function fk_reset_slides()
{
    global $flyingkrai_slides_helper;

    $flyingkrai_slides_helper->reset();
}
