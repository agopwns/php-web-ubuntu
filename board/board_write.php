<doctype html>
<html>
<head>
    <title>sign up page</title>
    <link rel="stylesheet" href="../css/signUp.css">
</head>
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>


</script>
<body>
<div class="container">
    <article class="boardArticle">
        <?php
            $board_name = $_GET['page'];
            $result = str_replace('%20' , '', $board_name);
            echo "<h3>$result 게시판 글쓰기</h3>";
        ?>
        <div id="boardWrite">
            <form action="./write_update.php" method="post">
                <table id="boardWrite">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="bTitle">제목</label></th>
                        <td class="title"><input type="text" name="bTitle" id="bTitle"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bContent">내용</label></th>
                        <td class="content"><textarea name="bContent" id="bContent"></textarea></td>
                    </tr>
                    </tbody>
                </table>
                <div class="btnSet">
                    <button type="submit" class="btnSubmit btn">작성</button>
                    <a href="./board/index.php" class="btnList btn">목록</a>
                </div>
            </form>
        </div>
    </article>
</div>

</body>

</html>