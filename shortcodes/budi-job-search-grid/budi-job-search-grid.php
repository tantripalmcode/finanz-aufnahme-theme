<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_JOB_SEARCH_GRID extends BUDI_SHORTCODE_BASE
{
    private $post_type;

    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->post_type = "job_listing";

        add_action('wp_ajax_budi_job_search_grid_filter', array($this, 'ajax_job_search_grid_filter'));
        add_action('wp_ajax_nopriv_budi_job_search_grid_filter', array($this, 'ajax_job_search_grid_filter'));
    }

    /**
     * get_name
     */
    protected function get_name()
    {
        return 'budi_job_search_grid';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Job Search Grid', _BUDI_TEXT_DOMAIN);
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists()
    {
        $google_maps_api_key = get_option('job_manager_google_maps_api_key');
        
        // Fallback API key sources
        if (!$google_maps_api_key) {
            $google_maps_api_key = get_option('budi_google_maps_api_key');
        }
        if (!$google_maps_api_key) {
            $google_maps_api_key = get_theme_mod('google_maps_api_key');
        }

        // Enqueue CSS & JS
        wp_enqueue_style($this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION);
        
        // Only load Google Maps if API key is available
        if ($google_maps_api_key) {
            wp_enqueue_script("google-maps", "https://maps.googleapis.com/maps/api/js?key={$google_maps_api_key}&sensor=false&libraries=places", array(), null, true);
        } else {
            // Log warning if no API key is found
            error_log('Warning: No Google Maps API key found for job search grid. Please set job_manager_google_maps_api_key, budi_google_maps_api_key, or google_maps_api_key option.');
        }
        
        wp_enqueue_script($this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.js", array(), _BUDI_VERSION, true);
    }

    /**
     * register_controls
     */
    public function register_controls()
    {
        $args = array(
            'name' => $this->widget_title,
            'base' => $this->widget_name,
            'category' => _BUDI_CATEGORY_WIDGET_NAME,
            'content_element' => true,
            "show_settings_on_create" => false,
            "is_container" => false,
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __('Posts Per Page', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'posts_per_page',
                    'admin_label' => true,
                    'std' => 4,
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Show Load More Button?', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'show_load_more',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'std' => 'yes',
                    'admin_label' => false,
                ),
                ...$this->get_design_options_controls(),
            )
        );

        vc_map($args);
    }

    /**
     * render_view
     */
    public function render_view($atts, $content = null)
    {
        global $wpdb;

        $atts = shortcode_atts([
            'posts_per_page'  => 4,
            'show_load_more'  => 'yes',
            'widget_class'    => '',
            'css'             => '',
        ], $atts);

        $posts_per_page = $atts['posts_per_page'];
        $show_load_more = $atts['show_load_more'];
        $widget_class   = sc_merge_css($atts['css'], $atts['widget_class']);

        ob_start();

        // Get search parameters
        $keyword    = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : '';
        $location   = isset($_GET['location']) ? sanitize_text_field($_GET['location']) : '';
        $radius     = isset($_GET['radius']) ? sanitize_text_field($_GET['radius']) : '';
        $lat        = isset($_GET['lat']) ? sanitize_text_field($_GET['lat']) : '';
        $lng        = isset($_GET['lng']) ? sanitize_text_field($_GET['lng']) : '';

        $paged = 1;

        $args = array(
            'post_type'      => $this->post_type,
            'post_status'    => array('publish', 'expired'),
            'posts_per_page' => $posts_per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            's'              => $keyword,
            'paged'          => $paged
        );

        // Check radius and location coordinates
        if ($radius && $lat && $lng) {
            // Validate coordinates
            $lat = floatval($lat);
            $lng = floatval($lng);
            $radius = floatval($radius);
            
            // Getting coordinate boundaries
            $lat_min = $lat - ($radius / 111);
            $lat_max = $lat + ($radius / 111);
            $lng_min = $lng - ($radius / (111 * cos(deg2rad($lat))));
            $lng_max = $lng + ($radius / (111 * cos(deg2rad($lat))));

            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'     => 'budi_location_lat',
                    'value'   => array($lat_min, $lat_max),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL(10,6)'
                ),
                array(
                    'key'     => 'budi_location_lng',
                    'value'   => array($lng_min, $lng_max),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL(10,6)'
                ),
            );
            
            // Debug logging
            error_log("Location search: lat=$lat, lng=$lng, radius=$radius");
            error_log("Search bounds: lat_min=$lat_min, lat_max=$lat_max, lng_min=$lng_min, lng_max=$lng_max");
        } elseif ($location && !$lat && !$lng) {
            // If location is provided but no coordinates, search by location text
            $args['meta_query'] = array(
                array(
                    'key'     => 'budi_location_city',
                    'value'   => $location,
                    'compare' => 'LIKE'
                )
            );
            error_log("Location text search: $location");
        }

        // Check Job Post type
        $matching_job_type = get_terms(array(
            'taxonomy' => 'job_listing_type',
            'name__like' => $keyword,
            'hide_empty' => false,
        ));

        if ($matching_job_type) {
            $job_type_ids = wp_list_pluck($matching_job_type, 'term_id');

            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'job_listing_type',
                    'terms' => $job_type_ids
                )
            );

            unset($args['s']);
        }

        $posts = new \WP_Query($args);
        $max_num_pages = $posts->max_num_pages;
