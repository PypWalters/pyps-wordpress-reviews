<?php
/**
 *
 * Displaying the star reviews after they have been submit
 * @since 1.0
 */

class Pyp_Reviews_Output{
  /**
    * Contains the instance of the class
    *
  	 * @since 1.0
  	 * @access private
  	 * @var static
  	 */
  	private static $instance = null;

    /**
     * Class constructor.
     *
     * Left empty on purpose. This class uses the init method.
     *
     * @since 1.0
     * @access private
     *
     * @return null
     */
    private function __construct() {}

      /**
       * Creates and instance of this class.
       *
       * @since 1.0
       *
       * @return object
       */
      public static function get_instance() {
        if ( is_null( self::$instance ) ) {
          self::$instance = new self();
          self::$instance->hooks();
        }

        return self::$instance;
      }

      public function hooks() {
        add_filter( 'comment_text', array( $this, 'display_saved_review' ), 10, 2 );
		  }

      public function display_saved_review( $text, $comment_obj ) {
        $pyp_rating = get_comment_meta( $comment_obj->comment_ID , 'pyp_rating', 'single' );
        if ( ! empty( $pyp_rating ) ) {
          return esc_html__( 'Rated' ) . ' ' . esc_html( $pyp_rating ) . '/5<br />' . $text;
        }
        return $text;
      }

      // TODO: make sure review contains propper schema

}
