<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BUDI_CHILD_TAXONOMY_POST_TYPE
{    
    /**
     * get_taxonomy_list
     *
     * @param  mixed $taxonomy
     * @param  mixed $hide_empty
     * @return array
     */
    public function get_taxonomy_autocomplete( $taxonomy, $hide_empty = false ) {
        $results = array();

        $terms = $terms = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => $hide_empty,
        ) );

        if ( $terms ) {
            foreach ( $terms as $term ) {
                array_push( $results, ["value" => $term->term_id, "label" => $term->name] );
            }
        }

        return $results;
    }

}