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

//- activation rooks
register_activation_hook(__FILE__, array( 'Flyingkrai\\FeaturedSlider\\FeaturedSlider', 'activate' ));
register_deactivation_hook(__FILE__, array( 'Flyingkrai\\FeaturedSlider\\FeaturedSlider', 'deactivate' ));

//- initialize class
FeaturedSlider::get_instance();
