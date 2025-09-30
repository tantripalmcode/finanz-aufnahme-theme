<?php
function register_textbausteine_cpt()
{
	/**
	 * Post Type: Textbausteine.
	 */

	$labels = [
		"name" => esc_html__("Textbausteine", "custom-post-type-ui"),
		"singular_name" => esc_html__("Textbaustein", "custom-post-type-ui"),
		"menu_name" => esc_html__("Textbausteine", "custom-post-type-ui"),
		"all_items" => esc_html__("Alle Textbausteine", "custom-post-type-ui"),
		"add_new" => esc_html__("Add new", "custom-post-type-ui"),
		"add_new_item" => esc_html__("Neue Textbaustein hinzufügen", "custom-post-type-ui"),
		"edit_item" => esc_html__("Textbaustein bearbeiten", "custom-post-type-ui"),
		"new_item" => esc_html__("Textbaustein hinzufügen", "custom-post-type-ui"),
		"view_item" => esc_html__("Textbaustein anzeigen", "custom-post-type-ui"),
		"view_items" => esc_html__("Textbausteine anzeigen", "custom-post-type-ui"),
		"search_items" => esc_html__("Textbausteine durchsuchen", "custom-post-type-ui"),
		"not_found" => esc_html__("No Textbausteine found", "custom-post-type-ui"),
		"not_found_in_trash" => esc_html__("No Textbausteine found in trash", "custom-post-type-ui"),
		"parent" => esc_html__("Übergeordnet Textbaustein:", "custom-post-type-ui"),
		"featured_image" => esc_html__("Featured image for this Textbaustein", "custom-post-type-ui"),
		"set_featured_image" => esc_html__("Set featured image for this Textbaustein", "custom-post-type-ui"),
		"remove_featured_image" => esc_html__("Remove featured image for this Textbaustein", "custom-post-type-ui"),
		"use_featured_image" => esc_html__("Use as featured image for this Textbaustein", "custom-post-type-ui"),
		"archives" => esc_html__("Textbaustein archives", "custom-post-type-ui"),
		"insert_into_item" => esc_html__("Insert into Textbaustein", "custom-post-type-ui"),
		"uploaded_to_this_item" => esc_html__("Upload to this Textbaustein", "custom-post-type-ui"),
		"filter_items_list" => esc_html__("Filter Textbausteine list", "custom-post-type-ui"),
		"items_list_navigation" => esc_html__("Textbausteine list navigation", "custom-post-type-ui"),
		"items_list" => esc_html__("Textbausteine list", "custom-post-type-ui"),
		"attributes" => esc_html__("Textbausteine attributes", "custom-post-type-ui"),
		"name_admin_bar" => esc_html__("Textbaustein", "custom-post-type-ui"),
		"item_published" => esc_html__("Textbaustein published", "custom-post-type-ui"),
		"item_published_privately" => esc_html__("Textbaustein published privately.", "custom-post-type-ui"),
		"item_reverted_to_draft" => esc_html__("Textbaustein reverted to draft.", "custom-post-type-ui"),
		"item_scheduled" => esc_html__("Textbaustein scheduled", "custom-post-type-ui"),
		"item_updated" => esc_html__("Textbaustein updated.", "custom-post-type-ui"),
		"parent_item_colon" => esc_html__("Übergeordnet Textbaustein:", "custom-post-type-ui"),
	];

	$args = [
		"label" => esc_html__("Textbausteine", "custom-post-type-ui"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => ["slug" => "textbausteine", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "editor"],
		"show_in_graphql" => false,
	];

	register_post_type("textbausteine", $args);
}

if ( did_action('init') ) {
	register_textbausteine_cpt();
} else {
	add_action('init', 'register_textbausteine_cpt');
}