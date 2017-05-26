$(function () {
   
    var video = $('#video_holder');

    // extract url parameters by name to be used to initiate call
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    var $initiator_crypto = getParameterByName('initiator_crypto');
    var $receiver_crypto = getParameterByName('receiver_crypto');

    $('#audio_call').click(function () {
        // handle audio calling
        navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

        if (navigator.getUserMedia) {
            navigator.getUserMedia(
                { video: false, audio: true },
                function (stream) {
                    // stream audio to an audio tag
                    $('#audio_holder').attr('src', stream);
                    $('#logger').text('Microphone initialised [OK]')
                },
                function (error) {
                    $('#logger').text('Your microphone seems not to work');
                });
        }
    });

    $('#video_call').click(function () {
        // handle video calling
        navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);

        if (navigator.getUserMedia) {
            navigator.getUserMedia(
                { video: true, audio: true },
                function (stream) {

                    $('#logger').text("Camera and microphone intilialised [OK]");
                    video.atrr('src', vendorURL.createObjectURL(stream));

                },
            function (error) {
                $('#logger').text('Your camera is not probably connected. Allow this page access to camera by clicking allow');
            });
        }
    });

    // close this window on close click
    $('#close_window').click(function () {
        window.close();
    })
})