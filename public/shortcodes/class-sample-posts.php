<?php

if( ! class_exists( 'Sample_Posts' ) ) {

    class Sample_Posts extends Shortcode_BP_Shortcode_Base {

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
        );

        /**
         * Returns an array of posts
         *
         * @since       1.0.0
         * @access      public
         *
         * @return  array   $posts.
         */
        private function get_sample_posts() {

            //Get the posts.
            $recent_posts = wp_get_recent_posts();

            // Generates the content for each post template mapping.
            foreach ( $recent_posts as $posts => $post ) {

                $total_posts        = count( $recent_posts );
                $post_id            = $post['ID'];
                $post_classes       = 'template_class';
                $post_title_text    = $post['post_title'];
                $post_content       = $post['post_content'];
                $post_url           = esc_url( get_permalink( $post_id ) );
                $post_title         = '<a href="' . $post_url.'">'. $post_title_text . '</a>';
				$placeholder        = plugin_dir_url( dirname( __FILE__ ) ) . 'imgs/placeholder.png';
				$image_src          = $placeholder;
                $post_author_text   = get_the_author();
                $map                = compact( 'image_src', 'post_id', 'post_classes', 'post_title', 'post_content' );

                // addin 'false' will prevent function from checking for template versions.
                $sample_posts[]     = $this->get_shortcode_template( $map, 'post', false );

            }; // end foreach

            return implode( $sample_posts );
        }

        /**
         * Returns the sample posts shortcode.
         *
         * @since       1.0.0
         * @access      public
         *
         * @return  string  Sample posts shortcode.
         */
        public function shortcode() {

			// User defined attributes.
			$attributes = $this->attributes;

            // Get the name of the current shortcode.
            $id = $attributes['id'];

            // Get the name of the current shortcode.
            $class = $attributes['class'];

            // Multi sub template map for posts.
            $sample_posts = $this->get_sample_posts();

            // Set message if there are no posts.
            $sample_posts = empty( $sample_posts ) ? '<h4><em>Looks like you have no posts...</em></h4>' : $sample_posts;

            // The shortcode's main template map matching the variables in the template.
            $map = compact( 'id', 'class', 'posts_header', 'sample_posts' );

            return $this->get_shortcode_template( $map);
        }
    }
}
