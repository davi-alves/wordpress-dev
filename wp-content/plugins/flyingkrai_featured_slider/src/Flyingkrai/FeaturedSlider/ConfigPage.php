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
    const ERROR_SAVING           = 1;
    const ERROR_IMAGES_INVALID   = 2;
    const ERROR_SETTINGS_INVALID = 3;
    const ERROR_INVALID_NONCE    = 4;

    /**
     * @var Mustache
     */
    private $mustache = null;
    /**
     * @var FeaturedSlider
     */
    private $featuredSlider = null;

    /**
     * init class and add wordpress hooks
     *
     * @param Mustache $mustache templating object
     */
    public function __construct(FeaturedSlider $featuredSlider, Mustache $mustache)
    {
        //- main class instance
        $this->featuredSlider = $featuredSlider;
        //- class helpers
        $this->mustache = $mustache;
        //- enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
        //- set ajax request handler
        add_action('wp_ajax_' . $this->get_admin_script_id(), array($this, 'handle_admin_ajax'));
        //- add menus
        add_action('admin_menu', array($this, 'add_admin_page'));
    }

    public function admin_scripts()
    {
        if (!$this->is_admin_page()) {
            return;
        }
        //- styles
        wp_enqueue_style('jquery-ui', FLYINGKRAI_FEATURED_SLIDER_URL .'styles/jquery-ui/smoothness/jquery-ui.min.css');
        wp_enqueue_style('flying-admin',  FLYINGKRAI_FEATURED_SLIDER_URL . 'styles/admin/admin.css');
        //- scripts
        $scriptId = $this->get_admin_script_id();
        //- add scripts with dependencies
        wp_enqueue_script(
            $scriptId,
            FLYINGKRAI_FEATURED_SLIDER_URL . 'scripts/admin/admin.js',
            array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'thickbox', 'media-upload'),
            FeaturedSlider::VERSION
        );
        //- localize the script and prepend an object to it
        wp_localize_script(
            $scriptId,
            'Flying',
            array(
                'url' => admin_url('admin-ajax.php'),
                'admin_action' => $scriptId,
                'nonce' => $this->get_nonce_ajax()
            )
        );
    }

    public function admin_styles()
    {
        if (!$this->is_admin_page()) {
            return;
        }

        wp_enqueue_style( 'thickbox');
    }

    public function add_admin_page()
    {
        //- add admin menu
        add_menu_page(
            $this->get_menu_title(),
            FeaturedSlider::DISPLAY_NAME,
            'manage_options',
            $this->get_menu_page_id(),
            array($this, 'render_admin_page')
        );
    }

    public function render_admin_page()
    {
        if (!isset($_GET['step'])) {
            return $this->settings_action();
        }

        switch ($_GET['step']) {
            case 'save':
                $this->save_action();
                break;
            case 'settings':
            default:
                $this->settings_action();
                break;
        }
    }

    public function handle_admin_ajax()
    {
        if (!isset($_POST['ajaxAction'])){
            die('Action not set');
        }
        if (!isset($_POST['nonce'])){
            die('Missing Nonce');
        }

        if (!wp_verify_nonce($_POST['nonce'], $this->get_admin_script_id())) {
            die('Busted!');
        }

        switch ($_POST['ajaxAction']) {
            case 'new_line':
                if (!isset($_POST['image']) || !is_array($_POST['image'])) {
                    die('Image missing');
                }

                $image = $_POST['image'];
                $imageId = (isset($image['id'])) ? $image['id'] : 0;
                if (!$imageId) {
                    die('Image info missing');
                }
                print $this->mustache->render(
                    'backend/_partials/image_tr',
                    $this->featuredSlider->get_image_by_id($imageId)
                );
                break;
            case 'empty_line':
                print $this->mustache->render('backend/_partials/empty_tr');
                break;

            default:
                die('Nothing to do here');
                break;
        }

        exit;
    }

    protected function settings_action()
    {
        $pageConfig = array(
                'screenIcon' => $this->get_admin_icon(),
                'pageTitle' => self::get_menu_title(),
                'tabs' => array(
                    'slides' => $this->get_admin_tab_link('slides'),
                    'settings' => $this->get_admin_tab_link('settings'),
                ),
                'message' => $this->get_request_message(),
        );
        $this->get_settings_by_tab($pageConfig);

        $this->mustache->render('backend/config_page', $pageConfig);
    }

    protected function save_action()
    {
        if (!$this->validate_admin_form_nonce()) {
            $this->redirect_to_form(array('error' => self::ERROR_INVALID_NONCE, 'tab' => $tab));
        }
        $this->save_by_tab();
    }

    protected function is_admin_page()
    {
        $screen = get_current_screen();
        return is_object($screen) && $screen->id == $this->get_menu_page_wp_id();
    }

    protected function get_settings_by_tab(&$pageConfig)
    {
        $currentTab = (isset($_GET['tab']) && $_GET['tab']) ? $_GET['tab'] : 'slides';

        switch ($currentTab) {
            default:
            case 'slides':
                $pageConfig['tabs']['isSlideActive'] = 'nav-tab-active';
                $pageConfig = array_merge(
                    $pageConfig,
                    array(
                        'form' => array(
                            'action' => $this->get_admin_action_url(array('step' => 'save', 'tab' => 'slides')),
                            'nonce' => $this->get_admin_form_nonce()
                        ),
                        'images' => $this->featuredSlider->get_images(),
                    )
                );
                break;
            case 'settings':
                $pageConfig['tabs']['isSettingsActive'] = 'nav-tab-active';
                $pageConfig = array_merge(
                    $pageConfig,
                    array(
                        'form' => array(
                            'action' => $this->get_admin_action_url(array('step' => 'save', 'tab' => 'settings')),
                            'nonce' => $this->get_admin_form_nonce()
                        ),
                        'settings' => $this->featuredSlider->get_admin_settings(),
                    )
                );
                break;
        }
    }

    protected function save_by_tab()
    {
        $tab = isset($_GET['tab']) && $_GET['tab'] ? $_GET['tab'] : 'slides';

        switch ($tab) {
            case 'slides':
            default:
                $images = (isset($_POST['images']) && is_array($_POST['images'])) ? $_POST['images'] : array();
                if (!empty($images) && !$this->validate_images($images)) {
                    $this->redirect_to_form(array('error' => self::ERROR_IMAGES_INVALID, 'tab' => $tab));
                }
                $images = array_merge(array(), $images);

                update_option($this->featuredSlider->get_images_key(), $images);
                $this->redirect_to_form(array('images_updated' => true, 'tab' => $tab));
                break;
            case 'settings':
                $settings = (isset($_POST['settings']) && $_POST['settings']) ? $_POST['settings'] : array();
                if(empty($settings) || !$this->validate_settings($settings)) {
                    $this->redirect_to_form(array('error' => self::ERROR_SETTINGS_INVALID, 'tab' => $tab));
                }

                update_option($this->featuredSlider->get_admin_settings_key(), $settings);
                $this->redirect_to_form(array('settings_updated' => true, 'tab' => $tab));
                break;
        }
    }

    protected function get_admin_action_url($params = array())
    {
        return add_query_arg($params, $this->get_menu_page_url());
    }

    protected function redirect_to_form($params = array())
    {
        $params = array_merge(array('step' => 'settings'), $params);
        print '<script>window.location.href="' .
            $this->get_admin_action_url($params) . '";</script>';
        exit;
    }

    protected function get_admin_icon()
    {
        return get_screen_icon('upload');
    }

    protected function get_menu_page_id()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '_admin_page';
    }

    protected function get_menu_page_wp_id()
    {
        return 'toplevel_page_' . $this->get_menu_page_id();
    }

    protected function get_menu_page_url()
    {
        return menu_page_url($this->get_menu_page_id(), false);
    }

    protected function get_menu_title()
    {
        return FeaturedSlider::DISPLAY_NAME . ' - Gerência';
    }

    protected function get_admin_tab_link($tab)
    {
        return $this->get_admin_action_url(array('tab' => $tab));
    }

    protected function get_admin_form_nonce_id()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '_nonce';
    }

    protected function get_admin_form_nonce_field()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '_field';
    }

    protected function get_admin_form_nonce()
    {
        return wp_nonce_field(
            $this->get_admin_form_nonce_id(),
            $this->get_admin_form_nonce_field(),
            true,
            false
        );
    }

    protected function get_admin_script_id()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '_ajax';
    }

    protected function get_nonce_ajax()
    {
        return $this->get_nonce_code($this->get_admin_script_id());
    }

    /**
     * Generate nonce code
     *
     * @param  string $id nonce id to be mixed
     *
     * @return string     nonce string
     */
    protected function get_nonce_code($id)
    {
        return wp_create_nonce($id);
    }

    protected function get_request_message()
    {
        $message = array();

        $error = (isset($_GET['error']) && $_GET['error']) ? $_GET['error'] : 0;
        if ($error) {
            $message['type'] = 'error';
            switch ($error) {
                case self::ERROR_SAVING:
                    $message['text'] = 'Ops! Ocorreu um erro ao salvar as configurações.';
                    break;
                case self::ERROR_IMAGES_INVALID:
                    $message['text'] = 'Imagens inválidas.';
                    break;
                case self::ERROR_SETTINGS_INVALID:
                    $message['text'] = 'Configurações inválidas.';
                    break;
                case self::ERROR_INVALID_NONCE:
                    $message['text'] = 'Esta página esta inválida, favor acessar novamente pelo menu.';
                    break;
            }
        } elseif(isset($_GET['images_updated'])) {
            $message = array(
                'type' => 'updated',
                'text' => 'Slide atualizado com sucesso',
            );
        } elseif(isset($_GET['settings_updated'])) {
            $message = array(
                'type' => 'updated',
                'text' => 'Configurações atualizadas com sucesso. <strong>Atenção!</strong> Caso alguma das proporções tenha sido alterada, as imagens precisarão ser reenviadas.',
            );
        }

        return $message;
    }

    protected function validate_images($images)
    {
        $validated = true;
        foreach ($images as $image) {
            if (!isset($image['id']) || !$image['id']) {
                $validated = false;
                break;
            }
        }

        return $validated;
    }

    protected function validate_settings($settings)
    {
        $_tmp = array();
        $validated = true;
        foreach ($settings as $setting => $value) {
            switch ($setting) {
                case 'slides_qty':
                    if (!(int)$value) {
                        $validated = false;
                    }
                    break;
                case 'images':
                    if (!isset($value['big']) || !is_array($value['big']) || !isset($value['thumb']) || !is_array($value['thumb'])) {
                        $validated = false;
                        break;
                    }
                    $big = $value['big'];
                    if (!isset($big['width']) || !(int)$big['width'] || !isset($big['height']) || !(int)$big['height']) {
                        $validated = false;
                        break;
                    }
                    $thumb = $value['thumb'];
                    if (!isset($thumb['width']) || !(int)$thumb['width'] || !isset($thumb['height']) || !(int)$thumb['height']) {
                        $validated = false;
                        break;
                    }
                    $_tmp['images'] = $value;
                    break;
            }
            if (!$validated) {
                break;
            }
        }

        return $validated;
    }

    protected function validate_admin_form_nonce()
    {
        return wp_verify_nonce($_POST[$this->get_admin_form_nonce_field()] , $this->get_admin_form_nonce_id());
    }
}
