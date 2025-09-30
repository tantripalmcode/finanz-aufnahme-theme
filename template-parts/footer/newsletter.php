<?php
defined('ABSPATH') || exit;

$footer_newsletter_title = get_field('budi_footer_newsletter_title', 'option');
?>
<section id="newsletter" class="budi-footer__newsletter position-relative">
    <div class="section-spacing-small"></div>
    <div class="container position-relative">
        <h4 class="budi-footer__newsletter-title budi-headline__title text-center"><?php echo nl2br($footer_newsletter_title); ?></h4>

        <div class="budi-footer__newsletter-form">

            <!-- Begin Brevo Form -->
            <link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">

            <!-- START - We recommend to place the below code where you want the form in your website html  -->
            <div class="sib-form">
                <div id="sib-form-container" class="sib-form-container">
                    <div id="error-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; color:#661d1d; border-radius:3px; border-color:#ff4949;max-width:540px;">
                        <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                            <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z" />
                            </svg>
                            <span class="sib-form-message-panel__inner-text">
                                Deine Anmeldung konnte nicht gespeichert werden. Bitte versuche es erneut.
                            </span>
                        </div>
                    </div>
                    <div></div>
                    <div id="success-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; color:#085229; background-color:#e7faf0; border-radius:3px; border-color:#13ce66;max-width:540px;">
                        <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
                            <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
                                <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z" />
                            </svg>
                            <span class="sib-form-message-panel__inner-text">
                                Deine Anmeldung war erfolgreich.
                            </span>
                        </div>
                    </div>
                    <div></div>
                    <div id="sib-container" class="sib-container--large sib-container--vertical">
                        <form id="sib-form" method="POST" action="https://f3b4bf43.sibforms.com/serve/MUIFAOwRLDcPUdYSjr5TPQsHbsCAdUsjAHK-wXhuat3j9u0bzRpidJI_Xxp0oEdqquoAuImAJbudELs3ERo4NrSzIDsBTWTHTVVx1EunguVPPXLy_tIzvor32tHNAmVZhjSI4y6mC8XoJQK-aZa6dkuTsgmvWdcAQgAaTrXNXJTmzExa1juW_2OF40xnLEbe7UonK0bZ_hO9eJg9" data-type="subscription">

                            <div class="row flex-column flex-lg-row">
                                <div class="col">

                                    <div class="budi-newsletter-form__inner">

                                        <div class="budi-footer__newsletter-input-fields w-100 d-flex flex-column flex-md-row align-items-center">

                                            <div class="sib-input sib-form-block w-100">
                                                <div class="form__entry entry_block">
                                                    <div class="form__label-row ">
                                                        <div class="entry__field">
                                                            <input class="input " maxlength="200" type="text" id="VORNAME" name="VORNAME" autocomplete="off" placeholder="Deine Name*" data-required="true" required />
                                                        </div>
                                                    </div>

                                                    <label class="entry__error entry__error--primary"></label>
                                                </div>
                                            </div>
                                            <div class="sib-input sib-form-block w-100">
                                                <div class="form__entry entry_block">
                                                    <div class="form__label-row ">
                                                        <div class="entry__field">
                                                            <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="Deine E-Mail-Adresse*" data-required="true" required />
                                                        </div>
                                                    </div>
                                                    <label class="entry__error entry__error--primary"></label>
                                                </div>
                                            </div>
                                            <div class="sib-input sib-form-block w-100">
                                                <div class="form__entry entry_block">
                                                    <div class="form__label-row ">
                                                        <div class="entry__field">
                                                            <input class="input " maxlength="200" type="text" id="UNTERNEHMEN" name="UNTERNEHMEN" autocomplete="off" placeholder="Unternehmen" />
                                                        </div>
                                                    </div>
                                                    <label class="entry__error entry__error--primary"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="sib-optin sib-form-block" data-required="true">
                                            <div class="form__entry entry_mcq">
                                                <div class="form__label-row ">
                                                    <div class="entry__choice">
                                                        <label>
                                                            <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN" required />
                                                            <span class="checkbox checkbox_tick_positive"></span>
                                                            <span class="sib-optin__text">
                                                                <p>Ich möchte den Newsletter erhalten und akzeptiere die Datenschutzerklärung.</p>
                                                                <span data-required="*" style="display: inline;" class="entry__label entry__label_optin"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <label class="entry__error entry__error--primary"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-auto">
                                    <div class="sib-form-block sib-form-block__button-wrapper text-center text-lg-left">
                                        <button class="sib-form-block__button sib-form-block__button-with-loader" form="sib-form" type="submit">
                                            <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                                                <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z" />
                                            </svg>
                                            <span class="sib-form-block__button-icon"></span>
                                            NEWSLETTER ABONNIEREN
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <input type="text" name="email_address_check" value="" class="input--hidden">
                            <input type="hidden" name="locale" value="de">
                        </form>
                    </div>
                </div>
            </div>
            <!-- END - We recommend to place the above code where you want the form in your website html  -->

            <!-- START - We recommend to place the below code in footer or bottom of your website html  -->
            <script>
                window.REQUIRED_CODE_ERROR_MESSAGE = 'Wähle bitte einen Ländervorwahl aus.';
                window.LOCALE = 'de';
                window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE = "Die eingegebenen Informationen sind nicht gültig. Bitte überprüfe das Feldformat und versuche es erneut.";
                window.REQUIRED_ERROR_MESSAGE = "Dieses Feld darf nicht leer sein. ";
                window.GENERIC_INVALID_MESSAGE = "Die eingegebenen Informationen sind nicht gültig. Bitte überprüfe das Feldformat und versuche es erneut.";
                window.translation = {
                    common: {
                        selectedList: '{quantity} Liste ausgewählt',
                        selectedLists: '{quantity} Listen ausgewählt'
                    }
                };

                var AUTOHIDE = Boolean(0);
            </script>
            <!-- END - We recommend to place the above code in footer or bottom of your website html  -->
            <!-- End Brevo Form -->
        </div>
    </div>
    <div class="section-spacing-medium"></div>
</section>

<script>
    jQuery(document).ready(function($) {
        var scriptLoaded = false;
        jQuery(window).on('scroll', function() {
            if (jQuery(window).scrollTop() >= 2500 && !scriptLoaded) {
                scriptLoaded = true;
                jQuery.getScript('https://sibforms.com/forms/end-form/build/main.js');
            }
        });
    });
</script>