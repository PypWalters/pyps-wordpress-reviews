<?php
/**
 * Pyp_Reviews_Output_Test
 * @group frontend
 */
use Pyp_Reviews_Output;

class Pyp_Reviews_Output_Test extends WP_UnitTestCase {

	/** @test */
	public function test_display_saved_review() {

		$comment_object = $this->factory->comment->create(
			array(
				'meta_input' => array(
					'pyp_rating' => 4,
				),
			)
		);

		$output_class = new Pyp_Reviews_Output;

		$this->assertEquals(
			'Rated 4/5<br />This is a test review.',
			$output_class->display_saved_review( $comment_object['post_content'], $comment_object )
		);
	}
}
