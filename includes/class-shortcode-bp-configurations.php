<?php

/**
 * Map shortcodes with their respective file names.
 *
 * @since      1.0.0
 * @package    Shortcode_BP
 * @subpackage Shortcode_BP/includes
 * @author     Author Name <youremail@example.com>
 */
class Shortcode_BP_Configurations {

	/**
	 * Shortcodes and their respective file paths.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string        The current version of the plugin.
	 */
    public static  $shortcodes = array(
        'sample-shortcode' 	=> 'class-sample-shortcode',
        'sample-posts' 		=> 'class-sample-posts',
    );

	/**
     * Registers all dropcaps to the dropcap shortcode class
     * file.
     *
     * @since       1.0.0
     * @access      public
     *@returns      array   Dropcap shorcode names and file names
     */
    private static function dropcaps() {

        // Create an array of upper and lower case letters.
        $allcaps    = range( 'A','Z' );
        $smallcaps  = range( 'a','z' );
        $letters    = array_merge( $allcaps, $smallcaps );

        // Create an array of dropcap filenames based on the number of letters.
        $dropcap_files = array_fill( 0, count( $letters ), 'class-dropcap.php' );

        // Create array of dropcap letter => filename pairs.
        return array_combine( $letters, $dropcap_files );

    }

	/**
	 * Returns file configurations for all shortcodes.
	 *
	 * @since      1.0.0
	 * @access     public
	 * @returns    array   Shorcode names and file names
	 */
	public static function get_shortcode_file_configs() {
		return array_merge( self::$shortcodes, self::dropcaps() );
	}
}
