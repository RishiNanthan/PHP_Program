
function verify_mail() {
    
    var email = document.getElementById('email').value;
    var xhttp = null;

    if(window.XMLHttpRequest){
        xhttp = new XMLHttpRequest();
    }
    else{
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onload = () => {
        let v = xhttp.responseText;
        console.log(v);
        var data = JSON.parse(xhttp.responseText);
        if(data.res == true){
            alert("Mail Send");
        }
        else{
            alert("Try again later");
        }
    }

    xhttp.open('POST', '/mailOTP/registration.php');
    xhttp.send(
        {
            'submit': 'email-verification',
            'email': email,
        }, true
    );
    
}