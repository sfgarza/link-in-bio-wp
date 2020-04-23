<?php
/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$landing_page_image = wp_get_attachment_url( get_option( 'linkinbio_page_image' ) );
$landing_page_image = (false !== $landing_page_image ) ? $landing_page_image : plugins_url( 'assets/images/user-circle-solid-156.png', WP_LinkInBio::get_plugin_base_name() );

$landing_image_link    = get_option( 'linkinbio_landing_page_image_link' );
$landing_image_caption = get_option( 'linkinbio_landing_page_caption' );

get_header()
?>

<a name="media"></a>
<div class="libio-header" > 
    <a href="<?php echo esc_url(  apply_filters( 'linkinbio_archive_header_link', $landing_image_link ) ); ?> ">
        <?php echo apply_filters( 'linkinbio_archive_header_image', '<img class="bio-circle" src="' . $landing_page_image . '">' ); ?> 
    </a>
    <p class="libio-header-caption"> <?php echo $landing_image_caption ?><p>
</div>
<div class="libio-before-content"> 
    <?php 
    do_action( 'linkinbio_before_content' );
    ?>
</div>  
<a name="posts"></a>
<div class="libio-container">
    <?php
        if ( have_posts() ) : 
            while ( have_posts() ) :
                the_post();?> 
                <div class="libio-photo-wrapper">
                    <a class="libio-photo" href="<?php echo apply_filters( 'linkinbio_post_link', get_the_permalink( ) ) ; ?>">
                    <?php echo apply_filters( 'linkinbio_post_thumbnail',  get_the_post_thumbnail( null, 'image_link' ) ); ?> 
                    </a> 
                </div>
                <?php
            endwhile;
        endif;
    ?>
</div>
<div class="libio-page-container">
    <div class="libio-pager">
        <a href="#" class="button button-primary btn-load-more">Load More</a>
    </div>
</div>

<?php
get_footer();