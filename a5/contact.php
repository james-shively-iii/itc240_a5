
<?php include 'includes/header1.php'?>

<hr class="divider">
    <h2 class="text-center text-lg text-uppercase my-0">Contact <strong>Form</strong></h2>
<hr class="divider">

<?php
    
    
// the following helps avoid php date errors:
date_default_timezone_set('America/Los_Angeles');

//Here are the keys for the server: reedly.info
$siteKey = "6LfMeBMUAAAAAG87BbytBRH5sibWeOxM5ziiW00j";
$secretKey = "6LfMeBMUAAAAAC83ncbBt91t9Gf2hHvvfn3Cb3Fs";    

//this will change to the client's email  
    $to = 'james.shively-iii@seattlecentral.edu';

if(isset($_POST["FirstName"]))
{//if data, show it
    
    $FirstName = clean_post('FirstName');
    $LastName = clean_post('LastName');
    $Email = clean_post('Email');
    $Comments = clean_post('Comments');
    
    
    $myText = "The user has entered their information as follows:" . PHP_EOL . PHP_EOL; //double newlines 
    $myText .= $FirstName . " " . $LastName . PHP_EOL;
    $myText .= $Comments . PHP_EOL;

    $subject = "ITC240 Contact From " . $FirstName . " " . $LastName . " " . date("m/d/y, G:i:s");
    $headers = 'From: noreply@reedly.info' . PHP_EOL .
        'Reply-To: ' . $Email . PHP_EOL .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $myText, $headers);
    
    echo '<h4 align="center">Your email has been sent.</h4>
          <p align="center">We\'ll get back to you within 48 hours.</p>
          <p align="center"><a href="">Exit</p>
         ';

}else{//show form
    echo '
        <form action="" method="post">
          <div class="row">
            <div class="form-group col-lg-4">
              <label class="text-heading">First Name</label>
              <input type="text" name="FirstName" autofocus required class="form-control">
            </div>
            <div class="form-group col-lg-4">
              <label class="text-heading">Last Name</label>
              <input type="text" name="LastName" required class="form-control">
            </div>
            <div class="form-group col-lg-4">
              <label class="text-heading">Email Address</label>
              <input type="email" name="Email" required class="form-control">
            </div>            
            <div class="clearfix"></div>
            <div class="form-group col-lg-12">
              <label class="text-heading">Comments</label>
              <textarea class="form-control" name="Comments" rows="6"></textarea>
            </div>
            <div class="form-group col-lg-12">
              <button type="submit" class="btn btn-secondary">Submit</button>
            </div>
          </div>
        </form>
    ';
}    
    
    

$resp = ""; # the response from reCAPTCHA
$error = ""; # the error code from reCAPTCHA, if any
$skipFields = "recaptcha_challenge_field,recaptcha_response_field,Email"; #comma separated list of form elements NOT to store.
$fromDomain = $_SERVER["SERVER_NAME"];
$fromAddress = "noreply@" . $fromDomain; //form always submits from domain where form resides
if(isset($header)){ include $header;}#include header file if provided
include 'includes/recaptchalib.php'; #required reCAPTCHA class code
include 'includes/contact_include.php'; #complex unsightly code moved here

?>
<script type="text/javascript" src="includes/util.js"></script>

<!-- Edit Required Form Elements via JavaScript Here -->
<script type="text/javascript">
	//here we make sure the user has entered valid data	
	function checkForm(thisForm)
	{//check form data for valid info
		if(empty(thisForm.Name,"Please Enter Your Name")){return false;}
		if(!isEmail(thisForm.Email,"Please enter a valid Email Address")){return false;}
		return true;//if all is passed, submit!
	}
	
	//var RecaptchaOptions = { theme : "blackglass"}; //reCAPTCHA color themes: https://developers.google.com/recaptcha/docs/customization
</script>


<?php 

include 'includes/footer1.php';

function clean_post($key){
    
    if(isset($_POST[$key])){
        return strip_tags(trim($_POST[$key]));
     }else{
        return '';
    }
 
}



?>