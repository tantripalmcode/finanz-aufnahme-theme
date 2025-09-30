<?php  
$hide_pagination = isset($args['hide_pagination']) ? $args['hide_pagination'] : false;
$paint_id        = 'paint0_linear_2004_143_' . uniqid();
?>
<div class="budi-swiper-arrow-new d-flex align-items-center justify-content-center px-2 px-md-0">
    <!-- <div class="swiper-button-prev swiper-arrow position-relative m-0">
        <svg width="52" height="20" viewBox="0 0 52 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_2_1398)">
            <path d="M0.47711 11.0357L7.63463 16.1709C8.10838 16.4991 8.85635 16.4797 9.3053 16.1277C9.74328 15.7842 9.73641 15.2489 9.28975 14.917L4.16855 11.2428L24.9505 10.9711C25.6032 10.9626 26.1272 10.5656 26.121 10.0844C26.1148 9.60315 25.5808 9.21998 24.9281 9.22851L4.14622 9.50022L9.17135 5.69332C9.63652 5.35288 9.6424 4.80108 9.18455 4.46091C8.72671 4.12069 7.97844 4.12091 7.51333 4.46135C7.50356 4.46851 7.49396 4.47574 7.48458 4.48313L0.461343 9.80375C0.00433959 10.15 0.0114226 10.7015 0.47711 11.0357Z" fill="#E0163C"/>
            </g>
            <line y1="-1.2" x2="48" y2="-1.2" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 51.0994 8.2334)" stroke="#E0163C" stroke-width="2.4"/>
            <defs>
            <clipPath id="clip0_2_1398">
            <rect width="26" height="19.1704" fill="white" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 26.244 19.658)"/>
            </clipPath>
            </defs>
        </svg>
    </div> -->

    <?php if( !$hide_pagination ) { ?>
        <div class="swiper-pagination position-relative"></div>
    <?php } ?>

    <!-- <div class="swiper-button-next swiper-arrow position-relative m-0">
        <svg width="67" height="25" viewBox="0 0 67 25" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M58.654 11.8789L51.563 6.69701C51.0935 6.36566 50.3454 6.37517 49.8919 6.71827C49.4496 7.05294 49.4496 7.58356 49.8919 7.91823L54.9655 11.6259H34.1818C33.5291 11.6258 33 12.0125 33 12.4894C33 12.9664 33.5291 13.3531 34.1818 13.3531H54.9655L49.8919 17.0607C49.4224 17.3921 49.4094 17.9388 49.8628 18.2819C50.3163 18.625 51.0645 18.6345 51.5339 18.3031C51.5438 18.2961 51.5535 18.2891 51.563 18.2819L58.6539 13.1C59.1153 12.7628 59.1153 12.2161 58.654 11.8789Z" fill="#E0163C"/>
            <line x1="8" y1="12.8" x2="56" y2="12.8" stroke="#E0163C" stroke-width="2.4"/>
            <rect class="progress-border" x="1" y="1" width="65" height="23" rx="11.5" stroke="url(#<?php echo $paint_id; ?>)" stroke-width="2"/>
            <defs>
                <linearGradient id="<?php echo $paint_id; ?>" x1="65" y1="8" x2="8" y2="4" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#E0163C"/>
                    <stop offset="1" stop-color="#630718"/>
                </linearGradient>
            </defs>
        </svg>
    </div> -->
</div>