<?php

if( ! class_exists( 'Dropcap' ) ) {

    class Dropcap extends Shortcode_BP_Shortcode_Base {

        /**
         * The defaults for this shortcode
         *
         * @since   1.0.0
         * @access  public
         */
        public  $defaults = array(
            'id'    => '',
            'class' => 'dropcap-large maroon',
            'color' => '',
        );

        /**
         * Returns the dropcap shorcode
         *
         * @since       1.0.0
         * @access      public
         *
         * @return  string    Shortcode.
         */
        public function shortcode() {

            // The name of the shortcode that called.
            $shortcode = $this->shortcode;

			// User defined attributes.
			$attributes = $this->attributes;

			// Defined shortcode id.
			$id = $attributes['id'];

			// Defined shortcode class.
			$class = $attributes['class'];

            return sprintf( '<div id="%1$s" class="elements dropcap %2$s">%3$s</div>', $id, $class, $letter );
        }
    }
}
