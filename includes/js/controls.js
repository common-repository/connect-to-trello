/*
 * Connect to Trello
 * Controls JavaScript
 */

jQuery(document).ready(function() {

	var Controls = new TrelloControls();

	Controls.setup();

});

var TrelloControls = function() {

	//private
	var $addPostButton 		= jQuery('#add_trello_post');
	var $addPostBox 		= jQuery('#trello_add_post_meta_box');
	var list_id				= jQuery('#trello_form_post input[name="list_id"]').val();
	var card_title			= jQuery('#trello_form_post input[name="card_title"]').val();
	var checklist_title		= jQuery('#trello_form_post input[name="checklist_title"]').val();
	var checklist_items		= JSON.parse(jQuery('#trello_form_post input[name="checklist_items"]').val().replace(/'/g, "\""));
	var numberOfItems 		= 0;

	var renderNotAuthorized = function() {
		$addPostBox.html(
			c2trello_controls_content.you_are_not_connected_to_trello
		);
	}

	var startAuthorization = function() {
	 	Trello.authorize({
			type: 'popup',
			name: 'Connect to Trello',
			scope: {
				read: true,
				write: true },
			expiration: 'never',
			success: authorizationSuccess,
			error: authorizationFailure
		});
	}

	var authorizationFailure = function() {
		renderNotAuthorized();
	}

	var authorizationSuccess = function() {
		$addPostButton.click( addCard );
	}

	var addCard = function() {

		var newCard = {
			name: card_title
		};

		Trello.post('/lists/'+list_id+'/cards', newCard)
			.fail(renderError)
			.done(addChecklist);
	}

	var addChecklist = function( data ) {

		var newChecklist = {
			idCard: data.id,
			name: checklist_title
		}

		Trello.post('/checklists', newChecklist)
			.fail(renderError)
			.done(addItems);

	}

	var addItems = function( data ) {
		var itemsAdded = 0;

		if( checklist_items.length > 0 ) {
			checklist_items.map(function(item){
				var newItem = {
					name: item
				}
				Trello.post('/checklists/'+data.id+'/checkItems', newItem)
					.fail(renderError)
					.done(countItems(++itemsAdded));
			});
		}

	}

	var countItems = function( currentNumber ) {
		if( currentNumber === checklist_items.length ) {
			renderDone();
		}
	}

	var renderDone = function() {
		$addPostBox.html(c2trello_controls_content.this_post_has_been_added_to_trello);
	}

	var renderError = function() {
		$addPostBox.html(c2trello_controls_content.something_went_wrong);
	}

	//public
	this.setup = function() {

		startAuthorization();

	}

}