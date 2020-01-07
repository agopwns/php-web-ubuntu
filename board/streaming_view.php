<?php
session_start();
$roomid = $_GET['roomid'];
$title = $_GET['title'];
$userid = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Peanut Community Streaming</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <script src="../RTC/demos/menu.js"></script>
    <script src="../RTC/node_modules/webrtc-adapter/out/adapter.js"></script>
<!--    <script src="../RTC/dist/RTCMultiConnection.min.js"></script>-->
    <script src="../RTC/dist/RTCMultiConnection.js"></script>

</head>
<style>
    *{
        background-color: #292929;
        color: #fff;
    }
    .make-center{
        margin: auto;
        max-width: 1100px;
        overflow: auto;
        padding: 0 20px;
        color: #fff;
    }
    #video-chat-container{
        display: flex;
    }
    /* 채팅 영역 */
    #chat {
        background-color: #373737;
        overflow-y: auto;
    }
</style>
<body>

<section class="make-center">
    <header>
        <h1>Peanut Community Streaming</h1>
        <h2>방제 : 드루와</h2>
    </header>
    <div id="video-chat-container" style="width: 1100px; height: 900px;">
        <div id="videos-container" style="width: width:840px; height:700px;"></div>
        <div id="main" style="width:260px; height: 470px;">
            <div id="chat" style="width:260px ;height:470px;">
                <!-- 채팅 메시지 영역 -->
            </div>
            <div>
                <input type="text" id="test" style="color:#fff; width: 200px;" placeholder="메시지를 입력해주세요..">
                <button id="btn-chat-message" style="color:#fff; width: 50px;">전송</button>
            </div>
        </div>
    </div>
</section>

