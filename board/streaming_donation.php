<?php
session_start();
$db = include('../dbconnect.php');
$roomid = $_GET['roomid'];
$userid = $_SESSION['user_id'];

$sql = "select * from member where mem_userid='$roomid'";
$result = $db->query($sql);
$receiver_address = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $receiver_address = $row['mem_ether_address'];
    }
}

$sql = "select * from member where mem_userid='$userid'";
$result = $db->query($sql);
$sender_address = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sender_address = $row['mem_ether_address'];
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Peanut Community Donation</title>
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
        max-width: 500px;
    }
    .make-center{
        margin: auto;
        max-width: 500px;
        overflow: auto;
        padding: 0 20px;
        color: #fff;
    }
    .input-area{
        margin-bottom: 20px;
    }


</style>
<body>
<div id="progressbar" class="loader loader--style1" title="0" style="display: none;">
    <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         width="40px" height="40px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
  <path opacity="0.2" fill="#000" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
    s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
    c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
        <path fill="#000" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
    C22.32,8.481,24.301,9.057,26.013,10.047z">
            <animateTransform attributeType="xml"
                              attributeName="transform"
                              type="rotate"
                              from="0 20 20"
                              to="360 20 20"
                              dur="0.5s"
                              repeatCount="indefinite"/>
        </path>
  </svg>
</div>
<section class="make-center">
    <header>
        <h1>Peanut Community Donation</h1>
    </header>
    <div class="input-area">
        <div style="display: flex">
            <div>이더리움 : &nbsp;</div>
            <div id="etherBalance"></div>
            <div>&nbsp;ETH</div>
            <div>&nbsp;<button onclick="getBalance()">조회</button></div>
        </div>


    </div>
    <div class="input-area">
        Private Key<br>
        <input type="password" id="privateKey">
<!--        <p style="color: #818181;">해당 사이트에서는 Private Key 를 저장하지 않습니다.</p>-->
    </div>
    <div class="input-area">
        후원 메시지<br>
        <input type="text" id="doneMessage">
    </div>
    <div class="input-area" >
        후원하는 이더리움 수<br>
        <input type="text" id="sendValue">
        <button id="sendButton" onclick="sendEther()">보내기</button>
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
    var sender_address = '<?php echo $sender_address ?>';
    var receiver_address = '<?php echo $receiver_address ?>';
    var current_value = 0;

    function getBalance(){

        if(sender_address != ''){
            $.ajax({
                url:"https://192.168.145.128:4001/getBalance",
                type:"POST",
                data:{sender_address:sender_address},
                datatype:"html",
                success:function(data){
                    // 성공시 html 영역 바꿔주기
                    if(data != null || data != ""){
                        current_value = data.result;
                        document.getElementById('etherBalance').innerText = data.result;
                    } else {
                        alert("오류 발생 : 지갑 주소를 확인해주세요.");
                    }
                }
            });
        } else {
            alert("지갑 주소를 확인해주세요.");
        }
    }

    function sendEther(){

        var sender_name = '<?php echo $userid?>';
        var receiver_name = '<?php echo $roomid?>';
        var private_key = document.getElementById('privateKey').value;
        var done_message = document.getElementById('doneMessage').value;
        var send_value = document.getElementById('sendValue').value;
        // send_value 16진수로 변환
        var hexa_str = send_value.toString(16);

        if(sender_address != '' && receiver_address != ''){

            if(current_value < send_value)
            {
                alert("이더 잔액이 부족합니다.");
                return;
            }
            // 버튼 비활성화
            // var btn = document.getElementById('sendButton');
            // btn.disabled = true;

            // 프로그레스바 활성화
            // var test = $("#progressbar");
            // test.fadeIn(1);

            $.ajax({
                url:"https://192.168.145.128:4001/sendEther",
                type:"POST",
                data:{
                    sender_address : sender_address,
                    receiver_address : receiver_address,
                    send_value : hexa_str,
                    private_key : private_key,
                    done_message : done_message
                },
                datatype:"html",
                success:function(data){
                    // 성공시 html 영역 바꿔주기
                    if(data.message == "200 ok"){
                        alert("성공적으로 이더리움을 전송하였습니다.");

                        // 채팅 서버에 알려주기
                        var socket = io.connect(':3100', {secure: true});
                        socket.emit('joinRoom', {roomName: '<?php echo $roomid?>'});
                        socket.emit('newUser', '후원');

                        var message = sender_name + "님이 " + receiver_name + "님에게 " +
                            send_value + "이더를 후원하셨습니다.";
                        // 서버로 message 이벤트 전달 + 데이터와 함께
                        socket.emit("reqMsg", {type: 'donation', message: message});
                        socket.emit("reqDonation", {send_value: send_value, done_message: done_message});

                        // self.close();
                    } else {
                        alert("오류 발생 : 지갑 주소를 확인해주세요.");
                    }
                }
            });
            // test.fadeOut(1);
            // btn.disabled = false;
        } else {
            alert("지갑 주소를 확인해주세요.");
        }
    }

    var roomuserid = '<?php echo $roomid ?>';
    var userid = '<?php echo $userid ?>';


</script>

<footer>
    <small id="send-message"></small>
</footer>

<script src="https://www.webrtc-experiment.com/common.js"></script>
</body>
</html>
