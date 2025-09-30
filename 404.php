<?php get_header(); ?>

<section class="outerWrap element-textblock columns-1" id="element-id-1" style="padding-top:120px; padding-bottom:120px;">
    <div class="innerWrap clearfix">

        <div class="intro budi-404 text-center">

            <h1 class="budi-404__heading mb-4">404 - Seite nicht gefunden</h1>
            <p class="budi-404__desc mb-5">Die von Ihnen gesuchte Seite existiert leider nicht.</p>

            <div class="budi-button-container d-flex flex-wrap justify-content-center" style="gap :14px;">
                <a href="<?php echo esc_url(get_site_url()); ?>" class="btn btn-primary d-inline-flex flex-row " role="button" target="" rel="" style="gap :4px;">
                    Zur Startseite 
                </a>
            </div>
        </div>

    </div>
</section>

<?php get_footer(); ?>