$(function() {

	$('#message-box').hide();

	$('#player-form').submit(function(){
		var player_name = $('#player-name').val();
		var player_type = $('#player-type').val();
		var player_type_text = $("#player-type option:selected").text();
		$('#header-message-box').html('<p>Player with name '+player_name+' and type '+player_type_text+' was created.</p>');

		$('#player-form').hide();
		return false;
	});

	$('#restart-button').click(function() {
		$('#player-form').show();

		//ajax success
		$('#message-box').hide();
		$('#header-message-box').html('<p>The game was restarted<p>');
	});

});