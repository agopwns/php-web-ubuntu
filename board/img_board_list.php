<?php
    session_start();
    $db = include('../dbconnect.php');

// 게시판 카테고리명 가져오기
$board_name = $_GET['bName'];
$board_name = str_replace('%20' , '', $board_name);
//echo $board_name ."<br>";//xptmxm

/* 페이징 시작 */
//페이지 get 변수가 있다면 받아오고, 없다면 1페이지를 보여준다.
if(isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
/* 검색 시작 */
if(isset($_GET['searchColumn'])) {
    $searchColumn = $_GET['searchColumn'];
    $subString .= '&amp;searchColumn=' . $searchColumn;
}
if(isset($_GET['searchText'])) {
    $searchText =  $_GET['searchText'];
    $subString .= '&amp;searchText=' . $searchText;
}
if(isset($searchColumn) && isset($searchText)) {
    $searchSql = " and " . $searchColumn . " like '%" . $searchText .  "%'";
    $searchSqlWhere = " where " . $searchColumn . " like '%" . $searchText . "%'";
} else {
    $searchSql = '';
    $searchSqlWhere = '';
}
/* 검색 끝 */


$notiAllPost = 0;
// default는 모든 게시판 기준이지만 최근 게시글을 제외하면 검색어나 로직을 추가해줘야함
//if($board_name != '최근글'){
//    $notiSql = "select  count(*) as cnt  from board where board_category='$board_name' AND board_super='Y' ORDER BY board_id DESC";
//    $notiResult = $db->query($notiSql);
//    $notiRow = $notiResult->fetch_assoc();
//    $notiAllPost = $notiRow['cnt'];
//
//    $sql = "select count(*) as cnt from board WHERE board_category='$board_name' AND board_super='N' $searchSql order by board_id desc";
//}
//else
    $sql = "select count(*) as cnt from board WHERE board_type='I' AND board_super='N' $searchSql order by board_id desc";
//echo $sql."<br>";//xptmxm

$result = $db->query($sql);
$row = $result->fetch_assoc();

$allPost = $row['cnt']; //전체 게시글의 수
echo $allPost;
//echo $allPost."<br>"; //xptmxm

if(empty($allPost)) {
    $emptyData = '<tr><td class="textCenter" colspan="5">글이 존재하지 않습니다.</td></tr>';
} else {

    $onePage = 14 - $notiAllPost; // 한 페이지에 보여줄 게시글의 수.
    $allPage = ceil($allPost / $onePage); //전체 페이지의 수

    if ($page < 1 || ($allPage && $page > $allPage)) {
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

    if ($currentSection == $allSection) {
        $lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
    } else {
        $lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
    }
    $prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
    $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.
    $paging = '<ul>'; // 페이징을 저장할 변수
//첫 페이지가 아니라면 처음 버튼을 생성
    if ($page != 1) {
        $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_start"><a href="./board_list.php?bName=' . $board_name . '&page=1' . $subString . '" style="font-size: 15px;">처음</a></li>';
    }
//첫 섹션이 아니라면 이전 버튼을 생성
    if ($currentSection != 1) {
        $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_prev"><a href="./board_list.php?bName=' . $board_name . '&page=' . $prevPage . $subString . '" style="font-size: 15px;">이전</a></li>';
    }

    for ($i = $firstPage; $i <= $lastPage; $i++) {
        if ($i == $page) {
            $paging .= '<li style="float:left; font-weight: bold; font-size: 17px; margin-left: 5px;" class="page current">' . $i . '</li>';
        } else {
            $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page"><a href="./board_list.php?bName=' . $board_name . '&page=' . $i . $subString . '" style="font-size: 17px;">' . $i . '</a></li>';
        }
    }
//마지막 섹션이 아니라면 다음 버튼을 생성
    if ($currentSection != $allSection) {
        $paging .= '<li style="float:left;  margin-left: 5px;" class="page page_next"><a href="./board_list.php?bName=' . $board_name . '&page=' . $nextPage . $subString . '" style="font-size: 15px;">다음</a></li>';
    }
//마지막 페이지가 아니라면 끝 버튼을 생성
    if ($page != $allPage) {
        $paging .= '<li style="float:left; font-size: 13px; margin-left: 5px;" class="page page_end"><a href="./board_list.php?bName=' . $board_name . '&page=' . $allPage . $subString . '" style="font-size: 15px;">끝</a></li>';
    }
    $paging .= '</ul>';
    /* 페이징 끝 */

// 쿼리
    $currentLimit = ($onePage * $page) - $onePage; //몇 번째의 글부터 가져오는지
    $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; //limit sql 구문
    if ($board_name == '최근글'){
        $sql = "select * from board WHERE board_type='I' AND board_super='N' $searchSql order by board_id desc" . $sqlLimit;
//        echo $sql."<br>";//xptmxm
    }
    else{
        $sql = "select * from board where board_type='I' AND board_category='$board_name' AND board_super='N' $searchSql order by board_id desc" . $sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지
//        echo $sql."<br>";//xptmxm
    }

    $result = $db->query($sql);

    // 해당 게시판 공지글 검색
    $notiSql = "select * from board where board_type='I' AND board_category='$board_name' AND board_super='Y' ORDER BY board_id DESC";
    $notiResult = $db->query($notiSql);
}
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
    <script src="//code.jquery.com/jquery.min.js"></script>


</head>
<script type="text/javascript">
    $(document).ready(function(){
        $(".fancybox").fancybox({
            openEffect: "none",
            closeEffect: "none"
        });

        $(".zoom").hover(function(){

            $(this).addClass('transition');
        }, function(){

            $(this).removeClass('transition');
        });
    });
</script>
<body>
<div class="parent_container">
    <div id="headers"><?php include '../include/include_header.php'?></div>

    <!-- Page Content -->
    <div class="container page-top">
        <div>
            <h4 style="color:#fff; margin-bottom: 40px">사진 게시판</h4>
            <a href='./img_board_write.php?bName=사진'><input type="button" value="글쓰기"></a>
        </div>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $bNO = $row['board_id'];
//                        echo $bNO;
                    $bStatus = $row['board_status'];
                    $bRecommendCount = $row['board_recommend_count'];
                    $bRegTime = $row['board_regtime']; // 작성 시간 분 단위까지
                    $dateResult = (strtotime(date('Y-m-d H:i:s')) - strtotime($bRegTime)) / 3600;
                    $dateResult = (int) $dateResult;
//                        echo $dateResult;

                    $date = strtotime($bRegTime);
                    $bRegTime = date('Y-m-d H:i', $date);
//                            $bRegTime = $bRegTime->format('Y-m-d H:i');
                    $bTitle = $row['board_title'];
                    $bImgPath = $row['board_sfile_path'];
                    // 새 글 표시
                    if($dateResult < 24){
                        $bTitle = $bTitle . " " . "new";
                    }

                    echo '<div class="col-lg-3 col-md-4 col-xs-6 thumb" style="width: 300px; height: 230px;">';
                    echo '<a href="/board/img_board_view.php?bNO=' . $bNO . '" class="fancybox" rel="ligthbox">';
                    echo '<img  src="' . $bImgPath . '" class="zoom img-fluid "  alt="" style="width:300px; height:230px;">';
                    echo '</a>';
                    echo '</div>';

                }
            } else {
                echo "row가 0입니다.";
            }

            ?>
<!--            <div class="col-lg-3 col-md-4 col-xs-6 thumb">-->
<!--                <a href="https://images.pexels.com/photos/62307/air-bubbles-diving-underwater-blow-62307.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" class="fancybox" rel="ligthbox">-->
<!--                    <img  src="https://images.pexels.com/photos/62307/air-bubbles-diving-underwater-blow-62307.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" class="zoom img-fluid "  alt="">-->
<!--                </a>-->
<!--            </div>-->


        </div>
</div>


</body>
<script>


</script>
</html>