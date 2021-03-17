
<?php
    session_start();
    
    
    function insert_database(){
        $con = mysqli_connect("localhost", "root", "", "sample");

        if (mysqli_connect_errno()) {
            mysqli_close($con);
            return false;
        }

        $email = $_SESSION['email'];
        $phone = $_SESSION['phone'];
        $center = $_SESSION['center'];
        $district = $_SESSION['district'];
        $name = $_SESSION['candidate_name'];
        $father = $_SESSION['father_name'];
        $mother = $_SESSION['mother_name'];
        $address = $_SESSION['address'];
        $ration = $_SESSION['ration_number'];
        $aadhaar = $_SESSION['aadhaar_number'];
        $religion = $_SESSION['religion'];
        $community = $_SESSION['community'];
        $caste = $_SESSION['caste'];
        $gender = $_SESSION['gender'];
        $dob = $_SESSION['dob'];
        $eighth = $_SESSION['8th'];
        $tenth = $_SESSION['10th'];
        $diplomo = $_SESSION['diplomo'];
        $degree = $_SESSION['degree'];
        $tamil_medium = $_SESSION['tamil_medium'];
        $height = $_SESSION['height'];
        $weight = $_SESSION['weight'];
        $license_number = $_SESSION['license_number'];
        $license_issue_date = $_SESSION['license_issue_date'];
        $license_expire_date = $_SESSION['license_expire_date'];
        $badge_number = $_SESSION['badge_number'];
        $badge_issue_date = $_SESSION['badge_issue_date'];
        $psv_region = $_SESSION['psv_region'];
        $hptv_issue_date = $_SESSION['hptv_issue_date'];
        $passbook_details = $_SESSION['passbook_details'];
        $free_courses = $_SESSION['free_courses'];

        if(!mysqli_query($con, 
            "insert into CourseRegistration
            (email, phone, center, district, name, father_name, mother_name, address, ration_number, aadhaar_number, religion,
            community, caste, gender, dob, eighth, tenth, diplomo, degree, tamil_medium, height, weight, license_number, 
            license_issue_date, license_expire_date, badge_number, badge_issue_date, psv_region, hptv_issue_date,
            passbook_details, free_courses) 
            values('$email', $phone, '$center', '$district', '$name', '$father', '$mother', '$address', '$ration', '$aadhaar',
            '$religion', '$community', '$caste', '$gender', '$dob', '$eighth', '$tenth', '$diplomo', '$degree', '$tamil_medium', 
            $height, $weight, '$license_number', '$license_issue_date', '$license_expire_date', '$badge_number', '$badge_issue_date'
            , '$psv_region', '$hptv_issue_date', '$passbook_details', '$free_courses')" )){
            mysqli_close($con);
            return false;
        }
        
        mysqli_close($con);
        return true;
    }


    if(isset($_POST['submit'])){
        if($_SESSION['otp'] != $_POST['otp']){
            echo "<script>alert('wrong OTP'); </script>";
            include('html/background_details.html');
        }
        else{
            $_SESSION['otp_verification'] = true;

            $_SESSION['8th'] = $_POST['8th'];
            $_SESSION['10th'] = $_POST['10th'];
            $_SESSION['diplomo'] = $_POST['diplomo'];
            $_SESSION['degree'] = $_POST['degree'];
            $_SESSION['tamil_medium'] = $_POST['tamil_medium'];
            $_SESSION['height'] = $_POST['height'];
            $_SESSION['weight'] = $_POST['weight'];
            $_SESSION['license_number'] = $_POST['license_number'];
            $_SESSION['license_issue_date'] = $_POST['license_issue_date'];
            $_SESSION['license_expire_date'] = $_POST['license_expire_date'];
            $_SESSION['badge_number'] = $_POST['badge_number'];
            $_SESSION['badge_issue_date'] = $_POST['badge_issue_date'];
            $_SESSION['psv_region'] = $_POST['psv_region'];
            $_SESSION['hptv_issue_date'] = $_POST['hptv_issue_date'];
            $_SESSION['passbook_details'] = $_POST['passbook_details'];
            $_SESSION['free_courses'] = $_POST['free_courses'];

            // insert_database();

            header("Location: /mailOTP/document_upload.php");
        }
    }
    else{
        if(!isset($_SESSION['email'])){
            header("Location: /mailOTP/registration.php");
        }
        include('html/background_details.html');
    }

?>


