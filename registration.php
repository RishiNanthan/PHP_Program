
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

        // Mail 

        $mail->setFrom("helloworld.hello1world@gmail.com", "Hello World");
        $mail->addAddress("rishinanthan344@gmail.com");
        $mail->addReplyTo("helloworld.helloworld@gmail.com");

        $mail->isHTML(true);
        $mail->Subject = "Check";

        $mail->Body = "Hi hello";

        if(!$mail->send()){
            echo "Message could not be send";
        }
        else{
            echo "Message send successfully";
        }


        /*
        $con = mysqli_connect("localhost", "rishi", "rishi", "sample");

        if (mysqli_connect_errno()) {

            printf("connection failed: %s\n", mysqli_connect_error());
            exit();
        }
        
        $query = "SELECT * from student";
        
        $res = mysqli_query($con, $query);
        
        if ($res) {
        
            $row = mysqli_fetch_row($res);
            echo $row[0];
        }
        
        mysqli_free_result($res);
        mysqli_close($con);
        */



    }
    else{
        include('html/registration.html');
    }

?>