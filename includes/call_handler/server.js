var express = require('express');
var app = new express();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var path = require('path');
var Log = require('log');
var log = new Log('debug');

// directories to place files
app.use(express.static(__dirname + '/assets'));
app.use(express.static(__dirname + '/js'));
app.use(express.static(__dirname + '/files'));

// return call options upon request by client
app.get('/initiate?', function (request, response) {
    var intiator_key = request.query.initiator_crypto;
    var receiver_key = request.query.receiver_crypto;

    console.log(intiator_key + " " + receiver_key);
    response.sendFile(__dirname + '/index.html');
});

// listen to specified port
var port = 7880;
http.listen(port, function () {
    log.info('Call server started port ' + port);
});