?>

        <div class="budi-job-search-grid__wrapper <?php echo esc_attr($widget_class); ?>" id="<?php echo esc_attr($this->widget_id); ?>" data-paged="1" data-max_num_pages="<?php echo esc_attr($max_num_pages); ?>" data-post_type="<?php echo esc_attr($this->post_type); ?>" data-posts_per_page="<?php echo esc_attr($posts_per_page); ?>" data-keyword="<?php echo esc_attr($keyword); ?>" data-location="<?php echo esc_attr($location); ?>" data-radius="<?php echo esc_attr($radius); ?>" data-lat="<?php echo esc_attr($lat); ?>" data-lng="<?php echo esc_attr($lng); ?>" data-paged="<?php echo esc_attr($paged); ?>">

            <div class="container">


                <!-- Search Form -->
                <div class="budi-job-search-grid__form-wrapper">
                    <form action="" method="GET" class="budi-job-search-grid__form" id="budi-job-search-grid-form">
                        <div class="budi-job-search-grid__form-container">
                            <div class="budi-job-search-grid__form-item budi-job-category">
                                <input type="text" name="keyword" id="budi-job-category" class="form-control" placeholder="Jobkategorie" value="<?php echo esc_attr($keyword); ?>" />
                            </div>
                            <div class="budi-job-search-grid__form-item budi-job-location">
                                <input type="text" name="location" id="budi-job-location" class="form-control" placeholder="Ort" value="<?php echo esc_attr($location); ?>" />
                            </div>
                            <div class="budi-job-search-grid__form-item budi-job-radius">
                                <div class="budi-job-custom-dropdown">
                                    <input type="hidden" name="radius" id="budi-radius-value" value="<?php echo esc_attr($radius); ?>" />
                                    <div class="budi-job-dropdown-trigger" data-value="<?php echo esc_attr($radius); ?>">
                                        <span class="budi-job-dropdown-text"><?php echo $radius ? $radius . ' km' : 'Umkreis'; ?></span>
                                        <svg class="budi-job-dropdown-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3 4.5L6 7.5L9 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <div class="budi-job-dropdown-menu">
                                        <div class="budi-job-dropdown-item" data-value="">Umkreis</div>
                                        <?php
                                        $radius_arr = array(5, 10, 25, 50, 75, 100);
                                        foreach ($radius_arr as $radius_val) {
                                            $selected = $radius == $radius_val ? 'selected' : '';
                                            echo sprintf('<div class="budi-job-dropdown-item %s" data-value="%s">%s km</div>', $selected, $radius_val, $radius_val);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="budi-job-search-grid__form-item budi-job-submit">
                                <input type="hidden" name="lat" id="budi-lat" value="<?php echo esc_attr($lat); ?>" />
                                <input type="hidden" name="lng" id="budi-lng" value="<?php echo esc_attr($lng); ?>" />
                                <button type="submit" class="budi-search-btn">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.67798 1.18872C4.74321 0.618677 5.93894 0.403718 7.28247 0.557861C8.63152 0.75293 9.76772 1.3196 10.7083 2.26001L10.7122 2.26392C11.6514 3.18408 12.227 4.31098 12.4417 5.66235C12.6135 7.28143 12.29 8.66523 11.4924 9.84399L11.262 10.1848L11.552 10.4768L15.2698 14.2278L15.2708 14.2288C15.4338 14.3918 15.5002 14.5594 15.5002 14.7512C15.5002 14.9429 15.4339 15.1086 15.2717 15.2698L15.2698 15.2717C15.1086 15.4339 14.9429 15.5002 14.7512 15.5002C14.5594 15.5002 14.3918 15.4338 14.2288 15.2708L14.2278 15.2698L10.4768 11.552L10.1848 11.262L9.84399 11.4924C8.66429 12.2906 7.27919 12.6144 5.65845 12.4417C4.31149 12.2475 3.18749 11.6737 2.26782 10.7161L2.26001 10.7083C1.3196 9.76772 0.75293 8.63152 0.557861 7.28247C0.403941 5.94088 0.618568 4.74693 1.18677 3.68286C1.78547 2.61759 2.61335 1.78805 3.67798 1.18872ZM6.45923 2.00024C5.21002 2.02338 4.14453 2.46803 3.29712 3.33716C2.4339 4.18316 1.99202 5.24554 1.96899 6.49048V6.50903C1.99195 7.75375 2.43431 8.81542 3.29712 9.66138V9.66235C4.14453 10.5315 5.21001 10.9771 6.45923 11.0002H6.47778C7.72689 10.9772 8.79982 10.5319 9.66626 9.66724L9.66724 9.66626C10.5139 8.81783 10.946 7.75411 10.969 6.50903V6.49048C10.946 5.24546 10.514 4.18164 9.66724 3.33325L9.66626 3.33228C8.79986 2.46772 7.72678 2.02328 6.47778 2.00024H6.45923Z" fill="white" stroke="white" />
                                    </svg>

                                    Suchen
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Job Grid -->
                <div class="budi-job-search-grid__content">
                    <div class="budi-job-search-grid__grid <?php if (!$posts->have_posts()) echo 'bj-not-found'; ?>" id="budi-job-search-grid">
                        <?php
                        if ($posts->have_posts()) {
                            while ($posts->have_posts()) {
                                $posts->the_post();
                                $this->render_job_item();
                            }
                            wp_reset_postdata();
                        } else {
                            $this->render_not_found();
                        }
                        ?>
                    </div>

                    <?php if ($show_load_more === "yes" && $max_num_pages > 1) { ?>
                        <div class="budi-job-search-grid__load-more">
                            <button type="button" class="budi-load-more-btn" id="budi-load-more-btn">
                                Mehr anzeigen
                            </button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    <?php
        return ob_get_clean();
    }

    /**
     * Render Job Item
     */
    private function render_job_item()
    {
        $post_id          = get_the_ID();
        $job_title        = get_the_title($post_id);
        $permalink        = get_permalink($post_id);
        $job_type         = get_the_terms($post_id, 'job_listing_type');
        $job_category     = get_the_terms($post_id, 'job_listing_category');
        $location_city    = function_exists('get_field') ? get_field('budi_location_city', $post_id) : '';
        $icon             = function_exists('get_field') ? get_field('budi_icon', $post_id) : '';
    ?>

        <a href="<?php echo esc_url($permalink); ?>" class="budi-job-search-grid__item">
            <div class="budi-job-search-grid__item-icon">
                <?php if ($icon) echo wp_get_attachment_image($icon, 'full'); ?>
            </div>

            <div class="budi-job-search-grid__item-content">
                <h3 class="budi-job-search-grid__item-title"><?php echo esc_html($job_title); ?></h3>
                <p class="budi-job-search-grid__item-subtitle">
                    <?php 
                    $subtitle_parts = array();
                    
                    // Add job category if available
                    if ($job_category && !is_wp_error($job_category)) {
                        $subtitle_parts[] = $job_category[0]->name;
                    }
                    
                    // Add job type wrapped in parentheses if available
                    if ($job_type && !is_wp_error($job_type)) {
                        $subtitle_parts[] = '(' . $job_type[0]->name . ')';
                    }
                    
                    // Fallback if no category or type
                    if (empty($subtitle_parts)) {
                        $subtitle_parts[] = 'Ambulante Pflege (Voll- bzw. Teilzeit)';
                    }
                    
                    echo esc_html(implode(' ', $subtitle_parts));
                    ?>
                </p>
                <?php if ($location_city) { ?>
                    <div class="budi-job-search-grid__item-location">
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 6.5C6.55228 6.5 7 6.05228 7 5.5C7 4.94772 6.55228 4.5 6 4.5C5.44772 4.5 5 4.94772 5 5.5C5 6.05228 5.44772 6.5 6 6.5Z" fill="currentColor" />
                            <path d="M6 1C4.34315 1 3 2.34315 3 4C3 6.5 6 10 6 10C6 10 9 6.5 9 4C9 2.34315 7.65685 1 6 1ZM6 5.5C5.44772 5.5 5 5.05228 5 4.5C5 3.94772 5.44772 3.5 6 3.5C6.55228 3.5 7 3.94772 7 4.5C7 5.05228 6.55228 5.5 6 5.5Z" fill="currentColor" />
                        </svg>
                        <?php echo esc_html($location_city); ?>
                    </div>
                <?php } ?>
            </div>

            <div class="budi-job-search-grid__item-arrow">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.8137 1.00001C12.8137 0.723871 12.5899 0.500013 12.3137 0.500012L7.81371 0.500013C7.53757 0.500012 7.31371 0.72387 7.31371 1.00001C7.31371 1.27615 7.53757 1.50001 7.81371 1.50001L11.8137 1.50001L11.8137 5.50001C11.8137 5.77615 12.0376 6.00001 12.3137 6.00001C12.5899 6.00001 12.8137 5.77615 12.8137 5.50001L12.8137 1.00001ZM1.35355 12.6673L12.6673 1.35357L11.9602 0.646459L0.646447 11.9602L1.35355 12.6673Z" fill="#BD0926" />
                </svg>
            </div>
        </a>
    <?php
    }

    /**
     * Render Not Found
     */
    private function render_not_found()
    {
    ?>
        <div class="budi-job-search-grid__not-found">
            <p>Die von Ihnen gesuchte Stelle wurde leider nicht gefunden</p>
        </div>
<?php
    }

    /**
     * Ajax Job Filter
     */
    public function ajax_job_search_grid_filter()
    {
        global $wpdb;

        ob_start();

        $response['success'] = false;

        $post             = isset($_POST) ? wp_unslash($_POST) : [];
        $max_num_pages    = isset($post['max_num_pages']) ? $post['max_num_pages'] : '';
        $post_type        = isset($post['post_type']) ? $post['post_type'] : '';
        $posts_per_page   = isset($post['posts_per_page']) ? $post['posts_per_page'] : '';
        $keyword          = isset($post['keyword']) ? $post['keyword'] : '';
        $location         = isset($post['location']) ? $post['location'] : '';
        $radius           = isset($post['radius']) ? $post['radius'] : 5;
        $radius           = empty($radius) ? 5 : $radius;
        $lat              = isset($post['lat']) ? $post['lat'] : '';
        $lng              = isset($post['lng']) ? $post['lng'] : '';
        $paged            = isset($post['paged']) ? $post['paged'] : '';

        $args = array(
            'post_type'      => $post_type,
            'post_status'    => array('publish', 'expired'),
            'posts_per_page' => $posts_per_page,
            'orderby'        => 'date',
            'order'          => 'DESC',
            's'              => $keyword,
            'paged'          => $paged,
        );

        error_log("Radius: " . $radius);
        error_log("Lat: " . $lat);
        error_log("Lng: " . $lng);

        // Check radius and location coordinates
        if ($radius && $lat && $lng) {
            // Validate coordinates
            $lat = floatval($lat);
            $lng = floatval($lng);
            $radius = floatval($radius);
            
            // Getting coordinate boundaries
            $lat_min = $lat - ($radius / 111);
            $lat_max = $lat + ($radius / 111);
            $lng_min = $lng - ($radius / (111 * cos(deg2rad($lat))));
            $lng_max = $lng + ($radius / (111 * cos(deg2rad($lat))));

            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'     => 'budi_location_lat',
                    'value'   => array($lat_min, $lat_max),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL(10,6)'
                ),
                array(
                    'key'     => 'budi_location_lng',
                    'value'   => array($lng_min, $lng_max),
                    'compare' => 'BETWEEN',
                    'type' => 'DECIMAL(10,6)'
                ),
            );
            
            // Debug logging
            error_log("AJAX Location search: lat=$lat, lng=$lng, radius=$radius");
            error_log("AJAX Search bounds: lat_min=$lat_min, lat_max=$lat_max, lng_min=$lng_min, lng_max=$lng_max");
        } elseif ($location && !$lat && !$lng) {
            // If location is provided but no coordinates, search by location text
            $args['meta_query'] = array(
                array(
                    'key'     => 'budi_location_city',
                    'value'   => $location,
                    'compare' => 'LIKE'
                )
            );
            error_log("AJAX Location text search: $location");
        }

        $posts = new \WP_Query($args);
        $max_num_pages = $posts->max_num_pages;

        if ($posts->have_posts()) {
            while ($posts->have_posts()) {
                $posts->the_post();
                $this->render_job_item();
            }
            wp_reset_postdata();
            $response['success'] = true;
        } else {
            $this->render_not_found();
            $response['success'] = false;
        }

        $response['html'] = ob_get_clean();
        $response['max_num_pages'] = $max_num_pages;

        wp_send_json($response);
        die;
    }
}

new BUDI_JOB_SEARCH_GRID();
