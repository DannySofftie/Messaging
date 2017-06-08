var express = require( 'express' );
var app = express( );
var ExpressPeerServer = require( 'peer' ).ExpressPeerServer;
var Log = require( 'log' );
var log = new Log( 'debug' );
var fs = require( 'fs' )
var cors = require( 'cors' )
var mysql = require( 'mysql' )
var connection = mysql.createConnection( {
	host: '127.0.0.1',
	user: 'root',
	password: '25812345Dan',
	database: 'dmclient'
} )
connection.connect( )
// directories to place files
app.use( express.static( __dirname + '/public' ) )
app.use( express.static( __dirname + '/' ) )
app.use( cors( ) ) 
var filename = ''

// return call options upon request by client
app.get( '/initiate?', function ( request, response ) {
	
	// obtain the specified keys from the URL
	var intiator_key = request.query.initiator_crypto
	var receiver_key = request.query.receiver_crypto
	var curr_userid = request.query.curr_userID
	var friend_id = request.query.friend_id
	/*
        JSON CREATE FILE ********** , only one record should exist for every user which will be used to initialize connection between any two users
         at any time. After create file, the same data will be utilized and the result set will be used to make Peer Connection on the client
    */
	filename = friend_id + '_' + curr_userid + '.json'
	
	var data = {
		'curr_userID' : curr_userid,
		'intiator_key' : intiator_key,
		'friend_id' : friend_id,
		'receiver_key' : receiver_key
	}
	// convert to JSON
	data = JSON.stringify( data )
	
	fs.writeFile( './jsonfiles/' + filename , data , function ( err ) {
		if ( err ) throw err
		console.log( filename + " has been created." )
	} )
	
	// insert into database these details
	var callData = {
		'initiator_id' : curr_userid,
		'initiator_key' : intiator_key,
		'receiver_id' : friend_id,
		'receiver_key' : receiver_key
	}
	
	connection.query( 'insert into call_handler set ?' , callData , function ( err , result ) {
		if ( err ) {
			console.log( "Action failed " + err )
		}
		else {
			console.log( "Success call data insert." );
		}
	} )
	
	// respond with index.html file
	response.sendFile( __dirname + '/index.html' )
	
	// print the keys on every request
	console.log( curr_userid + " to " + friend_id + " and keys: " + intiator_key + " " + receiver_key )

} );

app.get( '/checkCallStatus?' , function ( request , response ) {
	
	var idCheck = request.query.user_id
	var call_start_time = request.query.call_start_time
	
	var sqlQuery = "select * from call_handler where receiver_id = " + idCheck + " and call_status=0 and call_start_time >= date_sub(now(),interval 1 minute)";
	// write to database these keys	
	connection.query( sqlQuery , function ( err, results ) {
		if ( err ) {
			console.log( "An error occured " + err )
			console.log( sqlQuery )
		}
		if ( results.length === 0 ) {
			
			// signal the call listener to continue making requests HTTP_STATUS_CODE 200
			console.log( 'Receiving requests for user ' + idCheck + ' for time ' + call_start_time )
			response.send( 'continue' )

		} else {
			
			// signal the call listener that a match is found HTTP_STATUS_CODE 302
			response.send( 'found' )
		}
	} )
} )

app.get( '/', function ( request, response ) {
	response.sendFile( __dirname + '/files/error.html' );
} )

var port = process.env.PORT || 7880

var server = app.listen( port, function () {
	console.log( 'Listening....' + port )
} );

var options = {
	debug: true
}

peerServer = ExpressPeerServer( server, options )

app.use( '/peerjs', peerServer )

peerServer.on( 'connection', function ( id ) {
	console.log( 'Connected ' + id )
	//console.log(server._clients)
} )

peerServer.on( 'disconnect', function ( id ) {
	console.log( id + " has been disconnected" )
} )