<!--<script src="../RTC/dist/RTCMultiConnection.js"></script>-->
<script src="../RTC/node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com/socket.io/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="../RTC/demos/js/jquery-3.3.1.slim.min.js"></script>
<!-- custom layout for HTML5 audio/video elements -->
<link rel="stylesheet" href="../RTC/dev/getHTMLMediaElement.css">
<script src="../RTC/dev/getHTMLMediaElement.js"></script>
<script>
    var roomuserid = '<?php echo $roomid ?>';
    var userid = '<?php echo $userid ?>';
    // ......................................................
    // .......................UI Code........................
    // ......................................................
    $(document).ready( function() {
        var isRoomExist = false;
        if(roomuserid !== userid){
            connection.sdpConstraints.mandatory = {
                OfferToReceiveAudio: true,
                OfferToReceiveVideo: true
            };
            connection.join(roomuserid);
        } else {
            // if room doesn't exist, it means that current user will create the room
            connection.open(roomuserid, function() {
                // updateRoomid(roomid);
            });
        }
    });

    function updateRoomid(roomid){
        $.ajax({
            url:"./streaming_update.php",
            type:"GET",
            data:{roomid:roomid},
            datatype:"html",
            success:function(data){}
        });
    }

    var connection = new RTCMultiConnection();
    connection.socketURL = 'https://192.168.145.128:9001/';
    connection.sessionid = roomuserid;
    connection.extra.userFullName = userid;
    // connection.socketMessageEvent = 'video-broadcast-demo';
    connection.socketMessageEvent = 'canvas-dashboard-demo';
    var publicRoomIdentifier = 'dashboard';
    connection.publicRoomIdentifier = publicRoomIdentifier;
    connection.socketMessageEvent = publicRoomIdentifier;
    // keep room opened even if owner leaves
    connection.autoCloseEntireSession = true;
    // https://www.rtcmulticonnection.org/docs/maxParticipantsAllowed/
    connection.maxParticipantsAllowed = 1000;

    connection.session = {
        audio: true,
        video: true,
        oneway: true
    };
    connection.sdpConstraints.mandatory = {
        OfferToReceiveAudio: false,
        OfferToReceiveVideo: false
    };
    // https://www.rtcmulticonnection.org/docs/iceServers/
    // use your own TURN-server here!
    connection.iceServers = [{
        'urls': [
            'stun:stun.l.google.com:19302',
            'stun:stun1.l.google.com:19302',
            'stun:stun2.l.google.com:19302',
            'stun:stun.l.google.com:19302?transport=udp',
        ]
    }];
    connection.onmessage = function(event) {
        if (event.data.chatMessage) {
            appendChatMessage(event);
            return;
        }
        if (event.data.checkmark === 'received') {
            var checkmarkElement = document.getElementById(event.data.checkmark_id);
            if (checkmarkElement) {
                checkmarkElement.style.display = 'inline';
            }
            return;
        }
    };
    connection.videosContainer = document.getElementById('videos-container');
    connection.onstream = function(event) {
        var existing = document.getElementById(event.streamid);
        if(existing && existing.parentNode) {
            existing.parentNode.removeChild(existing);
        }
        event.mediaElement.removeAttribute('src');
        event.mediaElement.removeAttribute('srcObject');
        event.mediaElement.muted = true;
        event.mediaElement.volume = 0;
        var video = document.createElement('video');

        try {
            video.setAttributeNode(document.createAttribute('autoplay'));
            video.setAttributeNode(document.createAttribute('playsinline'));
        } catch (e) {
            video.setAttribute('autoplay', true);
            video.setAttribute('playsinline', true);
        }

        if(event.type === 'local') {
            video.volume = 0;
            try {
                video.setAttributeNode(document.createAttribute('muted'));
            } catch (e) {
                video.setAttribute('muted', true);
            }
        }
        video.srcObject = event.stream;

        // var width = parseInt(connection.videosContainer.clientWidth) - 20;
        var width = 650;
        var mediaElement = getHTMLMediaElement(video, {
            title: event.userid,
            buttons: ['full-screen'],
            width: width,
            showOnMouseEnter: false
        });

        connection.videosContainer.appendChild(mediaElement);

        setTimeout(function() {
            mediaElement.media.play();
        }, 5000);

        mediaElement.id = event.streamid;
    };

    connection.onstreamended = function(event) {
        var mediaElement = document.getElementById(event.streamid);
        if (mediaElement) {
            mediaElement.parentNode.removeChild(mediaElement);

            if(event.userid === connection.sessionid && !connection.isInitiator) {
                alert('Broadcast is ended. We will reload this page to clear the cache.');
                location.reload();
            }
        }
    };

    connection.onMediaError = function(e) {
        if (e.message === 'Concurrent mic process limit.') {
            if (DetectRTC.audioInputDevices.length <= 1) {
                alert('Please select external microphone. Check github issue number 483.');
                return;
            }

            var secondaryMic = DetectRTC.audioInputDevices[1].deviceId;
            connection.mediaConstraints.audio = {
                deviceId: secondaryMic
            };

            connection.join(connection.sessionid);
        }
    };
    // ......................................................
    // ......................Chat ...........................
    // ......................................................
    document.getElementById('btn-chat-message').onclick = function() {
        var chatMessage = document.getElementById('test').value;
        // $('#test').html('');

        if (!chatMessage || !chatMessage.replace(/ /g, '').length) return;

        var checkmark_id = connection.userid + connection.token();

        appendChatMessage(chatMessage, checkmark_id);

        connection.send({
            chatMessage: chatMessage,
            checkmark_id: checkmark_id
        });

        connection.send({
            typing: false
        });
    };
    var conversationPanel = document.getElementById('chat');

    function appendChatMessage(event, checkmark_id) {
        var div = document.createElement('div');

        div.className = 'message';

        if (event.data) {
            div.innerHTML = '<b>' + (event.extra.userFullName || event.userid) + ':</b><br>' + event.data.chatMessage;

            if (event.data.checkmark_id) {
                connection.send({
                    checkmark: 'received',
                    checkmark_id: event.data.checkmark_id
                });
            }
        } else {
            div.innerHTML = '<b>You:</b><br>' + event;
            div.style.background = '#cbffcb';
        }

        conversationPanel.appendChild(div);

        conversationPanel.scrollTop = conversationPanel.clientHeight;
        conversationPanel.scrollTop = conversationPanel.scrollHeight - conversationPanel.scrollTop;
    }
    // ......................................................
    // ......................Handling Room-ID................
    // ......................................................

    (function() {
        var params = {},
            r = /([^&=]+)=?([^&]*)/g;

        function d(s) {
            return decodeURIComponent(s.replace(/\+/g, ' '));
        }
        var match, search = window.location.search;
        while (match = r.exec(search.substring(1)))
            params[d(match[1])] = d(match[2]);
        window.params = params;
    })();

    var roomid = '';
    if (localStorage.getItem(connection.socketMessageEvent)) {
        roomid = localStorage.getItem(connection.socketMessageEvent);
    } else {
        roomid = connection.token();
    }


    // detect 2G
    if(navigator.connection &&
        navigator.connection.type === 'cellular' &&
        navigator.connection.downlinkMax <= 0.115) {
        alert('2G is not supported. Please use a better internet service.');
    }
</script>

<footer>
    <small id="send-message"></small>
</footer>

<script src="https://www.webrtc-experiment.com/common.js"></script>
</body>
</html>
