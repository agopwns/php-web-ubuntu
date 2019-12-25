<?php
    session_start();
    $db = include('../dbconnect.php');
// 게시판 카테고리명 가져오기
$board_name = $_GET['bName'];
$board_name = str_replace('%20' , '', $board_name);

/* 페이징 시작 */
//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
// default는 모든 게시판 기준이지만 최근 게시글을 제외하면 검색어나 로직을 추가해줘야함
$sql = 'select count(*) as cnt from board order by board_id desc';
$result = $db->query($sql);
$row = $result->fetch_assoc();

$allPost = $row['cnt']; //전체 게시글의 수
$onePage = 20; // 한 페이지에 보여줄 게시글의 수.
$allPage = ceil($allPost / $onePage); //전체 페이지의 수

if($page < 1 || ($allPage && $page > $allPage)) {
    ?>
    <script>
        alert("존재하지 않는 페이지입니다.");
        history.back();
    </script>
    <?php
    exit;
}
$oneSection = 10; //한번에 보여줄 총 페이지 개수(1 ~ 10, 11 ~ 20 ...)
$currentSection = ceil($page / $oneSection); //현재 섹션
$allSection = ceil($allPage / $oneSection); //전체 섹션의 수
$firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

if($currentSection == $allSection) {
    $lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
} else {
    $lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
}
$prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
$nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.
$paging = '<ul>'; // 페이징을 저장할 변수
//첫 페이지가 아니라면 처음 버튼을 생성
if($page != 1) {
    $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_start"><a href="./board_list.php?bName=' . $board_name . '&page=1" style="font-size: 15px;">처음</a></li>';
}
//첫 섹션이 아니라면 이전 버튼을 생성
if($currentSection != 1) {
    $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_prev"><a href="./board_list.php?bName=' . $board_name . '&page=' . $prevPage . '" style="font-size: 15px;">이전</a></li>';
}

for($i = $firstPage; $i <= $lastPage; $i++) {
    if($i == $page) {
        $paging .= '<li style="float:left; font-weight: bold; font-size: 17px; margin-left: 5px;" class="page current">' . $i . '</li>';
    } else {
        $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page"><a href="./board_list.php?bName=' . $board_name . '&page=' . $i . '" style="font-size: 17px;">' . $i . '</a></li>';
    }
}
//마지막 섹션이 아니라면 다음 버튼을 생성
if($currentSection != $allSection) {
    $paging .= '<li style="float:left;  margin-left: 5px;" class="page page_next"><a href="./board_list.php?bName=' . $board_name . '&page=' . $nextPage . '" style="font-size: 15px;">다음</a></li>';
}
//마지막 페이지가 아니라면 끝 버튼을 생성
if($page != $allPage) {
    $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_end"><a href="./board_list.php?bName=' . $board_name . '&page=' . $allPage . '" style="font-size: 15px;">끝</a></li>';
}
$paging .= '</ul>';
/* 페이징 끝 */

// 쿼리
$currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문
if($board_name == '최근글')
    $sql = 'select * from board order by board_id desc' . $sqlLimit;
else
    $sql = "select * from board where board_category='$board_name' order by board_id desc" . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
//echo $sql."<br>";
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
    <link rel="stylesheet" href="../css/board_list.css">
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

    <nav id="navBoardList">
        <div class="boardList-container">

            <table id = "boardTable" style=" width:95%;">
                <div class="table-div" style="display: flex; margin-bottom: 40px;">
                    <div class="table-div-child" style=" width: 780px;">
                    <?php
                        $board_name = $_GET['bName'];
                        $result = str_replace('%20' , '', $board_name);
                        echo "<a href='board_list.php' class='readHide' style='color:white; font-size: 20px; font-weight: bold; margin-bottom: 40px;'>$result 게시판</a>";
                        echo "</div>";
                        echo "<div class='table-div-child' style='width: 300px; float:right'>";
                        ?>
                        <select name="job" style="color:#fff;">
                            <option value="제목" selected="selected">제목</option>
                            <option value="작성자">작성자</option>
                        </select>
                        <?php
                        echo "<input type='text' style='color:#fff; width:100px; margin-left: 10px;'/>";
                        echo "<input type='button' style='color:#fff; margin-left: 10px;' value='검색'/>";
                        echo "<a href='board_write.php?bName=$result'>";
                        echo "<input type='button' style='color:#fff; margin-left: 10px;' value='글쓰기'/>";
                        echo "</a>";
                        ?>
                        <div class="paging">
                            <?php echo $paging ?>
                        </div>
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
                $currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
                $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문
                if($board_name == '최근글')
                    $sql = 'select * from board order by board_id desc' . $sqlLimit;
                else
                    $sql = "select * from board where board_category='$board_name' order by board_id desc" . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
//                echo $sql."<br>";
                $result = $db->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc())
                    {
                        $bNO = $row['board_id'];
//                        echo $bNO;
                        $bStatus = $row['board_status'];
                        $bViewCount = $row['board_view_count'];
                        $bTitle = $row['board_title'];
                        $bRegTime = $row['board_regtime'];
                        $bUserId = $row['board_userid'];
                        echo "<tr style='height: 60px' onClick='location.href=\"./board_view.php?bNO=".$bNO."\"' style='cursor:pointer'>";
                        if($bStatus == 'D'){
                            // 삭제된 게시물
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='hit'>$bViewCount</td>";
                            echo "<td style='border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='title'>삭제된 게시물입니다.</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='author'>$bRegTime</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='date'>$bUserId</td>";

                        } else if ($bStatus == 'B') {
                            // 블라인드 처리된 게시물
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='hit'>$bViewCount</td>";
                            echo "<td style='border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='title'>블라인드 처리된 게시물입니다.</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='author'>$bRegTime</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='date'>$bUserId</td>";
                        } else {
                            // 정상 게시물
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='hit'>$bViewCount</td>";
                            echo "<td style='border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='title'>$bTitle</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='author'>$bRegTime</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='date'>$bUserId</td>";
                        }
                    ?>
                        </tr>
                        <?php
                    }
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