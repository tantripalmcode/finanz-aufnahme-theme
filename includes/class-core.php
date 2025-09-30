<?php
// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

class BUDI_CHILD_CORE
{
    private static $initiated = false;

    /**
     * init
     *
     * @return void
     */
    public static function init()
    {
        if (!self::$initiated) {
            self::init_hooks();
        }
    }

    /**
     * init_hooks
     *
     * @return void
     */
    private static function init_hooks()
    {
        self::$initiated = true;

        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_filter('use_widgets_block_editor', '__return_false');
        add_filter('unzip_file_use_ziparchive', '__return_false');
        add_filter('body_class', [__CLASS__, 'add_body_class_name']);
        add_filter('upload_mimes', [__CLASS__, 'allow_svg_upload']);
    }

    /**
     * add_body_class_name
     *
     * @param  mixed $classes
     * @return void
     */
    public static function add_body_class_name($classes)
    {
        global $post;

        $classes[] = _BUDI_CHILD_PREFIX;

        if ($post) {
            $classes[] = $post->post_type . "-" . $post->post_name;
        }

        return $classes;
    }

    public static function allow_svg_upload($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
}

// Call the class
BUDI_CHILD_CORE::init();
