<?php
/**
 * Featured Slider
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage Helpers
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */

namespace Flyingkrai\Helpers;

use \Mustache_Engine;
use \Mustache_Loader_FilesystemLoader;

/**
 * Mustache helper class
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage Helpers
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class Mustache extends Mustache_Engine
{
    /**
     * @var Mustache
     */
    private static $_instance = null;

    /**
     * hidding constructor
     */
    public function __construct()
    {
        $viewsPath = FLYINGKRAI_FEATURED_SLIDER_PATH . 'src/views';
        parent::__construct(
            array(
                'loader' => new Mustache_Loader_FilesystemLoader($viewsPath),
                'partials_loader' => new Mustache_Loader_FilesystemLoader($viewsPath),
            )
        );
    }

    /**
     *  Render the template
     *
     * @param string $template file path
     * @param mixed  $context  template params
     *
     * @see   Mustache_Engine::render()
     * @return  void
     */
    public function render($template, $context = array())
    {
        print parent::render($template, $context);
    }

    /**
     *  Return Mustache_Engine singleton
     *
     *  @see Mustache_Engine
     *  @return Mustache
     */
    public static function get_instance()
    {
        if (null === self::$_instance) {

            self::$_instance = new self;
        }

        return self::$_instance;
    }
}
