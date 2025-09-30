<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_JOB_MANAGER {
    private static $initiated = false;
    
    /**
     * init
     *
     * @return void
     */
    public static function init() {
        if( !self::$initiated ) {
            self::init_hooks();
        }
    }

    /**
     * init_hooks
     *
     * @return void
     */
    private static function init_hooks() {
        self::$initiated = true;

        // Filters
        add_filter( 'job_manager_job_listing_data_fields', array( __CLASS__,  'budi_remove_not_used_field' ) );
        add_filter( 'manage_edit-job_listing_columns', array( __CLASS__,  'budi_remove_admin_columns' ) );
        add_filter( 'enter_title_here', array( __CLASS__, 'budi_change_title_job_manager_field' ), 1, 2 );
        add_filter( 'wpjm_get_job_listing_structured_data', array( __CLASS__, 'change_data_structure' ), 10, 2 );

        // Actions
        add_action( 'admin_init', array( __CLASS__, 'budi_remove_post_type_support' ) );
        add_action( 'admin_init', array( __CLASS__, 'my_custom_function_for_specific_post_type' ) );
        add_action( 'save_post_job_listing', array( __CLASS__, 'save_post_job_manager' ) );
    }
    
    /**
     * budi_remove_not_used_field
     */
    public static function budi_remove_not_used_field( $fields ) {
        unset( $fields['_job_expires'] );
        unset( $fields['_company_name'] );
        unset( $fields['_company_website'] );
        unset( $fields['_company_tagline'] );
        unset( $fields['_company_twitter'] );
        unset( $fields['_company_video'] );
        unset( $fields['_company_video'] );
        unset( $fields['_company_logo'] );
        unset( $fields['_featured'] );
        unset( $fields['_filled'] );
        unset( $fields['_application'] );
        unset( $fields['_job_location'] );

        return $fields;
    }
    
    /**
     * budi_remove_admin_columns
     */
    public static function budi_remove_admin_columns( $columns ) {
        unset( $columns['job_expires'] );
        unset( $columns['job_status'] );
        unset( $columns['promoted_jobs'] );
        unset( $columns['job_location'] );

        return $columns;
    }
    
    /**
     * budi_change_title_job_manager_field
     */
    public static function budi_change_title_job_manager_field( $text, $post ) {
        if ( 'job_listing' === $post->post_type ) {
            return esc_html__( 'Job Titel', _BUDI_TEXT_DOMAIN );
        }

        return $text;
    }
    
    /**
     * Remove post type support
     */
    public static function budi_remove_post_type_support() {
        remove_post_type_support( 'job_listing', 'thumbnail' );
        // remove_post_type_support( 'job_listing', 'editor' );
    }

    /**
     * Perform custom actions for specific post type 'job_listing' in the admin area.
     * - Adds custom post meta for a single job listing when editing.
     * - Adds custom post meta for all job listings with missing location data when viewing the job listing admin page.
     */
    public static function my_custom_function_for_specific_post_type() {
        global $pagenow;
    
        // Check if we are on an admin page
        if (is_admin()) {

            if( $pagenow === 'post.php' && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) === "job_listing" ){
                $post_id = $_GET['post'];

                self::add_custom_post_meta_job_listing( $post_id );
            }


            if ( $pagenow === 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'job_listing' ) {

                $args = array(
                    'post_type'      => 'job_listing',
                    'post_status'    => array('any'),
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key' => 'budi_location_city',
                            'value' => '',
                            'compare' => '=',
                        ),
                        array(
                            'key' => 'budi_location_lat',
                            'value' => '',
                            'compare' => '=',
                        ),
                        array(
                            'key' => 'budi_location_lng',
                            'value' => '',
                            'compare' => '=',
                        ),
                    )
                );

                $job_listings = get_posts( $args );

                if( $job_listings ){
                    foreach( $job_listings as $job_listing ) {
                        $post_id = $job_listing->ID;

                        self::add_custom_post_meta_job_listing( $post_id );
                    }
                }
            }
        }
    }

    /**
     * Adds custom post meta for a job listing with location data.
     */
    public static function add_custom_post_meta_job_listing( $post_id ) {
        if ( $post_id ) {
            $location   = get_field( 'budi_location', $post_id );
            $lat        = isset( $location['lat'] ) ? $location['lat']: '';
            $lng        = isset( $location['lng'] ) ? $location['lng']: '';
            $city       = isset( $location['city'] ) ? $location['city'] : '';
            $state      = isset( $location['state'] ) ? $location['state'] : '';
            $post_code  = isset( $location['post_code'] ) ? $location['post_code'] : '';
            $country    = isset( $location['country'] ) ? $location['country'] : '';

            update_post_meta( $post_id, 'budi_location_lat', $lat );
            update_post_meta( $post_id, 'budi_location_lng', $lng );
            update_post_meta( $post_id, 'budi_location_city', $city );
            update_post_meta( $post_id, 'budi_location_state', $state );
            update_post_meta( $post_id, 'budi_location_post_code', $post_code );
            update_post_meta( $post_id, 'budi_location_country', $country );
        }
    }

    
    /**
     * Calculate the Haversine distance between two geographical coordinates
     */
    public static function haversine_distance($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371; // earth radius in km

        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);
    
        $a = sin($dlat / 2) * sin($dlat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon / 2) * sin($dlon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earth_radius * $c;
    
        return $distance;
    
    }

    /**
     * Save Job Listing
     */
    public static function save_post_job_manager( $post_id ) {
        // Check if this is a revision
        if (wp_is_post_revision($post_id)) {
            return;
        }

        // Check if this is an autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // Check user permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;


        $json_location = json_decode( wp_unslash($_POST['acf']['field_65c08e65c1acc']), true );
        if( $json_location && isset( $json_location['address'] ) ){
            update_post_meta( $post_id, '_job_location', $json_location['address'] );
        }
    }

    /**
     * Change data structure
     */
    public static function change_data_structure( $data, $post ) {
        $data['description'] = wp_strip_all_tags( wpjm_get_the_job_description( $post ) );

        return $data;
    }
}

// Call the class
BUDI_JOB_MANAGER::init();