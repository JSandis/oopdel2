$(function() {

	$('#message').hide();

	$('#player-form').submit(function(){
		var player_name = $('#player-name').val();
		var player_type = $('#player-type').val();

		$.ajax({
			url: 'start.php',
			dataType: 'json',
			data: {
				player_name: player_name,
				player_class: player_type
			},
			success: function(data) {
				$('#message').show();
				$('#message').html('<h3>Player created: <h3>');

				$.each(data, function(index, key) {
					$('#message').append('<p>'+index+': '+key+'</p><br>');
				});
				offer_challenge();
			},
			error: function(data) {
				console.log('Error starting game', data, data.responseText);
			}
		});

		$('#player-form').hide();
		return false;
	});

	function offer_challenge() {
		$.ajax({
			url: 'challenge.php',
			dataType: 'json',
			data: {
				offer_challenge: 1
			},
			success: function(data) {
				$('#message').show();
				$('#message').append('<h3>Challenge<h3>');
				$('#message').append('<h4>'+data['name']+'</h4><p>'+data['description']+'</p><br>');
				$('#message').append('<p class="italic">You can accept the challenge or get a new random challenge. It will cost you 5 success points to get a new challenge</p><br>');
				$('#message').append('<button id="accept-challenge">Accept</button>');
				$('#message').append('<button id="new-challenge">New Challenge</button>');
			},
			error: function(data) {
				console.log('Error offering challenge', data, data.responseText);
			}
		});
	}

	$('body').on('click', '#accept-challenge', function() {
		//since there must be a method called acceptChallenge
		$('#message').html('<p class="italic">You accepted the challenge!</p><br>');
		winTool();
	});

	function winTool() {
		$.ajax({
      		url:'tool.php',
      		dataType: 'json',
      		data: {
        		win_tool: 1
      		},
	      	success: function(data) {
	      		$('#message').append('<h4>Tools</h4>');
	      		if (data !== null && typeof data === 'object') {
		      		$.each(data, function(index, key) {
						$('#message').append('<p>'+index+': '+key+'</p><br>');
					});	
	      		} else {
	      			$('#message').append('<p>'+data+'</p><br>');
	      		}
				$('#message').append('<button id="show-opponents">Show opponents</button');
				},
	  		error: function(data) {
	  			console.log('Error win tool', data, data.responseText);
	      	}
		});
	}

	$('body').on('click', '#change-challenge', function() {
		$.ajax({
      		url:'challenge.php',
      		dataType: 'json',
      		data: {
        		change_challenge: 1
      		},
	      	success: function(data) {
	      		$('#message').html('<p class="italic">Getting a new challenge cost you 5 success points.</p><br>');
	      		$('#message').append('<h3>New challenge:</h3>');
	      		$('#message').append('<h4>'+data['name']+'</h4><p>'+data['description']+'</p><br>');
				$('#message').append('<p class="italic">You can accept the challenge or get another random challenge. It will cost you 5 success points to change challenge</p><br>');
				$('#message').append('<button id="accept-challenge">Accept</button>');
				$('#message').append('<button id="change-challenge">Change Challenge</button>');
 			},
	  		error: function(data) {
	  			console.log('Error change challenge', data, data.responseText);
	      	}
		});
	});

	$('body').on('click', '#show-opponents', function() {
		$.ajax({
      		url:'game.php',
      		dataType: 'json',
      		data: {
        		get_opponents: 1
      		},
	      	success: function(data) {
	      	
	      		if (data !== null && $.type(data) === 'array') {	      	
	      			$('#message').html('<h3>Opponents</h3>');
	      			for (var i = 0; i < data.length; i++) {
	      				$('#message').append('<h4>'+data[i].name+'</h4>');
			      		$.each(data[i].info, function(index, key) {
							$('#message').append('<p>'+index+': '+key+'</p><br>');
						});
	      			}

	      			$('#message').append('<p class="italic">You can choose to do the challenge alone or team up with one of your opponents. It will cost you 5 success points to do it in a team but doing it alone may increase the risk of loosing.</p><br>');
	      			$('#message').append('<button id="do-challenge-alone">Do challenge alone</button>');
	      			$('#message').append('<button id="team-up">Team up</button>');

	      		} else {
	      			$('#message').html('<p>'+data+'</p><br>');
	      		}
			},
	  		error: function(data) {
	  			console.log('Error win tool', data, data.responseText);
	      	}
		});
	});

	$('body').on('click', '#do-challenge-alone', function() {
		$('#message').html('');
		$.ajax({
			url: 'challenge.php',
			dataType: 'json',
			data: {
			  	do_challenge_alone: 1
			},
			success: function(data) {
			  	$('#message').append('<p>'+data+'</p><br>');
			  	current_standings();
			},
			error: function(data) {
			  console.log('Error do challenge alone:', data, data.responseText);
			}
      });
	});

	$('body').on('click', '#team-up', function() {
		$('#message').html('');
		$.ajax({
			url: 'challenge.php',
			dataType: 'json',
			data: {
			  	team_up: 1
			},
			success: function(data) {
			  	$('#message').append('<p>'+data+'</p><br>');
			  	current_standings();
			},
			error: function(data) {
			  console.log('Error team up:', data, data.responseText);
			}
      });
	});

	function current_standings() {
		$.ajax({
	        url: 'game.php',
	        dataType: 'json',
	        data: {
	          	current_standings: 1
	        },
	        success: function(data) {
	          	$('#message').append('<h3>Current standings:</h3>');
	          	if (data !== null && $.type(data) === 'array') {
	          		for (var i = 0; i < data.length; i++) {
	      				$('#message').append('<h4>'+data[i].name+'</h4>');
	      				if ($.type(data[i].info) === 'object') {
	      					$.each(data[i].info, function(index, key) {
								$('#message').append('<p>'+index+': '+key+'</p><br>');
							});
			      		} else {
			      			$('#message').append('<p>'+data[i].info+'</p><br>');
	      					if (data[i].name === 'GAME OVER') {
	      						$('#message').append('<h4>Restart the game by clicking the link in the menu!</h4>');
	      					} else {
	      						$('#message').append('<button id="next-challenge">Next challenge</button>');
	      					}
			      		}
	      			}
	          	} else {
      				$('#message').append('<p>'+data[i]+'</p><br>');
      			}
	        },
	        error: function(data) {
	          console.log('Error current standings:', data, data.responseText);
	        }
      	});
	}

	$('body').on('click', '#next-challenge', function() {
		$('#message').html('');
		offer_challenge();
	});

	$('#restart').click(function() {
		$.ajax({
      		url:'restart.php',
      		dataType: 'json',
      		data: {
        		restart: 1
      		},
	      	success: function(data) {
	      		$('#player-form').show();
	          	$('#message').hide();
	          	$('#header-message').html('<p>The game was restarted<p>');
	      	},
	  		error: function(data) {
	  			console.log('Error restarting game', data, data.responseText);
	      	}
		});
	});
});