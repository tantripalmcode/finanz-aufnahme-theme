<div class="budi-posts">

    <?php
    $i = 1;
    ?>
    <?php while($posts->have_posts()) { $posts->the_post(); ?>
        <?php
        if($atts["posts_per_page"] > 0 &&  $i > $atts["posts_per_page"]){
            $class = "hidden";
        }else{
            $class = "";
        }
        ?>
        <div class="budi-post <?php echo $class ?>" <?php echo get_term_data_string( get_the_id() ) ?> >
            <div>
                <a href="<?php the_permalink(); ?>">
                    <h2 class="title"><?php the_title() ?></h2>
                </a>
                <p><?php echo get_category_links_string( get_the_id() ) . " - " . get_the_date() ?></p>
                <div><?php the_excerpt() ?></div>
                <a href="<?php the_permalink() ?>" class="button">Weiterlesen</a>
            </div>
        </div>
    <?php $i++; } ?>
</div>
