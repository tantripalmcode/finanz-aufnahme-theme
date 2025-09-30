<div class="container">
    <div class="row align-items-center">
        <div class="col-6">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer-menu',
                    'menu_class'     => 'budi-footer-legal-menu d-flex justify-content-center justify-content-md-start mb-0 flex-column flex-md-row'
                )
            );
            ?>
        </div>

        <div class="col-6">
            <?php
            global $budi_social_media;

            if ($budi_social_media) {
                echo '<ul class="budi__socialmedia m-0 p-0 list-unstyled d-flex align-items-center justify-content-end">';
                foreach ($budi_social_media as $social_media) {
                    $social_media_value = get_theme_mod($social_media['option_name']);

                    if ($social_media_value) {
                        echo sprintf('<li><a href="%s" target="_blank" class="d-inline-flex align-items-center justify-content-center">%s</a></li>', esc_url($social_media_value), $social_media['embedded_icon']);
                    }
                }
                echo '</ul>';
            }
            ?>
        </div>
    </div>
</div>