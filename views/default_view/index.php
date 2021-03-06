<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="REGISTRATION FORM">
    <meta name="author" content="Hristo">
    <link rel='stylesheet' id='twentytwelve-fonts-css'  href='https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext' type='text/css' media='all' />


    <title>REGISTRATION FORM</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <link href="styles.css" rel="stylesheet">

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
  
  <?php
         // define variables and set to empty values
         $emailErr = $passwordErr = $phoneErr = "";
         $email = $password = $phone = "";
         
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            if (empty($_POST["email"])) {
               $emailErr = "Email is required";
            }else {
               $email = test_input($_POST["email"]);
               
               // check if e-mail address is well-formed
               if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  $emailErr = "Invalid email format"; 
               }
            }
            
            if (empty($_POST["password"])) {
               $password = "";
            }else {
               $password = test_input($_POST["password"]);
            }
            
            if (empty($_POST["phone"])) {
               $phone = "";
            }else {
               $phone = test_input($_POST["phone"]);
            }
           
         }
         
         function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
         }
      ?>
     

<div class="container-fluid rblue">

      <div class="container">
       
		  <a class="nb" href="index.html">REGISTRATION FORM</a>
          
        </div>
        
      </div>
    
<div class="container sr" style="padding-top:100px;">
<h1 class="text-center">Please, Fill-in the Registration Form</h1>

<br />
<br />

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email">
	<span class = "error">* <?php echo $emailErr;?></span>
<br><br>
  </div>
  <div class="form-group">
    <label for="password">Password:</label>
    <input type="password" class="form-control" id="password">
	<span class = "error">* <?php echo $passwordErr;?></span>
  </div>
  <div class="form-group">
    <label for="phone">Phone:</label>
    <input type="phone" class="form-control" id="phone">
	<span class = "error">* <?php echo $phoneErr;?></span>
  </div>
  <button type="submit" class="btn btn-lg btn-primary">Submit</button>
</form>

   </div> 
   
   
   
  </body>
</html>
