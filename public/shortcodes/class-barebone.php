<?php

if( ! class_exists( 'Barebone' ) ) {

    class Barebone extends Shortcode_BP_Shortcode_Base {

        /**
         * The defaults for this shortcode
         *
         * @since   1.0.0
         * @access  public
         */
        public $defaults = array(
            'id'                => '',
            'class'             => '',
        );

        /**
         * Returns the shortcode defined with or without a template.
         *
         * @since       1.0.0
         * @access      public
         *
         * @return  string   Shortcode.
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

            // The shortcode's main template map matching the variables in the template.
            $map =  compact( 'id', 'class', 'content');

            // Return a fully furnished shortcode template.
            return  $this->get_shortcode_template( $map );

		}
    }
}
