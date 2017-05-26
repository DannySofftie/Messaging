
var express = require('express');
var app = express();
var ExpressPeerServer = require('peer').ExpressPeerServer;
var Log = require('log');
var log = new Log('debug');
var fs = require('fs')

// directories to place files
app.use(express.static(__dirname + '/public'));

// return call options upon request by client
app.get('/initiate?', function (request, response) {

    // obtain the specified keys from the URL
    var intiator_key = request.query.initiator_crypto;
    var receiver_key = request.query.receiver_crypto;
    var curr_userid = request.query.curr_userID;
    var friend_id = request.query.friend_id;
   
    /*
        JSON CREATE FILE ********** , only one record should exist for every user which will be used to initialize connection between any two users
         at any time. After create file, the same data will be utilized and the result set will be used to make Peer Connection on the client
    */
	var filename = friend_id + '_' + curr_userid + '.json'

	var data = {
			'curr_userID' : curr_userid,
			'intiator_key' : intiator_key,
			'friend_id' : friend_id,
			'receiver_key' : receiver_key
	}

	fs.writeFile(filename , data , function (err) {
		if (err) throw err
		console.log('file created')
	})
    // respond with index.html file
    response.sendFile(__dirname + '/index.html');

    // print the keys on every request
    console.log( curr_userid + " to  " + friend_id + " and keys: " + intiator_key + " " + receiver_key);

});

app.get('/requestUserData', function (request, response) {
    /*
        read the created json file for parameters
    */
    var danny = 'some gabbage'
    return response.json(danny)
});

app.get('/', function (request, response) {
    response.sendFile(__dirname + '/files/error.html');
})

var port = 7880

var server = app.listen(port, function () {
    console.log('Listening....' + port)
});

var options = {
    debug: true
}

peerServer = ExpressPeerServer(server, options)

app.use('/peerjs', peerServer);

peerServer.on('connection', function (id) {
    console.log(id)
    console.log(server._clients)
})

server.on('disconnect', function (id) {
    console.log(id + " disconnected")
})

