
<?php

    require 'mailer/PHPMailerAutoload.php';
    session_start();

    $_SESSION['otp_verification'] = false;
 
    function SendOTP(){
       // Mail 
        $mail = new PHPMailer();
        $mail->SMTPDebug = 3;
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Username = "helloworld.hello1world@gmail.com";
        $mail->Password = "*****";
        $mail->isSMTP();
    
        $mail->setFrom("helloworld.hello1world@gmail.com", "Hello World");
        $mail->addAddress($_SESSION['email']);
        $mail->addReplyTo("helloworld.helloworld@gmail.com");

        $mail->isHTML(true);
        $mail->Subject = "Check";

        $otp = $_SESSION['otp'];
        $mail->Body = " <h1> Your One Time Password (OTP) is $otp </h1> ";
        $res = $mail->send();
        echo $mail->ErrorInfo;
        return $res;
    }


    if(isset($_POST['submit'])){

        $_SESSION['email'] = $_POST['email'];
        $_SESSION['center'] = $_POST['center'];
        $_SESSION['district'] = $_POST['district'];
        $_SESSION['candidate_name'] = $_POST['candidate_name'];
        $_SESSION['father_name'] = $_POST['father_name'];
        $_SESSION['mother_name'] = $_POST['mother_name'];
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['phone'] = $_POST['phone'];
        $_SESSION['ration_number'] = $_POST['ration_number'];
        $_SESSION['aadhaar_number'] = $_POST['aadhaar_number'];
        $_SESSION['religion'] = $_POST['religion'];
        $_SESSION['community'] = $_POST['community'];
        $_SESSION['caste'] = $_POST['caste'];
        $_SESSION['gender'] = $_POST['gender'];
        $_SESSION['dob'] = $_POST['dob'];

        $_SESSION['otp'] = rand(1000, 9999);
        if(SendOTP()){
            header('Location: /mailOTP/background_details.php');
        }    
        else{
            echo "Unable to send mail";
        }
    }
    else{
        include('html/registration.html');
    }

?>