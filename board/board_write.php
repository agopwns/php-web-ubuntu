<doctype html>
<html>
<head>
    <title>Peanut Community</title>
    <link rel="stylesheet" href="../css/board_write.css">
</head>
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    function goHome(){
        location.href='../index.html';
    }
    function loadPage(){
        CKEDITOR.replace('bContent');
    }
    function FormSubmit(f) {
        CKEDITOR.instances.contents.updateElement();
        if(f.contents.value == "") {
            alert("내용을 입력해 주세요.");
            return false;
        }
        alert(f.contents.value); // 전송은 하지 않습니다.
        return false;
    }


</script>
<body onload="loadPage();">
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
            <form action="./write_insert.php" method="post">
                <table id="boardWrite">
                    <tbody>
                    <tr>
                        <th scope="row" style="width: 50px;"><label for="bTitle">제목</label></th>
                        <td class="title"><input type="text" name="bTitle" id="bTitle"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bContent">내용</label></th>
                        <td class="content"><textarea name="bContent" id="bContent" style="width: 600px;"></textarea></td>
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