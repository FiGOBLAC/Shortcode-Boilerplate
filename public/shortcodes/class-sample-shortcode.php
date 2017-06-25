<?php

if( ! class_exists( 'Sample_Shortcode' ) ) {

    class Sample_Shortcode extends Shortcode_BP_Shortcode_Base {

        /**
         * The defaults for this shortcode
         *
         * @since   1.0.0
         * @access  public
         */
        public $defaults = array(
            'id'                => '',
            'class'             => '',
            'version'           => '',
            'header'            => 'Sample Shortcode Header',
            'altheader'         => 'Alternate Template Header',
        );

        /**
         * Returns sample shortcode
         *
         * @since       1.0.0
         * @access      public
         *
         * @return  string   Sample shortcode.
         */
        public function shortcode() {

			// User defined attributes.
			$attributes = $this->attributes;

			// Defined shortcode id.
			$id = $attributes['id'];

			// Defined shortcode class.
			$class = $attributes['class'];

			// Content passed into the shortcode.
			$content = $this->content;

            // Change the header based on template being viewed.
            $header = ( $attributes['version'] === 'alternate' ) ? $attributes['altheader'] : $attributes['header'];

            // placeholder Image
            $placeholder = plugin_dir_url( dirname( __FILE__ ) ) . 'imgs/placeholder.png';

            // Image for alternate template
            $image_src = ! empty( $attributes['myimage'] ) ? esc_url( $attributes['myimage'] ) : $placeholder;

            // The shortcode's main template map matching the variables in the template.
			$map =	compact( 'id', 'class', 'header', 'content', 'image_src' );

			// Fully furnished shortcode with content and all.
			return  $this->get_shortcode_template( $map );
		}
    }
}
