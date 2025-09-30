<?php
/*
	Shortcode: [budi-posts]
	Autor: M.Ho / bundesweit.digital
	Version: 1.0

	Description
	Shortcode um Beiträge eines bestimmten Post-Types anzuzeigen

	Shortcodes: https://developer.wordpress.org/reference/functions/add_shortcode/

	vc_map: 	https://kb.wpbakery.com/docs/inner-api/vc_map/

	Bilder: 	$image = wp_get_attachment_image_src( $atts["image"], 'full' );
				<img class="img-responsive" src="<?php echo $image[0]; ?>">

*/
if (! defined('ABSPATH')) {
	die('-1');
}

remove_shortcode('budi-posts');

function sc_budi_posts($atts, $content = null)
{
	// Attribute einlesen, auf default setzen falls nicht angegeben
	$atts = shortcode_atts([
		'post_type' => '',
		'design' => 'design-1',
		'taxonomie_filter_headline' => '',
		'taxonomie_filter' => 'null',
		'posts_per_page' => '4',
		'show_more_text' => 'Mehr anzeigen',
		'category' => '',
		'css' => '',
		'extra_class' => '',
	], $atts);

	$element_id = "posts-" . uniqid();

	budi_add_style('budi-posts', this_dir_url(__FILE__) . 'assets/posts.css');
	budi_add_style('budi-posts-design', this_dir_url(__FILE__) . '/' . $atts["design"] . "/" . $atts["design"] . '.css');

	budi_add_script('budi-post', this_dir_url(__FILE__) . 'assets/script.js');

	$css_class = sc_merge_css($atts['css'], $atts['extra_class']);

	// WP_Query args
	$query_args = array(
		'post_type' => explode(",", $atts["post_type"]),
		'nopaging' => true,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	// Filter by category if set
	if (!empty($atts['category'])) {
		$query_args['cat'] = $atts['category'];
	}

	$posts = new WP_Query($query_args);

	if ($atts["taxonomie_filter"] != "null") {

		$taxonomy = $atts["taxonomie_filter"];

		// If a category is selected, filter taxonomy terms to only those used by posts in that category
		if (!empty($atts['category'])) {
			// Get all post IDs in the selected category
			$cat_post_ids = get_posts(array(
				'fields' => 'ids',
				'post_type' => explode(",", $atts["post_type"]),
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'cat' => $atts['category'],
			));

			if (!empty($cat_post_ids)) {
				// Get terms from the selected taxonomy that are assigned to these posts
				$taxonomy_filters = get_terms(array(
					'taxonomy' => $taxonomy,
					'hide_empty' => true,
					'object_ids' => $cat_post_ids,
				));
			} else {
				$taxonomy_filters = array();
			}
		} else {
			// No category selected, show all terms as before
			$taxonomy_filters = get_terms(array(
				'taxonomy' => $taxonomy,
				'hide_empty' => true,
			));
		}
	}


	ob_start();
	// echo $atts["taxonomie_filter"] . " ";
	// print_r($posts);
?>

	<section id="<?php echo $element_id; ?>" class="budi-posts <?php echo $css_class; ?>">
		<div class="post-filter">
			<?php echo ($atts["taxonomie_filter_headline"] != "" ? '<p><b>' . $atts["taxonomie_filter_headline"] . '</b></p>' : '') ?>
			<div class="mobile-filter-toggle">Filter <span class="toggle-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
						<path d="m6 9 6 6 6-6" />
					</svg></span></div>
			<div class="filter-options">
				<?php if (!empty($taxonomy_filters)) { ?>
					<?php foreach ($taxonomy_filters as $term) { ?>
						<div>
							<label class="control control-checkbox">
								<input type="checkbox" class="taxonomy-filter-checkbox" data-taxonomy="<?php echo $taxonomy ?>" value="<?php echo $term->term_id ?>">
								<h3> <?php echo $term->name ?></h3>
								<div class="control_indicator"></div>
							</label>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>

		<div class="post-list">
			<?php include sprintf("%s/%s.php", $atts["design"], $atts["design"]) ?>
			<div>

				<?php if ($atts["posts_per_page"] > 0) { ?>
					<button class="budi-posts-show-more"><?php echo $atts["show_more_text"] ?></button>
				<?php } ?>

				<script>
					//jQuery(document).ready(function(){
					jQuery("#<?php echo $element_id; ?> .budi-posts-show-more").click(function() {
						jQuery("#<?php echo $element_id; ?> .post-card.hidden").removeClass("hidden")
						jQuery("#<?php echo $element_id; ?> .budi-post.hidden").removeClass("hidden")
						jQuery(this).hide()
					})

					// Mobile filter dropdown toggle
					jQuery("#<?php echo $element_id; ?> .mobile-filter-toggle").click(function() {
						jQuery(this).toggleClass("filter-open");
						jQuery("#<?php echo $element_id; ?> .filter-options").toggleClass("open");
					});

					// Close filter dropdown when clicking outside
					jQuery(document).on('click', function(event) {
						var filterOptions = jQuery("#<?php echo $element_id; ?> .filter-options");
						var filterToggle = jQuery("#<?php echo $element_id; ?> .mobile-filter-toggle");

						if (filterOptions.hasClass("open") &&
							!filterOptions.is(event.target) &&
							filterOptions.has(event.target).length === 0 &&
							!filterToggle.is(event.target) &&
							filterToggle.has(event.target).length === 0) {

							filterOptions.removeClass("open");
							filterToggle.removeClass("filter-open");
						}
					});
					//})
				</script>

				<style>
					.budi-posts .mobile-filter-toggle {
						display: none;
					}

					@media screen and (max-width: 992px) {
						.budi-posts .post-filter {
							padding-bottom: 0;
						}

						.budi-posts .mobile-filter-toggle {
							display: block;
							padding: 10px;
							background-color: transparent;
							border: 1px solid #ddd;
							border-radius: 10px;
							cursor: pointer;
							margin-bottom: 10px;
							font-weight: bold;
							position: relative;
						}

						.budi-posts .toggle-icon {
							float: right;
							transition: transform 0.3s ease-in-out;
						}

						.budi-posts .mobile-filter-toggle.filter-open .toggle-icon {
							transform: rotate(180deg);
						}

						.budi-posts .filter-options {
							width: 100%;
							opacity: 0;
							visibility: hidden;
							border: 1px solid #ddd;
							border-radius: 10px;
							padding: 0;
							margin-bottom: 15px;
							background-color: #fff;
							max-height: 0;
							overflow-y: auto;
							transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
						}

						.budi-posts .filter-options.open {
							display: flex;
							flex-direction: column;
							gap: 1px;
							max-height: 250px;
							opacity: 1;
							visibility: visible;
						}

						.budi-posts .post-filter .filter-options>div {
							display: block;
						}

						.budi-posts .post-filter .filter-options h3 {
							margin-bottom: 0;
							border-radius: 0;
							border: 0;
						}

						.budi-posts .control {
							margin-right: 0;
						}

						.budi-posts .control input:checked~h3 {
							background-color: rgba(224, 22, 60, 0.1)
						}
					}
				</style>
			</div>
		</div>
	</section>

<?php
	// HTML Output in Variable packen und zurück geben
	$output = ob_get_contents();
	ob_end_clean();

	wp_reset_postdata();

	return $output;
}

add_shortcode('budi-posts', 'sc_budi_posts');



add_action('vc_before_init', function () {
	if (!function_exists('vc_map')) {
		return;
	}

	vc_map(array(
		"name" => "Posts anzeigen",
		"description" => "Zeigt Beiträge eines Post-Types an",
		"base" => "budi-posts",
		"class" => "",
		"icon" => this_dir_url(__FILE__) . 'posts.svg',
		"category" => "bundesweit.digital",
		"content_element" => true,
		"holder" => "div",
		"params" => array_merge(
			array(
				// Optionsfelder / Datenfelder die benötigt werden
				array(
					"type" => "posttypes",
					"class" => "",
					"heading" => "Post Type",
					"param_name" => "post_type",
					"value" => "",
					"description" => "",
					"admin_label" => true,
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => "Design",
					"param_name" => "design",
					"value" => array("Standard 2-spaltig mit Bild" => "design-1", "Überschrift + Text mit Button" => "design-2"),
					"std" => "design-1",
					"description" => "",
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => "Kategorie",
					"param_name" => "category",
					"value" => (new BUDI_CHILD_TAXONOMY_POST_TYPE())->get_taxonomy_autocomplete('category', true),
					"description" => "Wählen Sie eine Kategorie aus.",
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => "Ajax Taxonomie Filter",
					"param_name" => "taxonomie_filter",
					"value" => array_merge(["Kein Filter" => "null"], get_taxonomies()),
					"std" => 'null',
					"description" => "",
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => "Text vor Filtern",
					"param_name" => "taxonomie_filter_headline",
					"value" => '',
					"description" => "",
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => "Anzahl Posts bevor Mehr anzeigen Button erscheint",
					"param_name" => "posts_per_page",
					"value" => '4',
					"description" => "Geben Sie Werte <= 0 ein um den Button nicht zu verwenden.",
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => "Mehr anzeigen Button Text",
					"param_name" => "show_more_text",
					"value" => 'Mehr anzeigen',
					"description" => "",
				),
				array(
					'type' => 'css_editor',
					'heading' => 'CSS',
					'param_name' => 'css',
					'group' => 'Design Options',
				),
			)
		),
	));
});