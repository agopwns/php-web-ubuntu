<?php
$host = '127.0.0.1';
$user = 'root';
$pw = 'root';
$dbName = 'web_db';
$db = mysqli_connect($host, $user, $pw, $dbName);
//$mysqli = new mysqli($host, $user, $pw, $dbName);

if($db){
    echo "connect : success<br>";

    $id=$_POST['id'];
    echo $id; echo "<br>";
    $password=md5($_POST['pwd']);
    echo $password ; echo "<br>";
    $password2=$_POST['pwd2'];
    $name=$_POST['name'];
    echo $name; echo "<br>";
    $zipcode=$_POST['addr1'];
    echo $zipcode; echo "<br>";
    $address=$_POST['addr2'];
    $address = $address. $_POST['addr3'];
    echo $address; echo "<br>";
    $email=$_POST['email'];
    echo $email; echo "<br>";

    $sql = "insert into test (test)";
    $sql = $sql. "values(1)";
//    $sql = "insert into member (mem_userid, mem_password, mem_username, mem_zipcode, mem_address, mem_email)";
//    $sql = $sql. "values('$id','$password','$name','$zipcode','$address','$email')";
    if($db->query($sql)){
        echo 'success inserting';
    }else{
        echo 'fail to insert sql';
    }
}
else{
    echo "disconnect : fail<br>";
}


?>