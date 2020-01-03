<doctype html>
<html>
<head>
    <title>sign up page</title>
    <link rel="stylesheet" href="../css/signUp.css">
</head>
<script src="//code.jquery.com/jquery.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function execPostCode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
                var extraRoadAddr = ''; // 도로명 조합형 주소 변수

                // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                    extraRoadAddr += data.bname;
                }
                // 건물명이 있고, 공동주택일 경우 추가한다.
                if(data.buildingName !== '' && data.apartment === 'Y'){
                    extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                }
                // 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                if(extraRoadAddr !== ''){
                    extraRoadAddr = ' (' + extraRoadAddr + ')';
                }
                // 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
                if(fullRoadAddr !== ''){
                    fullRoadAddr += extraRoadAddr;
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                console.log(data.zonecode);
                console.log(fullRoadAddr);

                // $("[name=addr1]").val(data.zonecode);
                // $("[name=addr2]").val(fullRoadAddr);

                document.getElementById('addr1').value = data.zonecode; //5자리 새우편번호 사용
                document.getElementById('addr2').value = fullRoadAddr;
                //document.getElementById('signUpUserCompanyAddressDetail').value = data.jibunAddress;
            }
        }).open();
    }

    function checkID() {
        var idValue = $("#id").val();
        $.ajax({
                url:"idcheck.php",
                type:"POST",
                data:{id:idValue},
                datatype:"html",
                success:function(data){
                    alert(data);
                }
        });
    }

</script>
<body>
<div class="container">
    <form name="join" method="post" action="memberSave.php">
        <h1><a href="index.html">Peanut Community</a></h1>
        <h3>회원 가입</h3>
        <table>
            <tr>
                <td class="col">아이디 *</td>
                <td >
                    <input type="text" size="20" id="id" name="id">
                    <input type="button" value="중복 검사" onclick="checkID();">
                </td>
                <td id="idtd" name="idtd"></td>
            </tr>
            <tr>
                <td class="col">비밀번호 *</td>
                <td><input type="password" size="20" name="pwd"></td>
            </tr>
            <tr>
                <td class="col">비밀번호 확인 *</td>
                <td><input type="password" size="20" name="pwd2"></td>
            </tr>
            <tr>
                <td class="col">이름 *</td>
                <td><input type="text" size="6" maxlength="10" name="name"></td>
            </tr>
            <tr>
                <td class="col">우편 번호 *</td>
                <td>
                    <input type="text" size="6" id="addr1" name="addr1">
                    <input type="button" value="우편번호 검색" onclick="execPostCode();">
                </td>
            </tr>
            <tr>
                <td class="col">주소 *</td>
                <td><input type="text" size="40" id="addr2" name="addr2"></td>
            </tr>
            <tr>
                <td class="col">상세 주소 *</td>
                <td><input type="text" size="40" id="addr3" name="addr3"></td>
            </tr>
            <tr>
                <td class="col">이메일 *</td>
                <td><input type="text" size="20" name="email"> <!--<input type="button" value="인증 메일 보내기">--></td>
            </tr>
        </table>

        <a id= "agree" href="index.html">개인 정보 수집 및 이용 약관</a>에 동의 하십니까? <input type="checkbox" id="">
        <br>

        <input type="submit" value="확인">
        <input type="button" value="취소" onclick="index.html">
    </form>
</div>

</body>

</html>