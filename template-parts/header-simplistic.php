<?php
global $post;
$current_post_id       = $post ? $post->ID : null;
$disable_header_footer = get_field('budi_disable_header_footer', $current_post_id);

if ($disable_header_footer) return;

$site_title         = get_bloginfo('name');
$company_logo       = get_theme_mod('company_logo');
$company_logo_white = get_theme_mod('company_logo_white');
$cta1_text          = get_theme_mod('cta1_text');
$cta1_url           = get_theme_mod('cta1_url');
$cta1_target        = get_theme_mod('cta1_target');
$cta2_text          = get_theme_mod('cta2_text');
$cta2_url           = get_theme_mod('cta2_url');
$cta2_target        = get_theme_mod('cta2_target');
?>
<div id="primary-navigation-overlay"></div>
<header class="simplistic-header d-block w-100 position-fixed" id="simplistic-header">
    <div class="simplistic-header-inner w-100 position-relative">

        <!-- Contact Info -->
        <div class="budi-simplistic-contact-info d-flex d-md-none align-items-center justify-content-between">
            <?php echo do_shortcode('[company-phone-link]'); ?>
            <?php echo do_shortcode('[company-email-link]'); ?>
        </div>
        <div class="container position-relative">
            <!-- Header Content -->
            <div class="simplistic-header-container pt-3 pt-md-0">

                <div class="row align-items-center">
                    <div class="col-8 col-md-2 budi-simplistic-header-logo">
                        <!-- Company Logo -->
                        <?php if ($company_logo) { ?>
                            <a href="<?php echo esc_url(get_site_url()); ?>">
                                <img class="logo transition-all-03s" src="<?php echo esc_url($company_logo); ?>" alt="<?php echo esc_attr($site_title); ?>" data-company-logo-color="<?php echo esc_attr($company_logo); ?>" data-company-logo-white="<?php echo esc_attr($company_logo_white); ?>" />
                            </a>
                        <?php } ?>

                        <a href="#budi-main-content" class="sr-only sr-only-focusable budi-skip-to-main-content" tabindex="0">Zum Inhalt springen</a>
                    </div>

                    <div class="col-4 col-md-10">

                        <div class="budi-simplistic-header-cta-menu__wrapper d-flex align-items-center justify-content-end">
                            <div class="budi-simplistic-contact-info budi-simplistic-contact-info-desktop d-none d-md-flex align-items-center">
                                <?php echo do_shortcode('[company-phone-link]'); ?>
                                <?php echo do_shortcode('[company-email-link]'); ?>
                            </div>

                            <div class="simplistic-cta simplistic-cta-header budi-button-container d-xl-flex d-none justify-content-start">

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
                            <div class="simplistic-menu">
                                <div class="simplistic-menu-mobile-hamburger d-flex justify-content-end">
                                    <button class="budi-hamburger-menu-button position-relative d-flex flex-column p-0">
                                        <span class="line"></span>
                                        <span class="line"></span>
                                        <span class="line"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    $cta_datas = [
        'cta1_text'   => $cta1_text,
        'cta1_url'    => $cta1_url,
        'cta1_target' => $cta1_target,
        'cta2_text'   => $cta2_text,
        'cta2_url'    => $cta2_url,
        'cta2_target' => $cta2_target,
    ];
    get_template_part('template-parts/header/popup', 'menu', $cta_datas);
    ?>

</header>

<div id="budi-main-content" class="sr-only sr-only-focusable"></div>