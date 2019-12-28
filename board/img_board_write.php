<doctype html>
<html>
<head>
    <title>Peanut Community</title>
    <link rel="stylesheet" href="../css/board_write.css">
</head>
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function goHome(){
        location.href='../index.html';
    }
</script>
<body>
<div class="container">
    <article class="boardArticle">
        <div id="boardWrite">
            <div id="boardHeader" >
                <h1 class="logo"><a href="../index.html">Peanut Community</a></h1>
                <?php
                $board_name = $_GET['bName'];
                $result = str_replace('%20' , '', $board_name);
                echo "<h3>$result 게시판 글쓰기</h3>";
                ?>
            </div>
            <form method="post" action="./single_img_upload.php" enctype="multipart/form-data">
                <table id="boardWrite">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="bTitle">제목</label></th>
                        <td class="title"><input type="text" name="bTitle" id="bTitle"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bContent">이미지</label></th>
                        <td class="content"><textarea name="bContent" id="bContent"></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bTitle">업로드</label></th>
                        <td class="title"><input type="file" name="upload" id="upload"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td style="font-size: 14px;">2MB 이하의 사진만 등록 가능합니다.</td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            $board_name = $_GET['bName'];
                            $result = str_replace('%20' , '', $board_name);
                            echo "<input type='hidden' id='bBoard_name' name='bBoard_name' value='$result'/>"
                            ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="btnSet">
                    <button type="submit" class="btnSubmit btn">작성</button>
                    <button onclick="goHome()" class="btnList btn">취소</button>
                </div>
            </form>
        </div>
    </article>
</div>

</body>

</html>