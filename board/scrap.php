<?php header('Content-Type: text/html; charset=UTF-8');
//simple_html_dom php 파일을 include함
include('../simplehtmldom/simple_html_dom.php');
//가져올 url 설정
$url = 'https://tv.zum.com/ranking';
$html = @file_get_html($url);
unset($arr_result);

$arr_result = $html->find('div.tv_wrap > a'); //1위 ~ 3위 랭킹순위 및 프로그램명 가져오기
if(count($arr_result) > 0){ //위의 이미지에서 a 태그에 포함되는 html dom 객체를 가져옴
    foreach($arr_result as $e){
        //children 속성을 이용해 맨 처음(0)의 태그 가져오기(<span class="rank_num">1</span>값 가져옴)
        echo $e->children(0)->plaintext.':'; //위의 값 중 1 값을 가져옴
        //children 속성을 이용해 맨 두번째(1)의 태그(<div class="tv_info">) 안의 두번째(1)의 태그 가져오기
        //(<p class="program">미스트롯</p>값 가져옴)
        echo $e->children(1)->children(1)->plaintext.'<br/>'; //위의 값 중 미스트롯 값을 가져옴
    }
} else {
    echo "<br/>";
}

unset($arr_result);
$arr_result = $html->find('div.list_wrap > div.list'); //4위 ~ 50위 랭킹순위 및 프로그램명 가져오기
if(count($arr_result) > 0){
    foreach($arr_result as $e){
        echo $e->children(1)->plaintext.':';
        echo $e->children(2)->children(1)->children(0)->plaintext.'<br/>';
    }
} else {
    echo "<br/>";
} exit; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>CSS Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php $content?>
<script>

</script>
</body>
</html>
