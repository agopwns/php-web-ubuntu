<doctype html>
<html>
<head>
<title>sign up page</title>
</head>
<body>
<form name="join" method="post" action="memberSave.php">
 <h1>회원 가입</h1>
 <table>
  <tr>
   <td>아이디</td>
   <td><input type="text" size="30" name="id"></td>
  </tr>
  <tr>
   <td>비밀번호</td>
   <td><input type="password" size="30" name="pwd"></td>
  </tr>
  <tr>
   <td>비밀번호 확인</td>
   <td><input type="password" size="30" name="pwd2"></td>
  </tr>
  <tr>
   <td>이름</td>
   <td><input type="text" size="12" maxlength="10" name="name"></td>
  </tr>
  <tr>
   <td>주소</td>
   <td><input type="text" size="40" name="addr"></td>
  </tr>
  <tr>
   <td>이메일</td>
   <td><input type="text" size="30" name="email"></td>
  </tr>
 </table>
 <input type=submit value="submit"><input type=reset value="rewrite">
</form>
</body>
</html>