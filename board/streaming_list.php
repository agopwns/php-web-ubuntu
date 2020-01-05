<?php
    session_start();
    $db = include('../dbconnect.php');

// 게시판 카테고리명 가져오기
$board_name = $_GET['bName'];
$board_name = str_replace('%20' , '', $board_name);
//echo $board_name ."<br>";//xptmxm

$user_id = $_SESSION['user_id'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Welcome to the best Community">
    <meta name="keywords" content="Peanut Community">
    <link rel="stylesheet" href="../css/img_board_list.css">
    <title>Peanut Community</title>
    <script src="../RTC/demos/js/jquery-3.3.1.slim.min.js"></script>
    <script src="../RTC/demos/js/popper.min.js"></script>
    <script src="../RTC/demos/js/bootstrap.min.js"></script>
    <script src="../RTC/socket.io/socket.io.js"></script>
    <script src="../RTC/dist/RTCMultiConnection.min.js"></script>
</head>
<body>
<div class="parent_container">
    <div id="headers"><?php include '../include/include_header.php'?></div>


    <!-- Page Content -->
    <div class="container page-top" style="margin-top: 40px">
        <h2 style="color:#fff;">방송</h2>
        <button id="btn-create-room" style="float:right; color:#fff;">방송 시작</button>
        <div class="table-div" style="display: flex; margin-bottom: 20px;">
            <div class="table-div-child" style=" width: 780px;">

            </div>
        </div>
        <div class="row" style="display: block">

        </div>
    </div>
</div>


</body>
<script>
    // this object is used to get uniquie rooms based on this demo
    // i.e. only those rooms that are created on this page
    var publicRoomIdentifier = 'dashboard';

    var connection = new RTCMultiConnection();

    //connection.socketURL = '/';
    connection.socketURL = 'https://192.168.145.128:9001/';
     // connection.socketURL = 'https://rtcmulticonnection.herokuapp.com:443/';

    /// make this room public
    connection.publicRoomIdentifier = publicRoomIdentifier;
    connection.socketMessageEvent = publicRoomIdentifier;

    // keep room opened even if owner leaves
    connection.autoCloseEntireSession = true;

    connection.connectSocket(function(socket) {
        //looper();

        socket.on('disconnect', function() {
            location.reload();
        });
    });

    $('#btn-create-room').click(function() {
        // var roomid = $('#txt-roomid').val().toString();
        var roomid = '1234';

        connection.extra.userFullName = '<?php echo $user_id?>';

        connection.checkPresence(roomid, function(isRoomExist) {
            if (isRoomExist === true) {
                alert('이미 존재하는 방 번호 입니다.');
                return;
            }
            connection.sessionid = roomid;
            connection.isInitiator = true;
            openCanvasDesigner();
        });
    });

    function openCanvasDesigner() {
        var href = 'streaming_view.php?open=' + connection.isInitiator
            + '&sessionid=' + connection.sessionid
            + '&publicRoomIdentifier=' + connection.publicRoomIdentifier
            + '&userFullName=' + connection.extra.userFullName;
        window.open(href);
    }


</script>
</html>