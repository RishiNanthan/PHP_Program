<?php
   
   session_start();
   
    function check_valid_file($file){
        $valid_extensions = array('jpg', 'png', 'pdf', 'jpeg');
        $max_file_size = 10000000;

        $ar = explode('.',$file['name']);
        $file_ext = strtolower(array_pop($ar));
        if($file['size'] < $max_file_size && in_array($file_ext, $valid_extensions)){
            return true;
        }
        return false;
    }


    function store_file($file, $type){
        $upload_dir = "D:\\Program Files\\xampp\\htdocs\\MailOTP\\uploads\\";
        $email = $_SESSION['email'];

        $ar = explode('.',$file['name']);
        $file_ext = strtolower(array_pop($ar));

        if(check_valid_file($file) && move_uploaded_file($file['tmp_name'], $upload_dir.$type."/$email.$file_ext")){
            return true;
        }
        else{
            return false;
        }
    }

    if(isset($_POST['submit'])){

        $photograph = $_FILES['photograph'];
        $transfer_certificate = $_FILES['transfer_certificate'];
        $educational_certificate = $_FILES['educational_certificate'];
        $aadhaar_card = $_FILES['aadhaar_card'];
        $driving_license = $_FILES['driving_license'];
        $community_certificate = $_FILES['community_certificate'];

        if(store_file($photograph, "photograph") && store_file($transfer_certificate, "transfer_certificate") && store_file($educational_certificate, 
        "educational_certificate") && store_file($aadhaar_card, "aadhaar") && store_file($driving_license, "driving_license") && 
        store_file($community_certificate, "community_certificate")){
            session_destroy();
            echo "Files uploaded successfully";
        }
        else{
            echo "Retry upload";
        }
    }
    else{
        
        if(!isset($_SESSION["otp_verification"])){
            header("Location: /mailOTP/background_details.php");
        }

        include('html/document_upload.html');
    }

?>