<?php
/*
 * Connect to Trello
 * Controls Class
 */

class C2Trello_Controls {

	/*==========================MEMBERS=========================*/

	public $list_id, $checklist_title, $checklist_items;

	/*==========================METHODS========================*/

	/*
	 * Constructor
	 */
	public function __construct( $textdomain ) {
		$this->textdomain = $textdomain;
	}

	public function render_controls( $post_type, $list_id, $checklist_title, $checklist_items ) {

		//save id
		$this->list_id = $list_id;
		$this->checklist_title = $checklist_title;
		$this->checklist_items = $checklist_items;

		// Limit meta box to posts
        $post_types = array( 'post' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'trello_control_post',
                'Trello',
                array( $this, 'add_post' ),
                $post_type,
                'side',
                'high'
            );
        }

	}

	public function add_post( $post_object ) {

		?>
		<div id="trello_add_post_meta_box">
			<p><?php _e('Check your settings to see what this button exactly does.', $this->textdomain); ?></p>
			<div id="trello_form_post">
	            <input name="list_id" type="hidden" value="<?php echo $this->list_id; ?>" />
	            <input name="card_title" type="hidden" value="<?php echo $post_object->post_title; ?>"  />
	            <input name="checklist_title" type="hidden" value="<?php echo $this->checklist_title; ?>" />
	            <input name="checklist_items" type="hidden" value="<?php echo str_replace("\"", "'", $this->checklist_items); ?>" />
	        	<span class="button button-primary button-large" id="add_trello_post"><?php _e('Add to Trello', $this->textdomain); ?></span>
	        </div>
	    </div>
		<?php

	}

}

?>