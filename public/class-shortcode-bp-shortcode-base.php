<?php

/**
 * The shortcode boiler plate base.
 *
 * The base class responsible for generating the shortcodes
 * along with the templates assiged to each shortcode.
 *
 * @package    Shortcodes-BP
 * @subpackage Shortcodes-BP/Includes
 * @author     Your Name <email@example.com>
 */
abstract class Shortcode_BP_Shortcode_Base {

	/**
	 * The name of the current shortcode.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public $shortcode;

    /**
     * Defaults defined for the current shortcode.
     *
     * @since    1.0.0
     * @access   public
     * @var      array    $defaults    Shortcode defaults.
     */
	public $defaults;

	/**
	 * Shortcode attributes defined by user.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $attributes    Shortcode attributes defined by user.
	 */
	public $attributes;

	/**
	 * Handle used to select shortcode template versions
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version_handle    Handle used to select shortcode template versions
	 */
	public $version_handle = 'version';

	/**
	 * Shortcode content passed by the user.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    Shortcode content passed by the user.
	 */
	public $content;

	/**
	 * Loader object for loading shortcode templates.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $template_loader    Loader object for loading shortcode templates.
	 */
	public $template_loader;

	/**
	 * Initialize the class and set its properties.
	 *
	 * Sets the path to the plugin.
     * Initiates the shortcode template loader.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->set_plugin_paths();
		$this->load_template_loader();

	}

	/**
	 * Sets the paths that will be used by various files in
     * plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function set_plugin_paths() {

        $this->plugin_path 			= plugin_dir_path( dirname( __FILE__ ) );
        $this->shortcodes_dir_path  = plugin_dir_path( dirname( __FILE__ ) ) . 'public/shortcodes/';
	}

	/**
	 * Iniates the shortcode template loader.
	 *
	 * @since  1.0.0
	 */
	public function load_template_loader() {
		$this->template_loader = new Shortcode_BP_Template_Loader();
	}

	/**
	 * Should be used on returned shortcode content since WordPress wpautop
	 * will add errant paragraph tags
	 *
	 * @since  0.1.0
	 *
	 * @param  string  $string String to remove white-space
	 * @return string  Normalized string
	 */
	public function normalize_string( $string ) {
		return trim( preg_replace( array(
			'/[\t\n\r]/', // Remove tabs and newlines
			'/\s{2,}/', // Replace repeating spaces with one space
			'/> </', // Remove spaces between carats
		), array(
			'',
			' ',
			'><',
		), $string ) );
	}

    /**
     * Grrrrrrrrrrrrrrrrrrrrrrrr.... autop!!
     *
     * @since 1.0.0
     * @return shortcode content without the extra p.
     */
    function remove_wpautop( $content ) {

        if( ! empty( $content ) ) {

            $content = str_replace( array ( '<p>' , '</p>' ), '', $content );

            return $this->normalize_string ( $content );
        }
    }

	/**
     * Automagically generates an id for each shortcode
     * that doeesnt have one.
     *
     * @since   1.0.0
     * @access  public
     *
     * @param  string $attributes   Attributes set by user.
     * @return array                The shortcode defaults with ids.
     */
    public function generate_shortcode_id( $attributes ) {

        // Exclude shrotcodes from having ids generated automatcally.
        $exclude = array();

        // Allow devs to exclude shortcodes from having ids generated automatcally.
        $exclude = apply_filters( 'shortcode_id_excludes', $exclude );

        if( empty ( $attributes['id'] ) && ! in_array( $this->shortcode, $exclude ) ) {

            $id = str_replace( '_' , '-', $this->shortcode . '-' . mt_rand( 0 , 9999 ) );

            $attributes = array_merge( $attributes, array( 'id' => $id ) );
        }

        return $attributes;
    }

	/**
	 * Retrieves the template for the shortcode.
	 *
	 * @since  1.0.0
	 * @param  array   $template_map       An array of variables matching template variables
	 * @param  string  $custom_template    Name of the custom template replacing the default template.
	 * @param  string  $alternate_template Name of the alternatte template version replacint the default verison.
	 *
	 * @return  string  The shortcode in its assigned template.
	 */
	public function get_shortcode_template( $template_map, $custom_template = false, $alternate_template = false ) {

		// Get the template file name for this shortcode.
		$template = str_replace( '_', '-', $this->shortcode );

		// Remove the '.php' extension if one was added.
		$template = ( false === strpos( $template, '.php' ) ) ? $template : str_replace( '.php', '', $template );

		// Determine the type of template that should be loaded.
		$template = ! empty( $custom_template ) && ( '_sub' !== $custom_template ) ? $custom_template : $template;

        // Ignore base template checking when there are no versions are found for sub templates.
		$template = ( empty( $this->attributes[ $this->version_handle ] ) && ( '_sub' === $custom_template ) ) ? false : $template;

        // Checks for template in a directory.
        $template = empty( $this->template_directory ) ? $template : "{$this->template_directory}/$template" ;

		// Get the correct template version for this shortcode.
		$version = ! empty( $this->attributes[ $this->version_handle ] ) ? $this->attributes[ $this->version_handle ] : false;

		// Check for a custom version. Works only when an alt file name is given.
		$version = ! empty( $alternate_template ) ? $alternate_template : $version;

        // Ignore version checking even when versions are given.
        $version = ( 'disable' === $alternate_template ) || ( false === $template ) ? false : $version;

		ob_start();

        // Lets get our shortcode's template.
        $this->template_loader->get_template_part( $template, $version );
        $template = ob_get_clean();

        if( ! empty( $template ) && ! empty( $template_map ) ) {

            // Appends the '$' with the content key so we can match vars in the template.
            foreach ( $template_map as $content_key => $content_value ) {
                $content_key 		= '$' . $content_key;
                $map[ $content_key ] = $content_value;
            }

            return strtr( $template, $map );
        }

        return ! empty( $template ) ? $template : false;
	}

	/**
	 * Returns a fully functional shortcode.
	 *
	 * @since    1.0.0
	 *
     * @return  string  The shortcode.
	 */
	abstract function shortcode();

    /**
     * Callaback used for each shortcode.
     *
     * Normalizes the shortcode.
     * Merges shortcode defaults with user supplied attributes.
     * Generates an id for the shortcode.
     *
     * @since    1.0.0
     * @access   public
     *
     * @param  array   $atts        Shortcode attributes.
     * @param  string  $content     Shortcode content.
     * @param  string  $shortcode   The name of the shortcode.
     */
    public function render_shortcode( $atts, $content = null, $shortcode ) {

        // Content passed into the shortcode.
		$this->content = $this->normalize_string( $content );

		// The name of the shortcode that called this function.
		$this->shortcode = $shortcode;

		// Merge shortcode defaults with user defined attributes.
		$attributes = shortcode_atts( $this->defaults, $atts, $shortcode );

		// Gernerates an id for the shortcode if none is found.
        $this->attributes = $this->generate_shortcode_id( $attributes );

		// Return your beautiful shortcode.
		return $this->shortcode();
	}
}
