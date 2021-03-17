
<?php

require 'mailer/PHPMailerAutoload.php';
session_start();

function SendOTP(){
    // Mail 
     $mail = new PHPMailer();
     // $mail->SMTPDebug = 3;

     $mail->Host = "smtp.gmail.com";
     $mail->Port = 587;
     $mail->SMTPAuth = true;
     $mail->SMTPSecure = "tls";
     $mail->Username = "helloworld.hello1world@gmail.com";
     $mail->Password = "HelloWorld12345";
     $mail->isSMTP();
 
     $mail->setFrom("helloworld.hello1world@gmail.com", "Hello World");
     $mail->addAddress($_SESSION['email']);
     $mail->addReplyTo("helloworld.helloworld@gmail.com");

     $mail->isHTML(true);
     $mail->Subject = "Check";

     $otp = $_SESSION['otp'];
     $mail->Body = " <h1> Your One Time Password (OTP) is $otp </h1> ";
     $res = $mail->send();
     
     // echo $mail->ErrorInfo;
     return $res;
 }

 $error = "";
 $state = 0; // 0 - get email, 1 - get otp
 $email = "";

 if(isset($_POST['submit'])){
     if(!isset($_POST['otp'])){
         $email = $_POST['email'];
         $_SESSION['email'] = $email;
         $_SESSION['otp'] = rand(100000, 999999);
         if(!SendOTP()){
             $error = "Mail cannot be send. Server problem. Retry again later.";
         }
         else{
             $state = 1;
         }
     }
     else{
         $otp = $_POST['otp'];
     }
 }



?>

 <html>
    <head>
        <title>
            Heavy Vehicle Driving Course
        </title>
        <link href="/mailOTP/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/mailOTP/css/email_verification.css" rel="stylesheet" />
    </head>

    <body>
        <div class="container" style="padding: 10%;">
        <h2 class="alert alert-dark">Heavy Vehicle Driver Training Course</h2>      
            <form class="container shadow-lg p-3 mb-5 rounded form form-color" action="/mailOTP/email_verification.php" method="POST">

                <center>
                    <img src="/mailOTP/images/irtlogo.jpg" alt="IRT Logo" width="150px" height="150px" />
                </center>
                <div class="input">
                    <label>
                        <?php if($state==0) echo "Email"; if($state==1) echo "OTP is send to your email $email." ?>
                    </label>
                    <?php
                        if($state == 0)
                            echo '<input type="email" placeholder="Email" class="form-control" name="email" required />';
                        if($state == 1)
                            echo '<input type="number" placeholder="OTP" class="form-control" name="otp" required />';
                    ?>
                    <br />
                    <p class="text-danger"><?php echo $error; ?></p>
                    <br />
                </div>

                <div>
                    <input type="submit" 
                        value="<?php if($state==0) echo "Send OTP"; if($state==1) echo "Verify OTP" ?>" 
                        name="submit" class="btn btn-dark" />
                </div>

            </form>
        </div>
    </body>
 </html>