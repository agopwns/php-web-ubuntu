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
    <div id="headers"></div>

    <nav id="navBest">
        <div class="best-container">

            <table id = "boardTable" style=" width:95%;">
                <div class="table-div" style="display: flex; margin-bottom: 40px;">
                    <div class="table-div-child" style=" width: 1000px;">
                    <?php
                        $board_name = $_GET['page'];
                        $result = str_replace('%20' , '', $board_name);
                        echo "<a href='board_list.php' class='readHide' style='color:white; font-size: 20px; font-weight: bold; margin-bottom: 40px;'>$result 게시판</a>";
                        echo "</div>";
                        echo "<div class='table-div-child' style='width: 100px; float:right'>";
                        echo "<a href='board_write.php?page=$result'>";
                        echo "<input type='button' style='color:#fff;' value='글쓰기'></input>";
                        echo "</a>";
                        ?>
                    </div>
                </div>

                <thead>
                <tr style="height: 40px;">
                    <th scope="col" class="no" style="width:7%; border-bottom: 1px solid white; color:white;">추천</th>
                    <th scope="col" class="title" style="width:58%; border-bottom: 1px solid white; color:white;">제목</th>
                    <th scope="col" class="author" style="width:20%; border-bottom: 1px solid white; color:white;">작성시간</th>
                    <th scope="col" class="date" style="width:15%; border-bottom: 1px solid white; color:white;">작성자</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // 여기서 게시판에 따라 검색을 나눈다.
                // 공지 게시판들은 분기문을 따로 설정해줘야 하며
                // 주제
                // 1. 공지 기능

                // 2. 게시판 주제별



                $sql = "SELECT * FROM board";
                $result = $db->query($sql);
//                if($result)
//                    echo "select 성공";
//                else
//                    echo "select 실패";

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc())
                    {
//                        $datetime = explode(' ', $row['b_date']);
//                        $date = $datetime[0];
//                        $time = $datetime[1];
//                        if($date == Date('Y-m-d'))
//                            $row['b_date'] = $time;
//                        else
//                            $row['b_date'] = $date;
                        ?>
                        <tr style="height: 60px; ">
                            <td style="text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white;" class="hit"><?php echo $row['board_view_count']?></td>
                            <td style="border-bottom:1px solid white; border-collapse:collapse; color:white;" class="title"><?php echo $row['board_title']?></td>
                            <td style="text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white;" class="author"><?php echo $row['board_regtime']?></td>
                            <td style="text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white;" class="date"><?php echo $row['board_userid']?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td style="collapse: 4">게시글이 없습니다.</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </nav>

</div>    


</body>
<script>


</script>
</html>