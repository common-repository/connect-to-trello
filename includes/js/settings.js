/*
 * Connect to Trello
 * Settings JavaScript
 */

 jQuery(document).ready(function() {

 	var API = new APIsettings();
 	var Checklist = new ChecklistSettings();

 	API.setup();

 	Checklist.setup();

 });

var ChecklistSettings = function() {

	//private
	var $checklistEditor 	= jQuery('#checklist-items-editor');
	var $checklistInput 	= jQuery('#checklist-items');

	var renderLoader = function() {
		$checklistEditor.text(c2trello_settings_content.loading);
	}

	var renderUI = function() {
		var items = getItems();

		$checklistEditor.html('');
		if( items.length > 0 ) {
			items.map(function(item){
				$checklistEditor
					.append(checklistItem(item));
			});

			$checklistEditor.find('.checklist-item').change(saveItems);
			$checklistEditor.find('.delete-item').click(deleteItem);

		}
		$checklistEditor
			.append('<div id="add-checklist-item-container" ><span id="add-checklist-item">+</span></div>')
			.children('#add-checklist-item-container')
			.click(addItem)
	}

	var checklistItem = function( content ) {
		return (
			'<div class="checklist-item-container">'
				+'<input type="text" class="checklist-item" value="'+content+'" />'
				+'<span class="delete-item"></span>'
			+'</div>'
		);
	}

	var saveItems = function() {
		var items = [];
		jQuery('.checklist-item-container .checklist-item').each(function() {
			items.push(jQuery(this).val());
		});
		setItems( items );
	}

	var deleteItem = function() {
		jQuery(this).parent().remove();
		saveItems();
	}

	var addItem = function() {
		var items = getItems();
		items.push(c2trello_settings_content.new_item);
		setItems( items );
	}

	var getItems = function() {
		return JSON.parse( $checklistInput.val() );
	}

	var setItems = function( items ) {
		$checklistInput.val( JSON.stringify(items) );
		renderUI();
	}

	//public
	this.setup = function() {
		renderLoader();

		renderUI();

	}

}

var APIsettings = function() {

	//private
 	var $APIcontrollers 	= jQuery('#board-id-selector, #list-id-selector');
 	var $boardSelector 		= jQuery('#board-id-selector');
 	var $listSelector 		= jQuery('#list-id-selector');
 	var $boardInput 		= jQuery('input#board-id');
 	var $listInput 			= jQuery('input#list-id');
 	var $notice 			= jQuery('#c2trello-notice');
 	var $connectButton 		= jQuery('#connect-trello');

 	var authorizationSuccess = function() {

 		Trello.get('/tokens/'+Trello.token()+'/member')
 			.fail( renderError )
 			.done( renderUI );

 	}

 	var renderError = function( err ) {
 		if( err.status == 401 ) {
 			renderNotAuthorized();
 		}
 		else {

 		}
 	}

 	var renderUI = function( data ) {
 		//initial render
 		Trello.get('/members/'+data.id+'/boards', { filter: 'open'}).done(renderBoards);
 		if( $boardInput.val().length > 0 ) {
 			Trello.get('/boards/'+$boardInput.val()+'/lists',{filter:'open'}).done(renderLists);
 		} else {
 			$listSelector.html(c2trello_settings_content.select_a_board_first);
 		}

 	}

 	var boardChange = function() {
 		console.log( jQuery(this) );
 	}

 	var renderBoards = function( boards ) {
 		$boardSelector.html('');

 		var active = $boardInput.val();
 		boards.map(function(board) {
 			var activeClass = active===board.id?'active':'';
 			$boardSelector
 				.append('<button id='+board.id+' class="button '+activeClass+'">'+board.name+'</button>')
 				.children('#'+board.id)
 				.click( selectBoard );
 		});

 	}

 	var renderLists = function( lists ) {
 		$listSelector.html('');

 		var active = $listInput.val();
 		lists.map(function(list) {
 			var activeClass = active===list.id?'active':'';
 			$listSelector
 				.append('<button id='+list.id+' class="button '+activeClass+'">'+list.name+'</button>')
 				.children('#'+list.id)
 				.click( selectList );
 		});
 	}

 	var selectBoard = function( event ) {
 		event.preventDefault();
 
 		var newID = jQuery(this).attr('id')
 		//rerender list selector
 
 		if( $boardInput.val() !== newID ) {
 			console.log('test');
 			$listSelector.html('Loading...');
 			$listInput.val('');
 			Trello.get('/boards/'+newID+'/lists',{filter:'open'}).done(renderLists);
 		}

 		$boardSelector.children('button.button').removeClass('active');
 		jQuery(this).addClass('active');
 		$boardInput.val( newID );

 	}

 	var selectList = function( event ) {
 		event.preventDefault();
 		$listSelector.children('button.button').removeClass('active');
 		jQuery(this).addClass('active');
 		$listInput.val( jQuery(this).attr('id') );
 	}

 	var authorizationFailure = function() {
 		console.log(c2trello_settings_content.not_authorized);
 		$controllers.text(c2trello_settings_content.not_connected);
 	}

 	var authorizationStart = function() {

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

 	var renderLoaders = function() {

 		$APIcontrollers.text(c2trello_settings_content.loading);

 	}

 	var renderNotAuthorized = function() {
 		$APIcontrollers.text('Not authorized.');
 		renderNotification('Not authorized');
 	}

 	var renderNotification = function( content ) {
 		$notice.html(Notification(content));
 	}

 	var Notification = function( content ) {
 		return (
 			'<div id="setting-error-trello_not_authorized" class="error">'
 				+'<p>'
 					+'<strong>'+content+'</strong>'
 				+'</p>'
 			+'</div>'
 		);		
 	}

 	//public
 	this.setup = function() {

 		renderLoaders();

		authorizationStart();

 	}

 }