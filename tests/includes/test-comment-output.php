<?php
/**
 * Pyp_Reviews_Output_Test
 * @group frontend
 */
//use Pyp_Reviews_Output;

class Pyp_Reviews_Output_Test extends WP_UnitTestCase {

	public function test_display_saved_review_w_no_review_meta() {
		$output_class = new Pyp_Reviews_Output;
		$expected     = 'This is a test.';

		$comment_args   = array(
			'comment_content' => 'This is a test.',
		);
		$comment_object = $this->factory->comment->create_and_get( $comment_args );

		$this->assertEquals(
			$expected,
			$output_class->display_saved_review( $comment_object->comment_content, $comment_object )
		);
	}

	public function test_display_saved_review_w_good_review_meta() {
		$comment_args   = array(
			'comment_content' => 'This is a test.',
		);
		$comment_object = $this->factory->comment->create_and_get( $comment_args );
		add_comment_meta( $comment_object->comment_ID, 'pyp_rating', 4 );

		$output_class = new Pyp_Reviews_Output;
		$expected     = 'Rated 4/5<br />This is a test.';

		$this->assertEquals(
			$expected,
			$output_class->display_saved_review( $comment_object->comment_content, $comment_object )
		);
	}

	public function test_display_saved_review_w_nan_review_meta() {
		$comment_args   = array(
			'comment_content' => 'This is a test.',
		);
		$comment_object = $this->factory->comment->create_and_get( $comment_args );
		update_comment_meta( $comment_object->comment_ID, 'pyp_rating', 'bad!;\'' );

		$output_class = new Pyp_Reviews_Output;
		$expected     = 'This is a test.';

		$this->assertEquals(
			$expected,
			$output_class->display_saved_review( $comment_object->comment_content, $comment_object )
		);
	}
}
