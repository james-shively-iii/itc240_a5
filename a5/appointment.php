<?php include 'includes/header1.php'?>

<hr class="divider">
    <h2 class="text-center text-lg text-uppercase my-0">Appointment <strong>Form</strong></h2>
<hr class="divider">

<?php
    
//this will change to the client's email  
    $to = 'james.shively-iii@seattlecentral.edu';

if(isset($_POST["FirstName"]))
{//if data, show it
    
    $FirstName = clean_post('FirstName');
    $LastName = clean_post('LastName');
    $Email = clean_post('Email');
    //$Comments = clean_post('Comments');
    
    $myText = process_post();
    
    /*
    $myText = "The user has entered their information as follows:" . PHP_EOL . PHP_EOL; //double newlines 
    $myText .= $FirstName . " " . $LastName . PHP_EOL;
    $myText .= $Comments . PHP_EOL;
    */
    
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
    /*
    Radio buttons
    
    Appointment type
    
    Intake
    Degree audit
    Registration
    
    Check boxes
    
    Special Requests
    
    Online meeting
    Early morning
    Official transcripts

    Appointment Date
    
    */
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
            
            <div class="form-group col-lg-4">
              <label class="text-heading">Appointment Type</label>
              <br />
              <input type="radio" name="Appointment_Type" value="Intake" /> Intake <br />
              <input type="radio" name="Appointment_Type" value="Degree Audit" /> Degree Audit <br />
              <input type="radio" name="Appointment_Type" value="Registration" /> Registration <br />
            </div>
            
            <div class="form-group col-lg-4">
              <label class="text-heading">Special Requests</label>
              <br />
              <input type="checkbox" name="Special_Requests[]" value="Online Meeting" /> Online Meeting <br />
              <input type="checkbox" name="Special_Requests[]" value="Early Morning" /> Early Morning <br />
              <input type="checkbox" name="Special_Requests[]" value="Official Transcript" /> Official Transcript <br />
            </div>
            
            <div class="form-group col-lg-4">
              <label class="text-heading">Appointment Date/Time</label>
              <input type="date" name="Appointment_Time" required class="form-control">
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
    
?>
<?php 

include 'includes/footer1.php';

function clean_post($key){
    
    if(isset($_POST[$key])){
        return strip_tags(trim($_POST[$key]));
     }else{
        return '';
    }
 
}

function process_post()
{//loop through POST vars and return a single string
    $myReturn = ''; //set to initial empty value

    foreach($_POST as $varName=> $value)
    {#loop POST vars to create JS array on the current page - include email
         $strippedVarName = str_replace("_"," ",$varName);#remove underscores
        if(is_array($_POST[$varName]))
         {#checkboxes are arrays, and we need to collapse the array to comma separated string!
             $myReturn .= $strippedVarName . ": " . implode(",",$_POST[$varName]) . PHP_EOL;
         }else{//not an array, create line
             $myReturn .= $strippedVarName . ": " . $value . PHP_EOL;
         }
    }
    return $myReturn;
} 

