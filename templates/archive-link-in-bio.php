<?php
/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$landing_page_image = wp_get_attachment_url( get_option( 'linkinbio_page_image' ) );
$landing_page_image = (false !== $landing_page_image ) ? $landing_page_image : plugins_url( 'assets/images/user-circle-solid-156.png', WP_LinkInBio::get_plugin_base_name() );

$landing_image_link = get_option( 'linkinbio_landing_page_image_link' );
$landing_image_caption = get_option( 'linkinbio_landing_page_caption' );
get_header();
?>
<section>
    <div class="lib-header" > 
        <br>
        <a href="<?php echo esc_url(  apply_filters( 'linkinbio_archive_header_link', $landing_image_link ) ); ?> ">
            <?php echo apply_filters( 'linkinbio_archive_header_image', '<img class="bio-circle" src="' . $landing_page_image . '">' ); ?> 
        </a>
        <h6> <?php echo $landing_image_caption ?><h6>
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
//get_footer();
wp_footer(  );