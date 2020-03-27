<?php
/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$landing_page_image = wp_get_attachment_url( get_option( 'link-in-bio-page-image' ) );
$landing_page_image = (false !== $landing_page_image ) ? $landing_page_image : plugins_url( 'assets/images/user-circle-solid-156.png', WP_LinkInBio::get_plugin_base_name() );

get_header();
?>
<section>
    <div class="lib-header" > 
        <br>
        <?php echo apply_filters( 'link_in_bio_archive_header_image', '<img class="bio-circle" src="' . $landing_page_image . '">' ); ?> 
        <h6> Links <h6>
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