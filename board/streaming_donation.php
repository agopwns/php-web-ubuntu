<?php
session_start();
$db = include('../dbconnect.php');
$roomid = $_GET['roomid'];
$userid = $_SESSION['user_id'];

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
        <input type="text" id="privateKey">
<!--        <p style="color: #818181;">해당 사이트에서는 Private Key 를 저장하지 않습니다.</p>-->
    </div>
    <div class="input-area" id="doneMessage">
        후원 메시지<br>
        <input type="text">
    </div>
    <div class="input-area" id="sendValue">
        후원하는 이더리움 수<br>
        <input type="text">
        <button onclick="sendEther()">보내기</button>
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
    var roomuserid = '<?php echo $roomid ?>';
    var userid = '<?php echo $userid ?>';

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
