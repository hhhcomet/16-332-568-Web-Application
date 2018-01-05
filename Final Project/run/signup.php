<!-- written by Rong Zhang
     Debugged by Xiuqi Ye
     Assisted by Feng Rong -->
<?php

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login/Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <style type="text/css">
        
    </style>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
</head>
<body>

<?php


$con=mysql_connect("localhost","root","");
if (!$con)
  {
  die("Could not connect: " . mysql_error());
  }
$db=mysql_select_db("stockdatabase", $con);
if(!$db)
  {
    die("Failed to connect MySQL:". mysql_error());
  }

$username=$email=$password="";
if(isset($_POST["submit"]))
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $email = test_input($_POST["email"]);
            $password= test_input($_POST["password"]);
            $username=test_input($_POST["username"]);
        }
        //Insert the user
        $check_query = mysql_query("INSERT INTO UserInfo Values('$email','$username','$password',NULL,NULL)"); 
         if(!empty($_POST["rembember"]))
        {
        $_SESSION["adminemail"]=$email;
        $_SESSION["adminpassword"]=$password;
        }
        echo "<script>alert('Your have signed up successful! Please log in!');location.href='login.php';</script>";
    }


    function test_input($data) 
    {
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }
?>


  <div class="row">
        <div class="span12">
        <div class="" id="signupModal">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>New user?</h3>
              </div>
              <div class="modal-body">
                <div class="well">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#signup" data-toggle="tab">Sign up</a></li>
                    <li><a href="login.php" data-toggle="tab">Log in</a></li>
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="signup">
                      <form class="form-horizontal" action='' method="POST">
                        <fieldset>
                          <div id="legend">
                            <legend class="">Sign up</legend>
                          </div>   
                          
                          <div class="control-group">
                            <!--Email-->
                            <label class="control-label" for="email">email</label>
                            <div class="controls">
                              <input type="text" id="email" name="email" placeholder="" class="input-xlarge">
                            </div>
                          </div>
                          
                          <div class="control-group">
                            <!-- Username -->
                            <label class="control-label"  for="username">Username</label>
                            <div class="controls">
                              <input type="text" id="username" name="username" placeholder="" class="input-xlarge">
                            </div>
                          </div>
     
                          <div class="control-group">
                            <!-- Password-->
                            <label class="control-label" for="password">Password</label>
                            <div class="controls">
                              <input type="password" id="password" name="password" placeholder="" class="input-xlarge">
                            </div>
                          </div>
     
     
                          <div class="control-group">
                            <!-- Button -->
                            <div class="controls">
                              <button type="submit" name="submit" class="btn btn-success">Sign up</button>
                            </div>
                          </div>
                        </fieldset>
                      </form>                
                    </div
                </div>
              </div>
            </div>
        </div>
  </div>
</div>
<script type="text/javascript">

</script>
</body>
</html>
