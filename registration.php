
<?php

    require 'mailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls";
    $mail->Username = "helloworld.hello1world@gmail.com";
    $mail->Password = "HelloWorld12345";
    $mail->isSMTP();


    function SendOTP($otp, $mail){
       // Mail 

       $mail->setFrom("helloworld.hello1world@gmail.com", "Hello World");
       $mail->addAddress($mail);
       $mail->addReplyTo("helloworld.helloworld@gmail.com");

       $mail->isHTML(true);
       $mail->Subject = "Check";

       $mail->Body = "
            <p>Click this link to verify Email</p>
            <a href='http://localhost/demo1/PHP_Program/verify_registration.php'> Verify </a>
            "
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

        $otp = rand(1000, 9999);
        // $gender = $_POST['gender'];
        // $dob = $_POST['dob'];


        if(!$mail->send()){
            echo "Message could not be send";
        }
        else{
            echo "Message send successfully. Check your mail to verify";
        }


        
        $con = mysqli_connect("localhost", "root", "", "sample");

        if (mysqli_connect_errno()) {

            printf("connection failed: %s\n", mysqli_connect_error());
            exit();
        }

        if(mysqli_query("insert into vehicleregister values('$email', $phone, $otp, '$name', '$father_name', '$mother_name')")){
            echo "Inserted Successfully";
            SendOTP($otp, $email);
        }
        
        mysqli_close($con);


    }
    else{
        include('html/registration.html');
    }

?>