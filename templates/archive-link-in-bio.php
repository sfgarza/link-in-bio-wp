<?php
/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<section>
    <div> 
        <h1 style="text-align:center;"><?php echo apply_filters( 'link_in_bio_archive_header', get_bloginfo('name') ); ?></h1>
    </div>
</section>
<section>
    <div> 
    <?php 
    do_action( 'pre_link_in_bio' );
    ?>
    </div>
</section>
<section>   
    <div>
        <div>
        <?php
            if ( have_posts() ) : 
                while ( have_posts() ) :
                    the_post();?> 
                    <a href="<?php echo apply_filters( 'link_in_bio_post_link', get_the_permalink( ) ) ; ?>">
                    <?php echo apply_filters( 'link_in_bio_post_thumbnail',  get_the_post_thumbnail( null, array( 250, 250 ) ) ); ?> 
                    </a> 
                    <?php
                endwhile;
            endif;
        ?>
        </div>
    </div>
</section>

<?php
get_footer();