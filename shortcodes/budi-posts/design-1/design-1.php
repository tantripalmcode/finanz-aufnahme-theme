<?php
/*
    Standard Design zieht sein markup aus template-parts/*-archive.php und
    wird auch vom normalen blog verwendet
*/
?>

<div class="post-archive">

    <?php

    $i = 1;

    while($posts->have_posts()) { $posts->the_post();


        if($atts["posts_per_page"] > 0 && $i > $atts["posts_per_page"]){
            $class = "hidden";
        }else{
            $class = "";
        }

        // Check if post-template for post-type is present, else show post-archive template
        if( locate_template( 'template-parts/' . get_post_type() . '-archive.php') ){
            // Bekannter Post Type mit unserem Style laden

            get_template_part('template-parts/' . get_post_type(), 'archive', ["class" => $class] );
        }else{
            // Unbekannter CPT mit standard Style anzeigen
            get_template_part('template-parts/post', 'archive', ["class" => $class] );
        }

        $i++;

    }

    ?>

</div>
