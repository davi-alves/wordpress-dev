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
    }
}
