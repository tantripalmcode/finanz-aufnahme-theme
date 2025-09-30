<?php
/*
 * Template Name: Budi News
 * Template Post Type: post
 */

/**
 * Open Graph & Twitter Cards nur für dieses Template.
 * Nutzt das Beitragsbild ausschließlich in Meta-Tags (kein Frontend-Output).
 */
function budi_news_add_social_meta_tags_inline() {
    // Sicherheitshalber prüfen, ob wir im Loop und bei einem Einzelbeitrag sind
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    global $post;

    // Nur wenn ein Beitragsbild existiert
    if ( ! has_post_thumbnail( $post->ID ) ) {
        return;
    }

    // Bilddaten
    $thumb_id = get_post_thumbnail_id( $post->ID );
    $img      = wp_get_attachment_image_src( $thumb_id, 'full' );
    if ( ! $img || empty( $img[0] ) ) {
        return;
    }
    $img_url = esc_url( $img[0] );
    $img_w   = intval( $img[1] );
    $img_h   = intval( $img[2] );
    $img_mime = get_post_mime_type( $thumb_id );
    $img_alt  = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );

    // Seitendaten
    $title = esc_attr( get_the_title( $post ) );
    $desc  = has_excerpt( $post )
        ? wp_strip_all_tags( get_the_excerpt( $post ), true )
        : wp_trim_words( wp_strip_all_tags( $post->post_content ), 40, '…' );
    $desc  = esc_attr( $desc );

    $url       = esc_url( get_permalink( $post ) );
    $site_name = esc_attr( get_bloginfo( 'name' ) );
    $updated_time = esc_attr( get_post_modified_time( 'c', true, $post ) );

    echo "\n" . '<!-- Budi News: Social Meta (inline) -->' . "\n";
    // Open Graph
    echo '<meta property="og:type" content="article" />' . "\n";
    echo '<meta property="og:title" content="' . $title . '" />' . "\n";
    echo '<meta property="og:description" content="' . $desc . '" />' . "\n";
    echo '<meta property="og:url" content="' . $url . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . $site_name . '" />' . "\n";
    echo '<meta property="og:updated_time" content="' . $updated_time . '" />' . "\n";
    echo '<meta property="og:image" content="' . $img_url . '" />' . "\n";
    if ( $img_w && $img_h ) {
        echo '<meta property="og:image:width" content="' . $img_w . '" />' . "\n";
        echo '<meta property="og:image:height" content="' . $img_h . '" />' . "\n";
    }
    if ( $img_mime ) {
        echo '<meta property="og:image:type" content="' . esc_attr( $img_mime ) . '" />' . "\n";
    }
    if ( $img_alt ) {
        echo '<meta property="og:image:alt" content="' . esc_attr( $img_alt ) . '" />' . "\n";
    }

    // Twitter Cards
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . $desc . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . $img_url . '" />' . "\n";
    // Optional:
    // echo '<meta name="twitter:site" content="@bundesweitdigital" />' . "\n";
    echo '<!-- /Budi News: Social Meta (inline) -->' . "\n";
}
// Wichtig: Vor get_header() anhängen, damit es in den <head> gerendert wird.
add_action( 'wp_head', 'budi_news_add_social_meta_tags_inline', 5 );

function enqueue_budi_news_styles() {
    if ( is_singular( 'post' ) ) {
        $version = date("YmdHis"); // Cache-Busting
        wp_enqueue_style( 'budi-news-styles', get_stylesheet_directory_uri() . '/css/budi-news.css', array(), $version );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_budi_news_styles' );

get_header();
?>

<main id="budi-news" role="main">

<?php
while ( have_posts() ) :
    the_post();
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content">
            <h1 class="budi-news-h1"><?php the_title(); ?></h1>
            <p class="entry-date">Letzte Aktualisierung am: <?php echo get_the_modified_date(); ?></p>
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'your-theme-textdomain' ),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->

        <div class="post-tags">
            <h2><?php _e( 'Tags:', 'your-theme-textdomain' ); ?></h2>
            <ul>
                <?php
                $post_tags = get_the_tags();
                if ( $post_tags ) {
                    foreach ( $post_tags as $tag ) {
                        echo '<li><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li>';
                    }
                } else {
                    echo '<li>' . __( 'No tags found', 'your-theme-textdomain' ) . '</li>';
                }
                ?>
            </ul>
        </div><!-- .post-tags -->

        <?php
        $products = get_field('passende_produkte');
        if ( $products ) :
            if ( ! is_array( $products ) ) {
                $products = array( $products );
            }
            $products = array_slice( $products, 0, 1 ); // Nur das erste Produkt auswählen
        ?>
        <div class="related-products">
            <h2><?php _e( 'Unsere Lösung passend zum Thema:', 'your-theme-textdomain' ); ?></h2>
            <ul>
                <?php
                foreach ( $products as $post ) {
                    setup_postdata( $post );
                    $short_description = get_field('budi_short_description', $post->ID);
                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <div class="product-grid">
                                <div>
                                    <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
                                </div>
                                <div>
                                    <h3><?php the_title(); ?></h3>
                                    <?php if ( $short_description ) : ?>
                                        <?php echo ( $short_description ); ?>
                                    <?php endif; ?>
                                    <p class="button-produkte">Mehr erfahren →</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div><!-- .related-products -->
        <?php endif; ?>

        <div class="related-posts">
            <h2>Weitere Beiträge</h2>
            <ul>
                <?php
                $categories = wp_get_post_categories( get_the_ID() );
                $args = array(
                    'category__in' => $categories,
                    'post__not_in' => array( get_the_ID() ),
                    'posts_per_page' => 4,
                    'ignore_sticky_posts' => 1
                );
                $related_posts = new WP_Query( $args );

                if ( $related_posts->have_posts() ) {
                    while ( $related_posts->have_posts() ) {
                        $related_posts->the_post();
                        ?>
                        <li>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </li>
                        <?php
                    }
                } else {
                    echo '<li>' . __( 'No related posts found', 'your-theme-textdomain' ) . '</li>';
                }

                wp_reset_postdata();
                ?>
            </ul>
        </div><!-- .related-posts -->
    </article><!-- #post-<?php the_ID(); ?> -->
<?php endwhile; ?>

</main><!-- #budi-news -->

<?php get_footer();