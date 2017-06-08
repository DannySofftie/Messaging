$(function () {

	// make ajax to request user a random generated key
	// get the url parameters to create a peer constructor
	function getParameterByName( name, url ) {
		if ( !url ) url = window.location.href;
		name = name.replace( /[\[\]]/g, "\\$&" );
		var regex = new RegExp( "[?&]" + name + "(=([^&#]*)|&|#|$)" ), results = regex.exec( url );
		if ( !results ) return null;
		if ( !results[2] ) return '';
		return decodeURIComponent( results[2].replace( /\+/g, " " ) );
	}
	
	var $initiator_id = getParameterByName( 'initiator_crypto' );
	var $receiver_id = getParameterByName( 'receiver_crypto' );
	
	var peer = new Peer( $initiator_id,
	{
		host: 'localhost',
		port: 9000,
		path: '/peerjs',
		debug: 3,
		config: {'iceServers': [
		{ url: 'stun:stun1.l.google.com:19302' },
		{ url: 'turn:numb.viagenie.ca',
		credential: 'muazkh', username: 'webrtc@live.com' }
		]}
	}
	)
	
	peer.on( 'open', function ( id ) {
		// make an AJAX request and store this id for use by other peer to connect
		console.log( "My peer id is " + id )
	} )
	
	peer.on( 'error', function ( err ) {
		// alert with the message
		$( '#error_list' ).text( err.message );
	} );
	
	navigator.getMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia

	var vendorURL = window.URL || window.webkitURL
	
	// called by default
	navigator.getMedia({video: true, audio: true},function ( stream ) {
		$( '#my_video' ).prop( 'src', vendorURL.createObjectURL( stream ) )
		$( '#their_video' ).prop( 'src', vendorURL.createObjectURL( stream ) )
	}, function ( err ) {
		alert( "Allow access to microphone and camera" )
	} )
	
	// try to connect to remote peer
	var conn = peer.connect($receiver_id);

	peer.on('connection',function(connection) {
		conn = connection
		$receiver_id = connection.peer
		console.log('Connected to ' + $receiver_id)
	});

	// obtain stream data
	function getVideo(callback){
		navigator.getUserMedia({audio: true, video: true}, callback, function(error){
			console.log(error);
			alert('An error occured. Please try again');
		});
	}

	getVideo(function(stream){
		window.localStream = stream;
		onReceiveStream(stream, 'my_video');
	});
	
	// function to handle calls(receiving and sending data stream)
	function onReceiveStream(stream, element_id){
		var video = $('#' + element_id );
		video.src = window.URL.createObjectURL(stream);
		window.peer_stream = stream;
	}
	
	// handle the call event
	$( '#video_call' ).click( function () {
		console.log( 'Starting call to ' + $receiver_id )
		console.log(peer);
		var call = peer.call($receiver_id, window.localStream);
		call.on('stream', function(stream){
			window.peer_stream = stream;
			onReceiveStream(stream, 'their_video');
		});
	} )
	
	// listen for any incoming calls
	peer.on('call', function(call){
		onReceiveCall(call);
	});

	function onReceiveCall(call){
		call.answer(window.localStream);
		call.on('stream', function(stream){
			window.peer_stream = stream;
			onReceiveStream(stream, 'their_video');
		});
	}

	// terminate connection when not in use
	peer.on( 'close', function () {
		peer.destroy( )
	} )
	
	// close this window on close click
	$( '#close_window' ).click( function () {
		window.close( );
	} )
})