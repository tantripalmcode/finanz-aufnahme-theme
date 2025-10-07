<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #container div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bundesweit.digital
 */

?>
</div> <!-- /#container aus header.php -->

<footer class="footer" id="footer">
	<div class="footer-inner px-0"> <!-- bestimmt max-width, boxed, full width -->
		<?php
		/*
			Footer Template part aus Ordner template-parts/ laden
			*/
		$footer_setting = get_theme_mod('footer_type');
		get_template_part('template-parts/footer', $footer_setting);
		?>
	</div>
	<div class="footer-legal">
		<div class="footer-legal-inner">
			<!-- legal row -->
			<?php if (do_shortcode(get_theme_mod('copyright_text'))) { ?>
				<?php get_template_part('template-parts/footer', 'legal'); ?>
			<?php } ?>

		</div>
	</div>
</footer>

<?php
/*
		Scroll to Top anzeigen wenn im Customizer aktiviert
	*/
$stt = get_theme_mod('scroll_to_top');
if ($stt == "1") { ?>
	<div class="scrolltotop_wrapper">
		<div class="scrolltotop" style="background: <?php echo get_theme_mod('scroll_to_top_bg_color', '#ff00ff'); ?>; width: <?php echo get_theme_mod('scroll_to_top_size', '60px'); ?>px; height: <?php echo get_theme_mod('scroll_to_top_size', '60px'); ?>px;">
			<div style="width: 33%;">
				<img src="<?php echo get_template_directory_uri(); ?>/images/chevron-up.png">
			</div>
		</div>
		<script>
			jQuery(document).ready(function() {
				jQuery(".scrolltotop").click(function() {
					jQuery("html, body").animate({
						scrollTop: 0
					}, "slow");
				});
			});
		</script>
	</div>
<?php } ?>

<script>
	// Smooth Scrolling
	jQuery(function() {
		jQuery('a.ss[href*="#"]:not([href="#"])').click(function() {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = jQuery(this.hash);
				target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					jQuery('html, body').animate({
						scrollTop: target.offset().top + <?php echo get_theme_mod('anchor_scrolling_y_offset', "0"); ?>
					}, 1800);
					return false;
				}
			}
		});
		jQuery('.ss a[href*="#"]:not([href="#"])').click(function() {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = jQuery(this.hash);
				target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					jQuery('html, body').animate({
						scrollTop: target.offset().top + <?php echo get_theme_mod('anchor_scrolling_y_offset', "0"); ?>
					}, 1800);
					return false;
				}
			}
		});
	});
</script>


<?php wp_footer(); ?>

<script>
	AOS.init();
</script>
</body>

</html>

<!-- ProvenExpert ProSeal Widget -->
<!-- <noscript>
	<a href="https://www.provenexpert.com/bundesweit-digital-gmbh/?utm_source=seals&utm_campaign=proseal&utm_medium=profile&utm_content=3b1700f3-8689-4e0c-aa2b-7d7a1485a08c" target="_blank" title="Customer reviews & experiences for bundesweit.digital GmbH" class="pe-pro-seal-more-infos" rel="nofollow">More info</a>
</noscript>
<script nowprocket id="proSeal">
	window.loadProSeal = function() {
		window.provenExpert.proSeal({
			widgetId: "3b1700f3-8689-4e0c-aa2b-7d7a1485a08c",
			language: "de-DE",
			usePageLanguage: false,
			bannerColor: "#0e3855",
			textColor: "#FFFFFF",
			showReviews: true,
			hideDate: true,
			hideName: false,
			hideOnMobile: false,
			bottom: "50px",
			stickyToSide: "right",
			googleStars: false,
			zIndex: "9999",
			displayReviewerLastName: false,
		})
	};

	let active = 0;
	window.addEventListener('scroll', function() {
		// Check if the #footer element exists before running the rest of the code
		const footer = document.querySelector('#footer');
		if (!footer) return;

		setTimeout(() => {
			const scrollPosition = window.scrollY;
			const windowHeight = window.innerHeight;
			const footerRect = footer.getBoundingClientRect();
			const footerPosition = footerRect.top + window.scrollY - footerRect.height + 200;

			if (scrollPosition >= (windowHeight - 400) && scrollPosition < footerPosition && active === 0) {
				document.querySelector('.pe-pro-seal').classList.add('budi-active');
				active = 1;
			} else if (scrollPosition >= footerPosition) {
				document.querySelector('.pe-pro-seal').classList.remove('budi-active');
				active = 0;
			} else if (scrollPosition <= (windowHeight - 400)) {
				document.querySelector('.pe-pro-seal').classList.remove('budi-active');
				active = 0;
			}
		}, 300);
	});
</script>
<script nowprocket src="https://s.provenexpert.net/seals/proseal-v2.js" async="true" onload='loadProSeal()'></script> -->
<!-- ProvenExpert ProSeal Widget -->