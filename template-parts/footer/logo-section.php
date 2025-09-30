<?php
defined('ABSPATH') || exit;
?>

<div class="row">
    <?php
    $badges = get_field('budi_footer_badges', 'option');
    if ($badges):
    ?>
        <div class="col-md-5">
            <h4>Wir sind</h4>

            <div class="budi-footer__logos budi-footer__logos-badge d-flex align-items-center justify-content-between">
                <?php
                foreach ($badges as $badge) {
                    echo sprintf('<figure class="budi-footer__logos-item">%s</figure>', wp_get_attachment_image($badge, 'large', false));
                }
                ?>
            </div>
        </div>

    <?php endif; ?>


    <?php
    $partners = get_field('budi_footer_kooperationspartner', 'option');
    if ($partners):
    ?>
        <div class="col-md-6 offset-md-1">
            <div class="budi-footer__logos-partner-container">
                <h4>Kooperationspartner</h4>

                <div class="budi-footer__logos budi-footer__logos-partner d-flex flex-wrap flex-md-nowrap align-items-center">
                    <?php
                    foreach ($partners as $partner) {
                        echo sprintf('<figure class="budi-footer__logos-item">%s</figure>', wp_get_attachment_image($partner, 'large', false));
                    }
                    ?>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>