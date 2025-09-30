<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    return;
}

$type                     = isset( $args['type'] ) ? $args['type'] : '';
$image                    = isset( $args['image'] ) ? $args['image'] : '';
$image_size               = isset( $args['image_size'] ) ? $args['image_size'] : '';
$image_size_custom        = isset( $args['image_size_custom'] ) ? $args['image_size_custom'] : '';
$image_class              = isset( $args['image_class'] ) ? $args['image_class'] : '';
$use_svg_code             = isset( $args['use_svg_code'] ) ? $args['use_svg_code'] : '';
$title                    = isset( $args['title'] ) ? $args['title'] : '';
$title_class              = isset( $args['title_class'] ) ? $args['title_class'] : '';
$title_heading_tag        = isset( $args['title_heading_tag'] ) ? $args['title_heading_tag'] : '';
$sub_title                = isset( $args['sub_title'] ) ? $args['sub_title'] : '';
$sub_title_class          = isset( $args['sub_title_class'] ) ? $args['sub_title_class'] : '';
$sub_title_heading_tag    = isset( $args['sub_title_heading_tag'] ) ? $args['sub_title_heading_tag'] : '';
$sub_title_position       = isset( $args['sub_title_position'] ) ? $args['sub_title_position'] : '';
$content                  = isset( $args['description'] ) ? $args['description'] : '';
$description_class        = isset( $args['description_class'] ) ? $args['description_class'] : '';
$button_class             = isset( $args['button_class'] ) ? $args['button_class'] : '';
$link_url                 = isset( $args['link_url'] ) ? $args['link_url'] : '';
$link_target              = isset( $args['link_target'] ) ? $args['link_target'] : '';
$link_rel                 = isset( $args['link_rel'] ) ? $args['link_rel'] : '';
$link_title               = isset( $args['link_title'] ) ? $args['link_title'] : '';
$make_link_whole_box      = isset( $args['make_link_whole_box'] ) ? $args['make_link_whole_box'] : '';

$image_size = budi_get_image_size( $image_size, $image_size_custom );
?>

<?php if ( $type === "image" && $image ) { ?>

    <figure class="budi-content-box-slider__image <?php echo esc_attr( $image_class ); ?>">
        <?php if ( $use_svg_code === "yes" ) {

            $image_path         = wp_get_original_image_path( $image );
            $image_file_type    = wp_check_filetype( $image_path );

            if ( isset($image_file_type['ext']) && $image_file_type['ext'] === "svg" ) {
                $image_svg_code = file_get_contents( $image_path );
                echo $image_svg_code;
            } else{
                echo wp_get_attachment_image( $image, $image_size, false );
            }
        } else {
            echo wp_get_attachment_image( $image, $image_size, false );
        }
        ?>
    </figure>

<?php } ?>

<div class="budi-content-box-slider__content">

    <!-- If subtitle available and position is before title -->
    <?php if( $sub_title_position === "before_title" && $sub_title ){ ?>
        <<?php echo $sub_title_heading_tag; ?> class="budi-content-box__sub-title <?php echo esc_attr( $sub_title_class ); ?>">
            <?php echo $sub_title; ?>
        </<?php echo $sub_title_heading_tag; ?>>
    <?php } ?>

    <?php if ( $title ) { ?>
        <<?php echo $title_heading_tag; ?> class="budi-content-box-slider__title <?php echo esc_attr( $title_class ); ?>">
            <?php echo budi_fix_special_characters( $title ); ?>
        </<?php echo $title_heading_tag; ?>>
    <?php } ?>

    <!-- If subtitle available and position is after title -->
    <?php if( $sub_title_position === "after_title" && $sub_title ){ ?>
        <<?php echo $sub_title_heading_tag; ?> class="budi-content-box__sub-title <?php echo esc_attr( $sub_title_class ); ?>">
            <?php echo $sub_title; ?>
        </<?php echo $sub_title_heading_tag; ?>>
    <?php } ?>

    <?php if ( $content ) { ?>
        <div class="budi-content-box-slider__description <?php echo esc_attr( $description_class ); ?>">
            <?php echo $content; ?>
        </div>
    <?php } ?>

    <?php if ( $link_url ) { ?>
                        
        <?php if ( $make_link_whole_box ) { ?>

            <span class="budi-content-box-slider__button <?php echo esc_attr( $button_class ); ?>">
                <?php echo esc_html( $link_title ); ?>
            </span>

        <?php } else { ?>

            <a href="<?php echo esc_url( $link_url ); ?>" class="budi-content-box-slider__button <?php echo esc_attr( $button_class ); ?>" target="<?php echo esc_attr( $link_target ); ?>" rel="<?php echo esc_attr( $link_rel ); ?>">
                <?php echo esc_html( $link_title ); ?>
            </a>

        <?php } ?>

    <?php } ?>

</div>
