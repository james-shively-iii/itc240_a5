<?php
/**
 * contact.php is a postback application designed to provide a 
 * contact form for users to email our clients.  contact.php references 
 * recaptchalib.php as an include file which provides all the web service plumbing 
 * to connect and serve up the CAPTCHA image and verify we have a human entering data.
 *
 * See document in package for installation instructions.
 *
 * @package nmCAPTCHA
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 2.0 2013/01/28
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see contact_include.php 
 * @see recaptchalib.php
 * @see util.js 
 * @todo none
 */

# For each customer/domain, get a key from http://www.google.com/recaptcha/whyrecaptcha (DON'T LET A CUSTOMER USE YOUR KEY) 
# edison ONLY reCAPTCHA keys are below:
$publickey = "6Lf8FMkSAAAAAIR0DTQO4f0zjP-hlyBVcVTjRNB-";
$privatekey = "6Lf8FMkSAAAAAKsfveeLDuVJBWTNOm8PvRqL9lNm";

# zephir ONLY reCAPTCHA keys are below:
//$publickey = "6Ld11wYAAAAAAMqyo-I6NvENfuD3VJOXRBLG_8cE";
//$privatekey = "6Ld11wYAAAAAAEbf282RKWoikILiUBE7U1QJzfmO";

#EDIT THE FOLLOWING:
$toAddress = "horsey01@seattlecentral.edu";  //place your/your client's email address here - EDISON/ZEPHIR WILL ONLY EMAIL seattlecentral.edu ADDRESSES!
$toName = "JIMI HENDRIX"; //place your client's name here
$website = "GUITAR GODZ";  //place NAME of your client's website here
$sendEmail = TRUE; //if true, will send an email, otherwise just show user data.
//$header = 'header.php'; #UNCOMMENT & PLACE REFERENCE TO YOUR HEADER FILE HERE
//$footer = 'footer.php'; #UNCOMMENT & PLACE REFERENCE TO YOUR FOOTER FILE HERE
#--------------END CONFIG AREA ------------------------#
$resp = ""; # the response from reCAPTCHA
$error = ""; # the error code from reCAPTCHA, if any
$skipFields = "recaptcha_challenge_field,recaptcha_response_field,Email"; #comma separated list of form elements NOT to store.
$fromDomain = $_SERVER["SERVER_NAME"];
$fromAddress = "noreply@" . $fromDomain; //form always submits from domain where form resides
if(isset($header)){ include $header;}#include header file if provided
include 'include/recaptchalib.php'; #required reCAPTCHA class code
include 'include/contact_include.php'; #complex unsightly code moved here

?>
<script type="text/javascript" src="include/util.js"></script>

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

<!-- CSS class for required field elements.  Move to your CSS? (or not) -->
<style type="text/css">.required {font-style:italic;color:#FF0000;font-weight:bold;}</style>

<form action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return checkForm(this);">
<?php
if (isset($_POST["recaptcha_response_field"]))
{# Check for reCAPTCHA response
    $resp = recaptcha_check_answer ($privatekey,$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
	if ($resp->is_valid)
	{#reCAPTCHA agrees data is valid (PROCESS FORM & SEND EMAIL)
         handle_POST($skipFields,$sendEmail,$toName,$fromAddress,$toAddress,$website,$fromDomain);#process form elements, format and send email.
        
         #Here we can enter the data sent into a database in a later version of this file
?>
        <!-- format HTML here to be your 'thank you' message -->
		<div align="center"><h2>Your Comments Have Been Received!</h2></div>
        <div align="center">Thanks for the input!</div>
        <div align="center">We'll respond via email within 48 hours, if you requested information.</div>
            
<?php
    }else{#reCATPCHA response says data not valid - prepare for feedback
            $error = $resp->error;
            send_POSTtoJS($skipFields); #function for sending POST data to JS array to reload form elements
    }
}

#show form, either for first time, or if data not valid per reCAPTCHA    
if(!isset($_POST["recaptcha_response_field"])|| $error != "")
{#separate code block to deal with returning failed data, or no data sent yet	
	?>
	<!-- below change the HTML to accommodate your form elements - only 'Name' & 'Email' are significant -->
	<div align="center"><h2>Contact Us</h2></div>
	<div align="center">Your opinion is very important!</div>
	<div align="center"><span class="required">(*required)</span></div>
	<table border="1" style="border-collapse:collapse" align="center">
		<tr><!-- the form elements 'Name' and 'Email' are sigificant to the app, any others can be added/deleted -->
			<td align="right"><span class="required">*</span>Name:</td>
			<td><input type="text" name="Name" required="true" placeholder="Name" title="Name is required" /></td>
		</tr>
		<tr><td align="right"><span class="required">*</span>Email:</td>
			<td><input type="email" name="Email" required="true" placeholder="Email" title="A valid email is required" /></td>
		</tr>
		<tr><td align="right">How Did You Hear About Us?</td>
			<td>
				<select name="How_Did_You_Hear_About_Us?">
					<option value="">Choose How You Heard</option>
					<option value="Phone">Phone</option>
					<option value="Web">Web</option>
					<option value="Magazine">Magazine</option>
					<option value="A Friend">A Friend</option>
					<option value="Other">Other</option>
				</select>
			</td>
		</tr>
		<tr><td align="right">What Services Are You Interested In?:</td>
			<td>
				<input type="checkbox" name="Interested_In[]" value="New Website" /> New Website <br />
				<input type="checkbox" name="Interested_In[]" value="Website Redesign" /> Website Redesign <br />
				<input type="checkbox" name="Interested_In[]" value="Special Application" /> Special Application <br />
				<input type="checkbox" name="Interested_In[]" value="Lollipops" /> Complimentary Lollipops <br />
				<input type="checkbox" name="Interested_In[]" value="Other" /> Other <br />
			</td>
		</tr>
		<tr>
			<td align="right">Would You Like To Join Our Mailing List?</td>
			<td>
				<input type="radio" name="Join_Mailing_List?" value="Yes" /> Yes <br />
				<input type="radio" name="Join_Mailing_List?" value="No" /> No <br />
			</td>
		</tr>
		<tr><td align="right">Comments:</td>
			<td><textarea name="Comments" cols="40" rows="4" wrap="virtual"></textarea></td>
		</tr>
		<tr><!-- reCAPTCHA icon appears here: -->
			<td colspan="2" align="center"><?php echo recaptcha_get_html($publickey, $error); ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" value="submit" /></td>
		</tr>
    </table>
    </form>
<?php
}
if(isset($footer)){ include $footer;}#include footer file if provided
?>