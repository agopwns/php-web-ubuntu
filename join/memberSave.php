<?php
require '/usr/share/php/libphp-phpmailer/class.phpmailer.php';
require '/usr/share/php/libphp-phpmailer/class.smtp.php';

$db = include('../dbconnect.php');

if($db){
//    echo "connect : success<br>";

    $id=$_POST['id'];
//    echo $id; echo "<br>";
    $password=md5($_POST['pwd']);
//    echo $password ; echo "<br>";
    $password2=$_POST['pwd2'];
    $name=$_POST['name'];
//    echo $name; echo "<br>";
    $zipcode=$_POST['addr1'];
//    echo $zipcode; echo "<br>";
    $address=$_POST['addr2'];
    $address = $address. $_POST['addr3'];
//    echo $address; echo "<br>";
    $email=$_POST['email'];
//    echo $email; echo "<br>";
    $hash = md5( rand(0,1000) ); // 이메일 인증용 번호
    echo $hash; echo "<br>";

    $sql = "insert into member (mem_userid, mem_password, mem_username, mem_zipcode, mem_address, mem_email, mem_hash)";
    $sql = $sql. "values('$id','$password','$name','$zipcode','$address','$email','$hash')";
    if($db->query($sql)){
        echo 'success insert<br>';
        // 페이지 이동
        //echo "<script>document.location.href='../index.html'</script>";

        // 인증 메일 발송
        $to      = $email; // Send email to our user
        $subject = 'Peanut Commnunity 인증 메일'; // Give the email a subject
        $message = '
 
Peanut Community 회원 가입 감사합니다.
'.$name.'님.
------------------------
아래 링크를 클릭하면 인증이 완료되며 사이트를 이용할 수 있습니다.
http://192.168.145.139/verify.php?email='.$email.'&hash='.$hash.'
'; // Our message above including the link

        $fmail = 'agopwns@naver.com';
        $fname = 'PeanutCommunity';
        $result = mailer($fname, $fmail, $to, $subject, $message); // Send our email

        if($result){
            echo "success send mail";
            echo "<script>alert('인증 메일을 확인해주세요')</script>";
            echo "<script>document.location.href='../index.html'</script>";
        } else {
            echo "fail to send mail";
            echo "<script>alert('인증 메일을 확인해주세요')</script>";
            echo "<script>document.location.href='../index.html'</script>";
        }

    }else{
        echo 'fail to insert sql';
    }
}
else{
    echo "disconnect : fail<br>";
}

function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{
    if ($type != 1) $content = nl2br($content);
    // type : text=0, html=1, text+html=2
    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->IsSMTP();
    //   $mail->SMTPDebug = 2;
    $mail->SMTPSecure = "ssl";
    $mail->SMTPAuth = true;
    $mail->Host = "smtp.naver.com";
    $mail->Port = 465;
    $mail->Username = "agopwns";
    $mail->Password = "1095akdhkd328!";
    $mail->CharSet = 'UTF-8';
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if ($cc)
        $mail->addCC($cc);
    if ($bcc)
        $mail->addBCC($bcc);
    if ($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
    if ( $mail->send() ) echo "성공";
    else echo "실패";
}
//header("Location: ../index.html");

?>