<?php
/**
 * Create section for link in bio settings in customizer.
 *
 * @package link-in-bio
 */

 // Exit if accessed directly.
 defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LinkInBio_Customizer' ) ) {

	require_once ABSPATH . WPINC . '/class-wp-customize-control.php';

	/**
	 * LinkInBio_Customizer class.
	 */
	class LinkInBio_Customizer {

		/**
		 * Constructing a customizing running lemming.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			add_action( 'customize_register', array( $this, 'register' ) );
		}

		/**
		 * Idxbroker_customize_register function.
		 *
		 * @access public
		 * @param mixed $wp_customize Support Customizer.
		 * @return void
		 */
		function register( $wp_customize ) {

			// Link In Bio Settings Panel.
			$wp_customize->add_panel(
				'linkinbio',
				array(
					'priority'        => 10,
					'capability'      => 'manage_options',
					'theme_supports'  => '',
					'title'           => __( 'Link In Bio Settings', 'linkinbio' ),
					'description'     => __( 'Customize settings for the Link In Bio plugin.', 'linkinbio' ),
				)
			);

			// Landing Page Settings Section.
			$wp_customize->add_section(
				'linkinbio_landing_page_section',
				array(
					'title'       => __( 'Landing Page Settings', 'linkinbio' ),
					'description' => __( 'Insert code into the header or footer', 'linkinbio' ),
					'priority'    => 30,
					'panel'       => 'linkinbio',
				)
			);

			// Landing Page Image setting.
			$wp_customize->add_setting( 'link-in-bio-page-image', array(
				//'default'           => plugins_url( 'assets/images/user-circle-solid-108.png', WP_LinkInBio::get_plugin_base_name() ),//'sprintf( '%s/images/bg-%s.jpg', get_stylesheet_directory_uri(), $image )',
				'sanitize_callback' => 'absint',
				'type'              => 'option',
			) );

			// Landing Page Title setting.
			$wp_customize->add_setting(
				'linkinbio_landing_page_title',
				array(
					'default'   => '',
					'type'      => 'option',
					'transport' => 'refresh',
				)
			);

			// Landing Page Image Control.
			$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'link-in-bio-page-image', array(
				'label'    => __( 'Landing Page Image:', 'infinity-pro' ),
				'section'  => 'linkinbio_landing_page_section',
				'settings' => 'link-in-bio-page-image',
				'width' => 150,
				'height' => 150,
				'flex_width' => false,
				'flex_height' => false,
			) ) );

			// Landing Page Title Controls.
			$wp_customize->add_control(
				'linkinbio_page_title',
				array(
					'label'       => __( 'Page Title', 'linkinbio' ),
					'description' => __( 'Landing Page Title', 'linkinbio' ),
					'type'        => 'text',
					'section'     => 'linkinbio_landing_page_section',
					'settings'    => 'linkinbio_landing_page_title',
				)
			);
			

		}
	}

	new LinkInBio_Customizer();

}
