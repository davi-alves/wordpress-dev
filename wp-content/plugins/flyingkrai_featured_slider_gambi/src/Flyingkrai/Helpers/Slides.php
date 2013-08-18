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

/**
 * Slides helper class
 *
 * @category   Slideshow
 * @package    Flyingkrai
 * @subpackage Helpers
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class Flyingkrai_Helpers_Slides
{
    protected $slides = array();
    protected $current = null;
    protected $queried = false;
    protected $memory = array();

    public function get_slides()
    {
        return $this->slides;
    }

    public function walk()
    {
        $this->current = array_shift($this->slides);
    }

    public function get_current()
    {
        return $this->current;
    }

    public function have_slides()
    {
        if (!$this->queried) {
            $slides = Flyingkrai_FeaturedSlider::get_instance()->get_images_to_display();
            $this->slides = $slides;
            $this->memory = $slides;
            $this->queried = true;
        }

        return !empty($this->slides);
    }

    public function reset()
    {
        $this->slides = $this->memory;
    }
}
