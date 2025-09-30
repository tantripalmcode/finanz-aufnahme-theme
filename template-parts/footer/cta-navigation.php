<?php defined('ABSPATH') || exit; ?>

<div class="d-flex justify-content-between flex-column flex-md-row budi-footer-cta-navigation__wrapper budi-footer-cta-navigation__wrapper-new">
    <div class="budi-footer-cta-navigation-col__left">
        <?php dynamic_sidebar("footer_2"); ?>
    </div>
    <div class="budi-footer-cta-navigation-col__right">
        <div class="budi-footer-cta__button-wrapper">
            <?php
            $footer_cta_text        = get_field('budi_footer_cta_text', 'option');
            $footer_cta_button_text = get_field('budi_footer_cta_button_text', 'option');
            $footer_cta_button_url  = get_field('budi_footer_cta_button_url', 'option');

            if ($footer_cta_text) {
                printf('<p class="budi-footer-cta__text mb-3 font-weight-medium">%s</p>', $footer_cta_text);
            }

            if ($footer_cta_button_text && $footer_cta_button_url) {
                printf('
            <div class="budi-button-container budi-footer-cta__button">
                <a href="%s" class="budi-button btn btn-primary">
                    <svg width="23" height="23" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg"> <g clip-path="url(#clip0_1622_324)"> <mask id="mask0_1622_324" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="23" height="23"> <path d="M0 1.90735e-06H23V23H0V1.90735e-06Z" fill="white"/> </mask> <g mask="url(#mask0_1622_324)"> <path d="M7.98714 11.2012C8.16279 11.3764 8.16279 11.6607 7.98714 11.8364C7.81199 12.0116 7.52764 12.0116 7.35195 11.8364C7.1763 11.6607 7.1763 11.3764 7.35195 11.2012C7.52764 11.0256 7.81199 11.0256 7.98714 11.2012Z" fill="white"/> <path d="M10.0523 6.59578L12.704 3.9436C13.9227 2.72487 15.566 1.9306 17.1611 1.4127C19.9292 0.514261 22.5509 0.449572 22.5509 0.449572C22.5509 0.449572 22.4862 3.07121 21.5877 5.8393C21.0698 7.43447 20.2756 9.07772 19.0568 10.2964L11.4813 17.8721L5.12842 11.5192L10.0523 6.59578Z" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M10.3693 16.7604L8.94033 19.1426L3.85791 14.0602L6.24012 12.6312" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M16.405 12.948L15.9284 17.2362L12.1168 21.0479L11.6401 17.7124" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M5.28711 11.3594L1.95166 10.8828L5.76328 7.07113L10.0515 6.59451" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M5.12828 17.8717L0.449219 22.5508" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M6.39896 19.1425L3.62549 21.916" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M3.85795 16.6005L1.08447 19.374" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M16.1977 9.97873C15.3204 10.856 13.8981 10.856 13.0213 9.97873C12.144 9.10185 12.144 7.67962 13.0213 6.8023C13.8981 5.92547 15.3204 5.92547 16.1977 6.8023C17.0746 7.67962 17.0746 9.10185 16.1977 9.97873Z" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M17.1611 1.41227L21.5877 5.83887" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> <path d="M8.94043 12.789L12.7521 16.6006" stroke="white" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/> </g> </g> <defs> <clipPath id="clip0_1622_324"> <rect width="23" height="23" fill="white"/> </clipPath> </defs> </svg>

                    <span>%s</span>
                </a>
            </div>
        ', $footer_cta_button_url, $footer_cta_button_text);
            }
            ?>
        </div>
    </div>
</div>