<?php
/**
 * Featured Slider Metabox
 *
 * @category   MetaBox
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
 * FeaturedSlider Metabox class
 *
 * @category   MetaBox
 * @package    Flyingkrai
 * @subpackage FeaturedSlider
 * @author     Davi Alves <davi.alves@indexdigital.com.br>
 * @copyright  2013 Davi Alves
 * @license    http://www.gnu.org/copyleft/gpl.txt GNU General Public License
 * @link       null
 */
class MetaBox
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
        //- add metaboxes
        add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
        //- add post save hook
        add_action('save_post', array($this, 'savePost'));
    }

    /**
     * Return plugin meta name
     *
     * @return string
     */
    public static function getFieldId()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE . '-is_featured-checkbox';
    }

    /**
     * Return plugin nonce id
     *
     * @return string
     */
    public static function getNonceId()
    {
        return FeaturedSlider::PLUGIN_NAMESPACE .'_nonce';
    }

    /**
     * Get all featured posts
     *
     * @param int $limit how many posts
     *
     * @return array|string
     */
    public static function getAllFeatured($limit)
    {
        //@TODO check usage of setup_postdata() > https://codex.wordpress.org/Function_Reference/setup_postdata
        return get_posts(
            array(
                'meta_key' => self::getFieldId(),
                'posts_per_page' => $limit
            )
        );
    }

    /**
     * Create post metaboxes
     *
     * @return void
     */
    public function registerMetaBoxes()
    {
        add_meta_box(
            FeaturedSlider::PLUGIN_NAMESPACE . '-is_featured', // $id
            FeaturedSlider::DISPLAY_NAME, // $title
            array($this, 'showPostMetaBox'), // $callback
            'post', // $page
            'normal', // $context
            'high' // $priority
        );
    }

    /**
     * Display meta box form html
     *
     * @return void
     */
    public function showPostMetaBox()
    {
        global $post;

        $data = array(
            'isFeatured' => $this->isPostFeatured($post->ID),
            'nonceCode' => wp_create_nonce(basename(__FILE__)),
            'nonceId' => self::getNonceId(),
            'fieldId' => self::getFieldId(),
            'fieldLabel' => 'Exibir este post na Home?'
        );

        $this->mustache->render('backend/metabox', $data);
    }

    /**
     * Handle additional routines during save_post hook
     *
     * @param int $postId the post ID
     *
     * @return int|void
     */
    public function savePost($postId)
    {
        //- verify nonce
        if (!wp_verify_nonce($_POST[self::getNonceId()], basename(__FILE__))) {
            return $postId;
        }
        //- verify autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $postId;
        }
        //- check permissions
        if ('post' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $postId)) {
                return $postId;
            }
        } elseif (!current_user_can('edit_post', $postId)) {
            return $postId;
        }

        //- save meta data
        $fieldId = self::getFieldId();
        //- check for adding/updating or deleting
        if (isset($_POST[$fieldId]) && $_POST[$fieldId]) {
            update_post_meta($postId, $fieldId, true);
        } else {
            delete_post_meta($postId, $fieldId);
        }
    }

    /**
     * Check if post is featured
     *
     * @param int $postId the post ID
     *
     * @return boolean
     */
    public function isPostFeatured($postId)
    {
        return (get_post_meta($postId, self::getFieldId(), true));
    }
}

/**
 * Exposes the object function to global scope
 *
 * @param integer $limit quanty
 *
 * @return array|string
 */
function flying_all_featured($limit = 0)
{
    if (! (int) $limit) {
        $limit = FeaturedSlider::HOME_DISPLAY_LIMIT;
    }

    print MetaBox::getAllFeatured($limit);
}
