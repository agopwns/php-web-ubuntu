<doctype html>
<html>
<head>
<title>sign up page</title>
</head>
<body>
<form name="join" method="post" action="memberSave.php">
 <h1>input your information</h1>
 <table border="1">
     
  <tr>
   <td>ID</td>
   <td><input type="text" size="30" name="id"></td>
  </tr>
  <tr>
   <td>Password</td>
   <td><input type="password" size="30" name="pwd"></td>
  </tr>
  <tr>
   <td>Confirm Password</td>
   <td><input type="password" size="30" name="pwd2"></td>
  </tr>
  <tr>
   <td>name</td>
   <td><input type="text" size="12" maxlength="10" name="name"></td>
  </tr>
  <tr>
   <td>address</td>
   <td><input type="text" size="40" name="addr"></td>
  </tr>
  <tr>
   <td>sex</td>
   <td><input type="text" size="6" maxlength="2" name="sex"></td>
  </tr>
  <tr>
   <td>birth day</td>
   <td><input type="text" size="6" maxlength="8" name="birthDay"></td>
  </tr>
  <tr>
   <td>e-mail</td>
   <td><input type="text" size="30" name="email"></td>
  </tr>
 </table>
 <input type=submit value="submit"><input type=reset value="rewrite">
</form>
</body>
</html>