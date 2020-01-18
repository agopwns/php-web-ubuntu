<?php
session_start();
$db = include('../dbconnect.php');

header('Content-Type: text/html; charset=UTF-8');
//simple_html_dom php 파일을 include함
include('../simplehtmldom/simple_html_dom.php');
//가져올 url 설정
$url = 'https://trends.google.co.kr/trends/trendingsearches/daily/rss?geo=KR';
$html = @file_get_html($url);
unset($arr_result);

$arr_result = $html->find('item'); //1위 ~ 3위 랭킹순위 및 프로그램명 가져오기
if(count($arr_result) > 0){ //위의 이미지에서 a 태그에 포함되는 html dom 객체를 가져옴
    $arr_count = 0;
    foreach($arr_result as $e){

        $arr_search_title[$arr_count] = $e->children(0)->plaintext; // 검색어 제목
        $arr_view_count[$arr_count] = $e->children(1)->plaintext; // 조회수
        $arr_date[$arr_count] = $e->children(4)->plaintext.'<br>'; // 날짜
        $arr_date[$arr_count] = substr($arr_date[$arr_count], 0, 22);
        $arr_picture[$arr_count] = $e->children(5)->plaintext.'<br>'; // 사진
        $arr_article_title[$arr_count] = $e->children(7)->children(0)->plaintext; // 기사 제목
        $arr_article_title[$arr_count] = htmlspecialchars_decode($arr_article_title[$arr_count]);
        $arr_article_link[$arr_count] = $e->children(8)->children(2)->plaintext; // 기사 링크

        if($arr_count == 9) break;
        else $arr_count++;
    }
} else {

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Peanut Community</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/board_list.css">
</head>
<body>
<div id="headers"><?php include '../include/include_header.php'?></div>
<div class="parent_container">
    <nav id="navBoardList">
        <div class="boardList-container">

            <table id = "boardTable" style=" width:95%;">
                <div class="table-div" style="display: flex; margin-bottom: 40px;">
                    <div class="table-div-child" style=" width: 780px;">
                        <thead>
                        <tr style="height: 40px;">
                            <th scope="col" class="no" style="width:7%; border-bottom: 1px solid white; color:white;">순번</th>
                            <th scope="col" class="category" style="width:10%; border-bottom: 1px solid white; color:white;">조회수</th>
                            <th scope="col" class="title" style="width:55%; border-bottom: 1px solid white; color:white;">제목</th>
                            <th scope="col" class="author" style="width:20%; border-bottom: 1px solid white; color:white;">날짜</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        for($i = 0; $i < 10; $i++){
//                            echo "<tr style='height: 60px' onClick='location.href=\"" . $arr_article_link[$i] . "\"' style='cursor:pointer'>";
                            echo "<tr style='height: 60px' onClick='window.open(\"" . $arr_article_link[$i] . "\")' style='cursor:pointer'>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='hit'>";
                            echo $i+1;"</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='hit'>";
                            echo $arr_view_count[$i];"</td>";
                            echo "<td style='border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='title'>$arr_search_title[$i] - $arr_article_title[$i]</td>";
                            echo "<td style='text-align:center; border-bottom:1px solid white; border-collapse:collapse; color:white; cursor:pointer;' class='author'>$arr_date[$i]</td>";
                            echo "</tr>";
                        }
                            ?>
                        </tbody>
                    </div>
                </div>
            </table>
        </div>
    </nav>
</div>
</body>
</html>
