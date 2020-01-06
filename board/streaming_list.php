<?php
    session_start();
    $db = include('../dbconnect.php');
// 게시판 카테고리명 가져오기
$board_name = $_GET['bName'];
$board_name = str_replace('%20' , '', $board_name);
//echo $board_name ."<br>";//xptmxm
$user_id = $_SESSION['user_id'];

// 방송 검색
$sql = "select * from streaming WHERE stm_is_stream='Y' order by stm_index desc";
$result = $db->query($sql);
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
    <script src="https://rtcmulticonnection.herokuapp.com/socket.io/socket.io.js"></script>
    <script src="../RTC/dist/RTCMultiConnection.min.js"></script>
</head>
<style>
    * {
      color:#fff;
    }

</style>
<body>
<div class="parent_container">
    <div id="headers"><?php include '../include/include_header.php'?></div>


    <!-- Page Content -->
    <div class="container page-top" style="margin-top: 40px">
        <h2 style="color:#fff;">방송</h2>
        <button id="btn-create-room" style="float:right; color:#fff;">방송 시작</button>
        <div class="table" style="">
            <?php
            if ($result->num_rows > 0) {
                if (isset($emptyData)) {
                    echo '현재 방송중인 방이 존재하지 않습니다.';
                } else {
                    echo "<table>";
                    while ($row = $result->fetch_assoc()) {
                        $title = $row['stm_title'];
                        $roomid = $row['stm_roomid'];
                        echo "<tr style='height: 60px' onClick='location.href=\"./streaming_view.php?roomid=" . $roomid . "&title=" . $title . "\"' style='cursor:pointer'>";
                        echo "<td style='border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;'>$title</td>";
                        echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;'>$user_id</td>";
                        ?>
                        </tr>
                        <?php
                    }
                    echo "</table>";
                }
            }
            ?>
        </div>
    </div>
</div>


</body>
<script>
    // this object is used to get uniquie rooms based on this demo
    // i.e. only those rooms that are created on this page
    var publicRoomIdentifier = 'dashboard';

    var connection = new RTCMultiConnection();

    connection.socketURL = 'https://192.168.145.128:9001/';
    //connection.socketURL = '/';
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
        var roomtitle = prompt("방송 제목을 입력해주세요.");
        // var roomid = $('#txt-roomid').val().toString();
        connection.extra.userFullName = '<?php echo $user_id?>';
        var roomid = connection.extra.userFullName + '<?php rand(1,1000)?>';

        connection.checkPresence(roomid, function(isRoomExist) {
            if (isRoomExist === true) {
                alert('이미 존재하는 방 번호 입니다.');
                return;
            }
            connection.sessionid = roomid;
            connection.isInitiator = true;
            $.ajax({
                url:"./streaming_create.php",
                type:"GET",
                data:{title:roomtitle, roomid:roomid},
                datatype:"html",
                success:function(data){
                    document.location.href='streaming_view.php?roomid='+roomid+'&title='+roomtitle;
                }
            });
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