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
    /* 내가 보낸 메시지 */
    .me {
        width: 90%;
        margin: auto;
        text-align: left;
        color: lightcoral;
        background-color: #373737;
        border-radius: 5px;
        margin-top: 10px;
    }

    /* 상대방이 보낸 메시지 */
    .other {
        width: 90%;
        margin: auto;
        text-align: left;
        color: white;
        background-color: #373737;
        border-radius: 5px;
        margin-top: 10px;
    }

    /* 후원 메시지 */
    .donation {
        width: 90%;
        margin: auto;
        text-align: left;
        color: white;
        background-color: #bb6ed0;
        border-radius: 5px;
        margin-top: 10px;
    }

    #donaNoti{
        display:none;  /* 평상시에는 서브메뉴가 안보이게 하기 */
        height:auto;
        padding:0px;
        margin:0px;
        margin-top: 60px;
        border:0px;
        font-size: 12px;
        position:fixed;
        top: 100px;
        left: 200px;
        width:300px;
        height: 100px;
        z-index:201;
        background-color: azure;
    }

</style>
<body>

<section class="make-center">
    <header>
        <h1>Peanut Community Streaming</h1>
        <h2>방제 : 드루와</h2>
    </header>
    <div id="donaNoti">
        <div style="margin: 0 auto;">
            <img src="../img/ethericon.png" style="width: 80px;opacity: 1;margin-left:100px;">
        </div>
        <div id="donaMessageArea" style="margin: 0 auto; opacity: 1;">

        </div>
    </div>
    <div id="video-chat-container" style="width: 1100px; height: 900px;">
        <div id="videos-container" style="width: width:840px; height:700px;"></div>
        <div id="main" style="width:260px; height: 470px;">
            <div id="chat" style="width:260px ;height:470px;">
                <!-- 채팅 메시지 영역 -->
            </div>
            <div>
                <input type="text" id="inputText" style="color:#fff; width: 200px;" placeholder="메시지를 입력해주세요..">
                <button id="btn-chat-message" style="color:#fff; width: 50px;" onclick="myOnClick()">전송</button>
                <?php
                if($roomid != $userid){
                    ?>
                    <button id="btn-chat-message" style="color:#fff; width: 50px;" onclick="openWin()">후원</button>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!--<script src="../RTC/dist/RTCMultiConnection.js"></script>-->
<script src="../RTC/node_modules/webrtc-adapter/out/adapter.js"></script>
<script src="https://rtcmulticonnection.herokuapp.com/socket.io/socket.io.js"></script>
<script src="../node/node_modules/socket.io-client/dist/socket.io.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js">
    <script src="../RTC/demos/js/jquery-3.3.1.slim.min.js"></script>
<!-- custom layout for HTML5 audio/video elements -->
<link rel="stylesheet" href="../RTC/dev/getHTMLMediaElement.css">
<script src="../RTC/dev/getHTMLMediaElement.js"></script>

<script>
    var roomuserid = '<?php echo $roomid ?>';
    var userid = '<?php echo $userid ?>';

    function openWin(){
        window.open("./streaming_donation.php?roomid=" + roomuserid, "후원하기", "width=500, height=400" +
            ", toolbar=no, menubar=no, scrollbars=no, resizable=yes" );
    }


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
    // connection.onmessage = function(event) {
    //     if (event.data.chatMessage) {
    //         appendChatMessage(event);
    //         return;
    //     }
    //     if (event.data.checkmark === 'received') {
    //         var checkmarkElement = document.getElementById(event.data.checkmark_id);
    //         if (checkmarkElement) {
    //             checkmarkElement.style.display = 'inline';
    //         }
    //         return;
    //     }
    // };
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

    // ......................................................
    // ......................Chat ...........................
    // ......................................................

    var socket = io.connect(':3100', {secure: true});

    socket.emit('joinRoom', {roomName: '<?php echo $roomid?>'});
    socket.emit('newUser', '<?php echo $userid?>');

    socket.on('recMsg', function (data) {
        console.log(data.comment);
        $('#chat').append(data.comment);
    });

    socket.on('update', function(data){
        var chat = document.getElementById('chat');

        var message = document.createElement('div');
        var node = document.createTextNode(`${data.name}: ${data.message}`);
        var className = '';

        // 타입에 따라 적용할 클래스를 다르게 지정
        switch(data.type) {
            case 'message':
                className = 'other';
                break;
            case 'connect':
                className = 'connect';
                break;
            case 'disconnect':
                className = 'disconnect';
                break;
            case 'donation':
                className = 'donation';
                break;
        }
        message.classList.add(className);
        message.appendChild(node);
        chat.appendChild(message);
    });

    socket.on('donepopup', function(data){
        var done_message = `${data.done_message}`;
        var send_value = `${data.send_value}`;

        var addText = done_message;
        $("#donaMessageArea").append(addText);
        speech(addText); // tts
        var test = $("#donaNoti");
        test.fadeOut(1);

        test.fadeIn(2000);
        var list = document.getElementById("donaNoti");
        setTimeout(function(){ test.fadeOut(4000); },5000);
        setTimeout(function(){ list.removeChild(list.childNodes[0]); },7500);

    });

    var voices = [];
    function setVoiceList() {
        voices = window.speechSynthesis.getVoices();
    }
    setVoiceList();
    if (window.speechSynthesis.onvoiceschanged !== undefined) {
        window.speechSynthesis.onvoiceschanged = setVoiceList;
    }
    function speech(txt) {
        if(!window.speechSynthesis) {
            alert("음성 재생을 지원하지 않는 브라우저입니다. 크롬, 파이어폭스 등의 최신 브라우저를 이용하세요");
            return;
        }
        var lang = 'ko-KR';
        var utterThis = new SpeechSynthesisUtterance(txt);
        utterThis.onend = function (event) {
            console.log('end');
        };
        utterThis.onerror = function(event) {
            console.log('error', event);
        };
        var voiceFound = false;
        for(var i = 0; i < voices.length ; i++) {
            if(voices[i].lang.indexOf(lang) >= 0 || voices[i].lang.indexOf(lang.replace('-', '_')) >= 0) {
                utterThis.voice = voices[i];
                voiceFound = true;
            }
        }
        if(!voiceFound) {
            alert('voice not found');
            return;
        }
        utterThis.lang = lang;
        utterThis.pitch = 1;
        utterThis.rate = 1; //속도
        window.speechSynthesis.speak(utterThis);
    }

    function myOnClick() {
        // 입력되어있는 데이터 가져오기
        var message = document.getElementById('inputText').value;

        // 가져왔으니 데이터 빈칸으로 변경
        document.getElementById('inputText').value = '';

        // 내가 전송할 메시지 클라이언트에게 표시
        var chat = document.getElementById('chat');
        var msg = document.createElement('div');
        var node = document.createTextNode("나: " + message);
        msg.classList.add('me');
        msg.appendChild(node);
        chat.appendChild(msg);

        // 서버로 message 이벤트 전달 + 데이터와 함께
        socket.emit("reqMsg", {type: 'message', message: message});
        $('#inputText').val('');
    }
</script>

<footer>
    <small id="send-message"></small>
</footer>

<script src="https://www.webrtc-experiment.com/common.js"></script>
</body>
</html>
