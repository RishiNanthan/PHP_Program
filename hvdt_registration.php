<?php 

session_start();


function is_error($error_array){
    $fields = array_keys($error_array);
    foreach($fields as $field){
        if($error_array[$field] != ""){
            return true;
        }
    }
    return false;
}

function verify_files($files, $file_fields, $files_error){
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $max_file_size = 10000000;

    foreach($file_fields as $field){
        $file = $files[$field];
        $ar = explode('.',$file['name']);
        $file_ext = strtolower(array_pop($ar));
        if(!($file['size'] < $max_file_size && in_array($file_ext, $allowed_extensions))){
            $files_error[$field] = "File Size must be lesser than $max_file_size and have only .jpg .jpeg .png .pdf types";
        }
        else{
            $files_error[$field] = "";
        }
    }
    return $files_error;
}

function find_year_difference($dob, $today){
    $from = new DateTime($dob);
    $to   = new DateTime($today);
    return $from->diff($to)->y;
}

function date_greater($date1, $date2){
    $curdate=strtotime($date1);
    $mydate=strtotime($date2);

    return $curdate > $mydate;
}

function verify_data($data, $data_fields, $data_error){

    foreach($data_fields as $field){
        if($data[$field] != null && $data[$field] != ""){
            $data_error[$field] = null;
        }
        else{
            $data_error[$field] = "$field cannot be empty";
        }
    }

    if(find_year_difference($data['dob'], "today") < 20){
        $data_error['dob'] = "Age must be greater than 20 to be able to apply for this course.";
    }
    if($data['height'] < 155){
        $data_error['height'] = "Height must be greater than 155cm";
    }
    if($data['weight'] < 40){
        $data_error['weight'] = "Weight must be greater than 40kg";
    }
    if(find_year_difference($data['license_issue_date'], 'today') < 1){
        $data_error['license_issue_date'] = "It requires 1 year after LMV license to join this course";
    }

    if(date_greater("today", $data['license_expire_date'])){
        $data_error['license_expire_date'] = "License Already Expired";
    }

    return $data_error;
}

