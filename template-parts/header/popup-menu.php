<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

$cta1_text   = $args['cta1_text'];
$cta1_url    = $args['cta1_url'];
$cta1_target = $args['cta1_target'];
$cta2_text   = $args['cta2_text'];
$cta2_url    = $args['cta2_url'];
$cta2_target = $args['cta2_target'];
?>
<div class="budi-simplistic-popup-menu__wrapper position-fixed w-100">

    <button class="budi-simplistic-popup-menu__close-button position-absolute" type="button">
        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </button>

    <div class="container position-relative">
        <div class="budi-simplistic-popup-menu__inner">
            <?php
            wp_nav_menu(
                array(
                    'menu'         => 'Main Menu',
                    'menu_class'   => 'budi-simplistic-popup-menu list-unstyled m-0 flex-wrap flex-lg-nowrap',
                    'container_id' => 'main-menu',
                )
            );
            ?>
        </div>

        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="simplistic-cta budi-button-container d-flex flex-column flex-md-row justify-content-start align-items-start align-items-md-center">
                    <?php if ($cta1_text): ?>

                        <?php
                        $link_url    = $cta1_url;
                        $link_title  = $cta1_text;
                        $link_target = $cta1_target;
                        ?>

                        <a class="simplistic-outline-button btn btn-primary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            <span class="budi-button-text"><?php echo ($link_title); ?></span>
                        </a>

                    <?php endif; ?>

                    <?php if ($cta2_text): ?>

                        <?php
                        $link_url    = $cta2_url;
                        $link_title  = $cta2_text;
                        $link_target = $cta2_target;
                        ?>

                        <a class="simplistic-outline-button btn btn-secondary" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                            <span class="budi-button-text"><?php echo ($link_title); ?></span>
                        </a>

                    <?php endif; ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="budi-simplistic-popup-social-media">
                    <?php
                    global $budi_social_media;

                    if ($budi_social_media) {
                        echo '<ul class="budi__socialmedia m-0 p-0 list-unstyled d-flex align-items-center justify-content-start justify-content-md-end">';
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
    </div>
</div>