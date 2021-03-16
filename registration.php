
<?php

    require 'mailer/PHPMailerAutoload.php';



    function SendOTP($otp, $email){
       // Mail 
       $mail = new PHPMailer();
       $mail->Host = "smtp.gmail.com";
       $mail->Port = 587;
       $mail->SMTPAuth = true;
       $mail->SMTPSecure = "tls";
       $mail->Username = "helloworld.hello1world@gmail.com";
       $mail->Password = "****";
       $mail->isSMTP();
   
       $mail->setFrom("helloworld.hello1world@gmail.com", "Hello World");
       $mail->addAddress($email);
       $mail->addReplyTo("helloworld.helloworld@gmail.com");

       $mail->isHTML(true);
       $mail->Subject = "Check";

       $mail->Body = "
            <h1>Click this link to verify Email</h1>
            <a href='http://localhost/mailOTP/verify_registration.php?otp=$otp&email=$email'> Verify </a>
            ";

        if(!$mail->send()){
            echo "Message could not be send";
        }
        else{
            echo "Message send successfully. Check your mail to verify";
        }
    
    }

    if(isset($_POST['submit'])){
        $email = $_POST['email'];
        $center = $_POST['center'];
        $district = $_POST['district'];
        $candidate_name = $_POST['candidate_name'];
        $father_name = $_POST['father_name'];
        $mother_name = $_POST['mother_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $ration_number = $_POST['ration_number'];
        $aadhaar_number = $_POST['aadhaar_number'];
        $religion = $_POST['religion'];
        $community = $_POST['community'];
        $caste = $_POST['caste'];
        // $gender = $_POST['gender'];
        // $dob = $_POST['dob'];

        $otp = rand(1000, 9999);

        $con = mysqli_connect("localhost", "root", "", "sample");

        if (mysqli_connect_errno()) {

            echo "connection failed:" ;
            echo mysqli_connect_error();
            exit();
        }

        if(mysqli_query($con, "insert into verifyregister values('$email', $phone, $otp, '$candidate_name', '$father_name', '$mother_name')")){
            echo "Inserted Successfully";
            SendOTP($otp, $email);
        }
        else{
            echo mysqli_error($con);
        }
        
        mysqli_close($con);

    }
    else{
        include('html/registration.html');
    }

?>