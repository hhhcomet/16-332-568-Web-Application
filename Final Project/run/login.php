<!-- written by Rong Zhang
     debugged by Jingxuan Chen
     assisted by Feng Rong -->
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

$username=$password="";
if(isset($_POST["submit"]))
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $password= test_input($_POST["password"]);
            $username=test_input($_POST["username"]);
        }
        //Insert the user
        $check_query = mysql_query("SELECT * from UserInfo where username='$username' and password='$password'"); 
        if ($check_query)
         {   
            $row=mysql_fetch_array($check_query);
            print_r(mysql_fetch_array($check_query));
            echo "1";
            if($row)
            {  
                $_SESSION["username"]=$username;
                $_SESSION["password"]=$password;
                if(!empty($_POST["remember"]))
                {
                    //echo "<script>alert('Y1111')</script>";
                    $_SESSION["username"]=$username;
                    $_SESSION["password"]=$password;
                }
                echo "<script>alert('You have successfully logged in!');location.href='index.php';</script>";
            }
            else
            {
                echo "<script>alert('Your Account is not right!');location.href='login.php';</script>";
            }
        }
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
        <div class="" id="loginModal">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Already have an account?</h3>
              </div>
              <div class="modal-body">
                <div class="well">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#login" data-toggle="tab">Log in</a></li>
                    <li><a href="signup.php" data-toggle="tab">Sign up</a></li>
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div class="tab-pane active in" id="login">
                      <form class="form-horizontal" action='' method="POST">
                        <fieldset>
                          <div id="legend">
                            <legend class="">Log in</legend>
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
                              <button type="submit" name="submit" class="btn btn-success">Log me in!</button>
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