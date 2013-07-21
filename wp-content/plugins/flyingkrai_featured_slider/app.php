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

use \Flyingkrai\FeaturedSlider\FeaturedSlider;

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
FeaturedSlider::get_instance()->init();

//- activation rooks
register_activation_hook(__FILE__, array( 'Flyingkrai\\FeaturedSlider\\FeaturedSlider', 'activate' ));
register_deactivation_hook(__FILE__, array( 'Flyingkrai\\FeaturedSlider\\FeaturedSlider', 'deactivate' ));

//- expose funtions to global scope

function flyingkrai_get_slideshow_images($amount = 0)
{
    return FeaturedSlider::get_instance()->get_images_to_display($amount);
}
