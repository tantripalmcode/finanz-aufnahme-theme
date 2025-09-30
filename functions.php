<?php
define('_BUDI_VERSION', time());
define('_BUDI_PATH', get_stylesheet_directory());
define('_BUDI_INCLUDE_PATH', _BUDI_PATH . '/includes/');
define('_BUDI_URL', get_stylesheet_directory_uri());
define('_BUDI_ASSETS_URL', get_stylesheet_directory_uri() . '/assets/');
define('_BUDI_CATEGORY_WIDGET_NAME', 'bundesweit.digital custom');
define('_BUDI_TEXT_DOMAIN', 'budigital');
define('_BUDI_CHILD_SHORTCODES_DIR', _BUDI_PATH . '/shortcodes');
define('_BUDI_CHILD_WIDGETS_DIR', _BUDI_PATH . '/widgets');
define('_BUDI_CHILD_PREFIX', 'budigital-child');
define('_BUDI_CHILD_SLIDER_SPEED', 1500);

/**
 * Include functions
 */
require_once _BUDI_INCLUDE_PATH . 'class-core.php';
require_once _BUDI_INCLUDE_PATH . 'class-enqueue.php';
require_once _BUDI_INCLUDE_PATH . 'class-job-manager.php';
require_once _BUDI_INCLUDE_PATH . 'class-taxonomy-post_type.php';
require_once _BUDI_INCLUDE_PATH . 'class-acf.php';
require_once _BUDI_INCLUDE_PATH . 'class-cf7.php';
require_once _BUDI_INCLUDE_PATH . 'class-redirect.php';
require_once _BUDI_INCLUDE_PATH . 'class-customizer.php';
require_once _BUDI_INCLUDE_PATH . 'widgets.php';
require_once _BUDI_INCLUDE_PATH . 'social-media.php';
require_once _BUDI_INCLUDE_PATH . 'vc-functions.php';
require_once _BUDI_INCLUDE_PATH . 'video-functions.php';
require_once _BUDI_INCLUDE_PATH . 'helper-functions.php';
require_once _BUDI_INCLUDE_PATH . 'budi-custom-widget.php';
require_once _BUDI_INCLUDE_PATH . 'monday-loghub.php';

/**
 * Custom functions
 */
function budi_add_custom_spacing_css()
{
?>
    <style>
        :root {
            --section-spacing-large: <?php echo get_theme_mod('section_spacing_large', '120px'); ?>;
            --section-spacing-medium: <?php echo get_theme_mod('section_spacing_medium', '80px'); ?>;
            --section-spacing-small: <?php echo get_theme_mod('section_spacing_small', '60px'); ?>;

            --h1-spacing: <?php echo get_theme_mod('h1_spacing', '80px'); ?>;
            --h2-spacing: <?php echo get_theme_mod('h2_spacing', '60px'); ?>;
            --h3-spacing: <?php echo get_theme_mod('h3_spacing', '30px'); ?>;
            --h4-spacing: <?php echo get_theme_mod('h4_spacing', '20px'); ?>;
            --h5-spacing: <?php echo get_theme_mod('h5_spacing', '20px'); ?>;
            --h6-spacing: <?php echo get_theme_mod('h6_spacing', '20px'); ?>;
            --sub-headline-spacing: <?php echo get_theme_mod('sub_headline_spacing', '18px'); ?>;
        }
    </style>
<?php
}
add_action('wp_head', 'budi_add_custom_spacing_css');


/**
 * Add tags to media coverage
 */
function add_tags_to_my_custom_post_type()
{
    register_taxonomy_for_object_type('post_tag', 'media_coverage');
}
add_action('init', 'add_tags_to_my_custom_post_type');


add_filter('wpcf7_validate_textarea*', 'limit_links_in_textarea', 10, 2);
add_filter('wpcf7_validate_textarea', 'limit_links_in_textarea', 10, 2);

function limit_links_in_textarea($result, $tag) {
    $tag_name = $tag['name'];

    // Passe den Feldnamen an deinen CF7-Feldnamen an (z. B. 'your-message')
    if ($tag_name === 'nachricht') {
        $value = isset($_POST[$tag_name]) ? $_POST[$tag_name] : '';
        preg_match_all('/https?:\/\/[^\s]+/i', $value, $matches);
        $link_count = count($matches[0]);

        if ($link_count > 1) {
            $result->invalidate($tag, 'Bitte füge maximal 1 Link ein.');
        }
    }

    return $result;
}