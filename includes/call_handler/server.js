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
app.get('/', function (request, response) {
    response.sendFile(__dirname + '/index.html');
});

// listen to specified port
http.listen(8080, function () {
    log.info('Call server started');
});

