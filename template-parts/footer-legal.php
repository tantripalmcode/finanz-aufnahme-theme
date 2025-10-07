<div class="container">
    <div class="row align-items-center flex-column-reverse flex-lg-row">
        <div class="col-lg-6">
            <div class="budi-footer-legal text-center text-lg-left">
                <?php echo do_shortcode(get_theme_mod('copyright_text')); ?>
            </div>
        </div>

        <div class="col-lg-6 mb-4 mb-lg-0">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer-menu',
                    'menu_class'     => 'budi-footer-legal-menu d-flex justify-content-center justify-content-lg-end mb-0 flex-md-row'
                )
            );
            ?>
        </div>
    </div>
</div>