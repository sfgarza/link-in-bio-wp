<?php

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$redirect_link = get_post_meta( $post->ID, "_linkinbio_redirect_link", true);

do_action( 'link_in_bio_before_redirect', $post->ID, $redirect_link );

wp_redirect( $redirect_link, 302, 'WordPress Link In Bio' );