function store_data($data, $data_fields){
    $con = mysqli_connect("localhost", "root", "", "sample");

    if (mysqli_connect_errno()) {
        mysqli_close($con);
        return false;
    }

    $email = $_SESSION['email'];

    $phone = $data['phone'];
    $center = $data['center'];
    $district = $data['district'];
    $name = $data['candidate_name'];
    $father = $data['father_name'];
    $mother = $data['mother_name'];
    $address = $data['address'];
    $ration = $data['ration_number'];
    $aadhaar = $data['aadhaar_number'];
    $religion = $data['religion'];
    $community = $data['community'];
    $caste = $data['caste'];
    $gender = $data['gender'];
    $dob = $data['dob'];
    $eighth = $data['8th'];
    $tenth = $data['10th'];
    $diplomo = $data['diplomo'];
    $degree = $data['degree'];
    $tamil_medium = $data['tamil_medium'];
    $height = $data['height'];
    $weight = $data['weight'];
    $license_number = $data['license_number'];
    $license_issue_date = $data['license_issue_date'];
    $license_expire_date = $data['license_expire_date'];
    $badge_number = $data['badge_number'];
    $badge_issue_date = $data['badge_issue_date'];
    $psv_region = $data['psv_region'];
    $hptv_issue_date = $data['hptv_issue_date'];
    $passbook_details = $data['passbook_details'];
    $free_courses = $data['free_courses'];

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

function store_files($files, $file_fields){
    $upload_dir = "D:\\Program Files\\xampp\\htdocs\\MailOTP\\uploads\\";
    $email = $_SESSION['email'];

    foreach($file_fields as $field){
        $file = $files[$field];

        $ar = explode('.',$file['name']);
        $file_ext = strtolower(array_pop($ar));

        if(!move_uploaded_file($file['tmp_name'], $upload_dir.$field."/$email.$file_ext")){
            echo "Problem in storing $field file of $email";
            return false;
        }
    }
    return true;
}


$data_fields = array('phone', 'center', 'district', 'candidate_name', 'father_name', 'mother_name', 'address', 'ration_number', 'aadhaar_number',
'religion', 'community', 'caste', 'gender', 'dob', '8th', '10th', 'diplomo', 'degree', 'tamil_medium', 'height', 'weight', 'license_number', 'license_issue_date',
'license_expire_date', 'badge_number', 'badge_issue_date', 'psv_region', 'hptv_issue_date', 'passbook_details', 'free_courses');

$file_fields = array('photograph', 'transfer_certificate', 'educational_certificate', 'aadhaar_card', 'driving_license', 'community_certificate');


$email = $_SESSION['email'];
$data = array();
$files = array();
$data_error = array();
$files_error = array();

foreach($data_fields as $field){
    $data[$field] = null;
    $data_error[$field] = "";
}

foreach($file_fields as $field){
    $files[$field] = null;
    $files_error[$field] = "";
}


if(!isset($_SESSION["otp_verification"]) || $_SESSION['otp_verification']==false){
    header("Location: /MailOTP/email_verification.php");
}


if(isset($_POST['submit'])){
    foreach($data_fields as $field){
        $data[$field] = $_POST[$field];
    }

    foreach($file_fields as $field){
        $files[$field] = $_FILES[$field];
    }


    $files_error = verify_files($files, $file_fields, $files_error);
    $data_error = verify_data($data, $data_fields, $data_error);

    if(!is_error($files_error) && !is_error($data_error)){
        if(store_files($files, $file_fields) && store_data($data, $data_fields)){
            session_destroy();
            header("Location: /mailOTP/thank_you.php");
        }
        else{
            echo "Cannot save your details. Try again";
        }
    }
}


?>


<html>
    <head>
        <title>Heavy Vehicle Driver Training Course Registration Form</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" />

        <script src="js/registration.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                <br />
                <h1 class="alert alert-dark">Heavy Vehicle Driver Training Course</h1>
                <br />
            </div>
            
            <form action="/mailOTP/hvdt_registration.php" method="POST" enctype="multipart/form-data" class="form">

                <h3 class="alert alert-dark">Personal Details</h3>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Name of the Candidate (பெயர்)</label>
                    <input class="form-control" placeholder="Name" type="text" id="candidate_name" name="candidate_name" 
                    value="<?php echo $data['candidate_name']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['candidate_name']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Father's Name (தந்தை பெயர்)</label>
                    <input class="form-control" placeholder="Father's Name" type="text" id="father_name" name="father_name"
                    value="<?php echo $data['father_name']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['father_name']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Mother's Name (தாய் பெயர்)</label>
                    <input class="form-control" placeholder="Mother's Name" type="text" id="mother_name" name="mother_name"
                    value="<?php echo $data['mother_name']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['mother_name']; ?></p>
                </div>
 
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Date Of Birth (பிறந்த தேதி) <br /> <small>Age Should be more than 20</small> </label>
                    <input class="form-control" type="date" id="dob" name="dob"
                    value="<?php echo $data['dob']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['dob']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Residential Address ( வீட்டு முகவரி)</label>
                    <input class="form-control" placeholder="Address" type="text" id="address" name="address"
                    value="<?php echo $data['address']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['address']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Name of the District (மாவட்டத்தின் பெயர்)</label>
                    <input class="form-control" placeholder="District" type="text" id="district" name="district"
                    value="<?php echo $data['district']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['district']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Contact Phone No (தொடர்பு தொலைபேசி எண்)</label>
                    <input class="form-control" placeholder="Phone" type="number" id="phone" name="phone"
                    value="<?php echo $data['phone']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['phone']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Ration Card Number (குடும்ப அட்டை எண்)</label>
                    <input class="form-control" placeholder="Ration Card Number" type="text" id="ration_number" name="ration_number"
                    value="<?php echo $data['ration_number']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['ration_number']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Aadhaar No (ஆதார் எண்)</label>
                    <input class="form-control" placeholder="Aadhaar Number" type="text" id="aadhaar_number" name="aadhaar_number"
                    value="<?php echo $data['aadhaar_number']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['aadhaar_number']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Religion (மதம்) (Hindu, Muslim, Christian,Parsi,Jain)</label>
                    <input class="form-control" placeholder="Religion" type="text" id="religion" name="religion"
                    value="<?php echo $data['religion']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['religion']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Community</label>
                    <select name="community" id="commmunity" class="form-control" required>
                        <option value="OC">OC</option>
                        <option value="BC">BC</option>
                        <option value="MBC">MBC</option>
                        <option value="BCM">BCM</option>
                        <option value="DNC">DNC</option>
                        <option value="SC">SC</option>
                        <option value="SCA">SCA</option>
                        <option value="ST">ST</option>
                    </select>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Caste (சாதி)</label>
                    <input class="form-control" placeholder="Caste" type="text" id="caste" name="caste"
                    value="<?php echo $data['caste']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['caste']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Gender (பாலினம்)</label>
                    <div>
                        <input type="radio" id="male" name="gender" value="male" class="btn-check" checked />
                        <label> Male</label>
                        &nbsp;
                        <input type="radio" id="female" name="gender" value="female" class="btn-check" />
                        <label> Female</label>     
                        &nbsp;    
                        <input type="radio" id="other" name="gender" value="other" class="btn-check" />
                        <label> Other</label>   
                    </div>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">Height (உயரம்) in cm</label>
                    <input class="form-control" placeholder="Height" type="number" id="height" name="height"
                    value="<?php echo $data['height']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['height']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">Weight (எடை) in kg</label>
                    <input class="form-control" placeholder="Weight" type="number" id="weight" name="weight"
                    value="<?php echo $data['weight']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['weight']; ?></p>
                </div>

                <h3 class="alert alert-dark">Background Details</h3>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Educational Qualification</label>
                    <div class="container">

                        <div class="row">
                            <div class="col col-4"></div>
                            <div class="col col-4">Pass</div>
                            <div class="col col-4">Fail</div>
                        </div>

                        <div class="border row">
                            <div class="col col-4">
                                8th
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="8th" value="pass" style="margin-top: 5px;" checked />
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="8th" value="fail" style="margin-top: 5px;" />
                            </div>
                        </div>
                        
                        <div class="border row">
                            <div class="col col-4">
                                10th
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="10th" value="pass" style="margin-top: 5px;" checked />
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="10th" value="fail" style="margin-top: 5px;" />
                            </div>
                        </div>

                        <div class="border row">
                            <div class="col col-4">
                                Diplomo
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="diplomo" value="pass" style="margin-top: 5px;" checked />
                            </div>
                            <div class="col col-4">
                                <input type="radio" name="diplomo" value="fail" style="margin-top: 5px;" />
                            </div>
                        </div>

                        <div class="border row">
                            <div class="col col-4">
                                Degree
                            </div>
                            <div class="col col-4 radio">
                                <input type="radio" name="degree" value="pass" style="margin-top: 5px;" checked />
                            </div>
                            <div class="col col-4 radio">
                                <input type="radio" name="degree" value="fail" style="margin-top: 5px;" />
                            </div>
                        </div>
                    </div>

                </div>
                                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Language of study in tamil?</label>
                    
                    <div class="radio">
                        <label><input type="radio" name="tamil_medium" value="yes" class="form-check-inline" checked > Yes </label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="tamil_medium" value="no" class="form-check-inline"> No </label>
                    </div>
                    
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Name of the Center of choice (பயிற்சி பெற விரும்பும் மையத்தின் பெயர்)</label>
                    <input class="form-control" type="text" id="center" name="center" placeholder="Center"
                    value="<?php echo $data['center']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['center']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">LMV Driving Licence Number (ஓட்டுநர் உரிமம் எண்)</label>
                    <input class="form-control" placeholder="Licence Number" type="text" id="license_number" 
                    value="<?php echo $data['license_number']; ?>"name="license_number" required />
                    <p class="text text-danger"><?php echo $data_error['license_number']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">LMV Issue Date (LMV எடுத்த நாள்)</label>
                    <input class="form-control" type="date" id="licence_issue_date" name="license_issue_date"
                    value="<?php echo $data['license_issue_date']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['license_issue_date']; ?></p>
                </div>
                         
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">Licence Valid upto (ஓட்டுனர் உரிமம் செல்லுபடி ஆகும் நாள்)</label>
                    <input class="form-control" type="date" id="licence_expire_date" name="license_expire_date"
                    value="<?php echo $data['license_expire_date']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['license_expire_date']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">PSV BADGE எண்</label>
                    <input class="form-control" placeholder="Badge Number" type="text" id="badge_number" name="badge_number"
                    value="<?php echo $data['badge_number']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['badge_number']; ?></p>
                </div>
                                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">PSV BADGE எடுத்த நாள்</label>
                    <input class="form-control" type="date" id="badge_issue_date" name="badge_issue_date"
                    value="<?php echo $data['badge_issue_date']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['badge_issue_date']; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">PSV Taken in the region of (PSV எடுக்கப்பட்ட இடம்)</label>
                    <input class="form-control" placeholder="PSV region" type="text" id="psv_region" name="psv_region"
                    value="<?php echo $data['psv_region']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['psv_region']; ?></p>
                </div>                
                                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label class="left">HPTV issue date</label>
                    <input class="form-control" type="date" id="hptv_issue_date" name="hptv_issue_date"
                    value="<?php echo $data['hptv_issue_date']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['hptv_issue_date']; ?></p>
                </div>
                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>
                        Bank Passbook details(வங்கி பாஸ்புக் விவரங்கள்)<br /> 
                        <small>1. Name of the Candidate 2. Account Number 3. Bank Name 4. Branch Name 5. IFSC Code</small> 
                    </label>
                    <input class="form-control" type="text" id="passbook_details" name="passbook_details"
                    value="<?php echo $data['passbook_details']; ?>" required />
                    <p class="text text-danger"><?php echo $data_error['passbook_details']; ?></p>
                </div>
                                
                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Any free course undertaken from TNSTC</label>
                    <div class="radio">
                        <label><input type="radio" name="free_courses" class="form-check-inline" checked > Yes</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="free_courses" class="form-check-inline" > No</label>
                    </div>
                </div>

                <h3 class="alert alert-dark">Documents</h3>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Photograph</label>
                    <input type="file" id="photograph" name="photograph" class="form-control" required />
                    <p><?php echo $files_error["photograph"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Transfer Certificate</label>
                    <input type="file" id="transfer_certificate" name="transfer_certificate" class="form-control" required />
                    <p><?php echo $files_error["transfer_certificate"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Educational Certificate</label>
                    <input type="file" id="educational_certificate" name="educational_certificate" class="form-control" required />
                    <p><?php echo $files_error["educational_certificate"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Aadhaar Card</label>
                    <input type="file" id="aadhaar_card" name="aadhaar_card" class="form-control" required />
                    <p><?php echo $files_error["aadhaar_card"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Driving License</label>
                    <input type="file" id="driving_license" name="driving_license" class="form-control" required />
                    <p><?php echo $files_error["driving_license"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>Community Certificate</label>
                    <input type="file" id="community_certificate" name="community_certificate" class="form-control" required />
                    <p><?php echo $files_error["community_certificate"]; ?></p>
                </div>

                <div class="shadow-lg p-3 mb-5 bg-white rounded">
                    <label>
                        <input type="checkbox" name="physical_deficiency" value="NO" required />
                         I declare that, I have no physical deficiencies and I have good vision (6/6)
                        without using spectacles and free from color blindness and night blindness. 
                    </label>
                </div>

                <div>
                    <input type="submit" value="Submit" class="btn btn-dark" name="submit" />
                </div>

                <br /> <br />
            </form>
            
        </div>
    </body>
</html>
