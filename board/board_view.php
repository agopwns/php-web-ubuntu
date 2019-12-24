<?php
    session_start();
    $db = include('../dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Welcome to the best Community">
    <meta name="keywords" content="Peanut Community">
    <link rel="stylesheet" href="../css/style.css">
    <title>Peanut Community</title>
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready( function() {
            $("#headers").load("../include/include_header.php");  // 원하는 파일 경로를 삽입하면 된다
        });
    </script>

</head>
<body>
<div class="parent_container">
    <!--  헤더  -->
    <div id="headers"></div>

    <!--  본문 영역  -->
    <?php
        $board_id = $_GET['bNO'];
        $sql = "SELECT * FROM board WHERE board_id='$board_id'";
        $result = $db->query($sql);

        if($db){
            // 값이 있을 경우
            if ($result->num_rows > 0) {

                while($row = mysqli_fetch_array($result)){
                    $userid = $row['board_userid'];
                    $title = $row['board_title'];
                    $viewCount = $row['board_view_count'];
                    $content = $row['board_content'];
                    $regtime = $row['board_regtime'];

                    echo "<nav id='navBest'>";
                    echo "<div class='title-container'>";
                    echo "<h3>$title</h3>";
                    echo "</div>";
                    echo "<div class='info-container'>";
                    echo "<div class='info-child'>$userid</div>";
                    echo "<div class='info-child'>$viewCount</div>";
                    echo "<div class='info-child'>$regtime</div>";
                    echo "</div>";
                    echo "<div class='content-container'>";
                    echo "<div class='info-child'>$content</div>";
                    echo "</div>";
                    echo "<div class='comment-container'>";
                    echo "</div>";
                    echo "</nav>";
                }
            }
            } else {
        }
    ?>
</div>


</body>
<script>


</script>
</html>