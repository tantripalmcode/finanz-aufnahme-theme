<?php 
$item              = $args;
$image             = $item['icon']  ?? '';
$title             = $item['title'] ?? '';
$description       = $item['description'] ?? '';
$title_heading_tag = $item['title_heading_tag'];
$title_class       = $item['title_class'];
$image_size        = $item['image_size'];
$image_class       = $item['image_class'];
$description_class = $item['description_class'];
$for_mobile        = $item['for_mobile'];
?>

<div class="budi-icon-box-grid__item">
    <div class="budi-icon-box-grid__item-header d-flex <?php echo !$for_mobile ? 'flex-column flex-lg-row' : ''; ?> align-items-center">
        <?php if ( $image ) {
            echo wp_get_attachment_image( $image, $image_size, false, ['class' => 'budi-icon-box-grid__item-image ' . $image_class] );
        } ?>
        <<?php echo $title_heading_tag; ?> class="budi-icon-box-grid__item-title <?php echo esc_attr( $title_class ); ?>">
            <?php echo nl2br( $title ); ?>
        </<?php echo $title_heading_tag; ?>>
    </div>

    <div class="budi-icon-box-grid__item-content <?php echo esc_attr( $description_class ); ?>">
        <?php echo $description; ?>
    </div>
</div>