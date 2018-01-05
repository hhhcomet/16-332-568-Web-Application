<!-- Written by Feng Rong;
     Assisted by Xiaoyi Tang;
     Debugged by Zhang Rong; -->
<?php 
session_start();
@$username=$_SESSION["username"];
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Email Page</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/bootstrap-table.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
		<?php

    // Connect to the database
    $con = @mysql_connect("localhost","root","");
    if (!$con){
        die("Failed to connect database" . mysql_error());
    }
    $db=mysql_select_db("stockdatabase", $con);
    if(!$db)
    {
      die("Failed to connect to MySQL:". mysql_error());
    }
    ?>
		
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><span>Stock</span>Forecaster</a>
                <span>
                <ul class="user-menu">
                    <li class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php 
                            if ($username == null)
                                $username = User;
                            echo $username;
                    ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                        <li><a href="login.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!-- /.container-fluid -->
    </nav>
        
    <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
        <ul class="nav menu">
            <li class=""><a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Home</a></li>
            <li ><a href="function1.php"><span class="glyphicon glyphicon-dashboard"></span> StockList</a></li>
            <li class="parent">
                <a href="#">
                    <span class="glyphicon glyphicon-list"></span> PriceData <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span> 
                </a>
                <ul class="children collapse" id="sub-item-1">
                    <li>
                        <a class="" href="Historical.php">
                            <span class="glyphicon glyphicon-share-alt"></span> Historical
                        </a>
                    </li>
                    <li>
                        <a class="" href="Realtime.php">
                            <span class="glyphicon glyphicon-share-alt"></span> Realtime
                        </a>
                    </li>
                </ul>
            </li>
            <li class="active"><a href="Email.php"><span class="glyphicon glyphicon-list-alt"></span> Email</a></li>
            <li class="parent ">
                <a href="#">
                    <span class="glyphicon glyphicon-list"></span> Indicators <span data-toggle="collapse" href="#sub-item-3" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span> 
                </a>
                <ul class="children collapse" id="sub-item-3">
                    <li>
                        <a class="" href="indicator_VR.php">
                            <span class="glyphicon glyphicon-share-alt"></span> VR
                        </a>
                    </li>
                    <li>
                        <a class="" href="indicator_KDJ.php">
                            <span class="glyphicon glyphicon-share-alt"></span> KDJ
                        </a>
                    </li>
                    <li>
                        <a class="" href="indicator_MACD.php">
                            <span class="glyphicon glyphicon-share-alt"></span> MACD
                        </a>
                    </li>
                </ul>
            </li>
            <li><a href="price.php"><span class="glyphicon glyphicon-list-alt"></span> Price Prediction</a></li>
            <li role="presentation" class="divider"></li>
            <li><a href="login.php"><span class="glyphicon glyphicon-user"></span> Login Page</a></li>
        </ul>
    </div><!--/.sidebar-->
        
        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">	
        <div class="row">
         <ol class="breadcrumb">
         	<li>
         	<a href="#"> <span class ="glyphicon glyphicon-home"></span> </a> 
         	</li>
         	<li class="active">Email</li>
         </ol>
         </div>
         <div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Email</h1>
				
			</div>
		</div><!--/.row-->
		<div>
         	<div class ="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Your Stocks</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
                        <div class="titleArea" method="post">
                                <div class="button" >
                                 <form action="<?php echo $_SERVER['PHP_SELF']?>" id="validate" class="form" method="post">
                                    <?php
                                     $stock=mysql_query("SELECT stock FROM UserInfo WHERE username='$username'");
							         $stock=mysql_result($stock,0);
                                    if ($stock!=NULL)
                                    {
                                        $sub="submit";
                                        echo "<input type=".$sub." name=".$sub." value=".$stock." />";
                                    }
                                    else
                                    {
                                    	echo "You didn't select your stock.";
                                    }
                                    ?>
                                    </div>
                        </div>
						</div>
                        <div class="canvas-wrapper">
					    <?php 
                        //print_r($_POST);
                            if (isset($_POST["add"]))
                            {	
                                $stockname=$_POST["add"];
                                $check_query=mysql_query("SELECT Symbol FROM RealTimePrice where name='$stockname'");
                               $result=mysql_fetch_array($check_query);
                                $symbol=$result[0];
                                mysql_query("INSERT INTO UserInfo VALUES('$username','$symbol')");
                                echo "<script>alert('You have successfully add to the list!');location.href='Email.php';</script>";
                                exit;
                            }
                            if (isset($_POST["search"]) )
                            {
                                ?>
                                <div class="wrapper" >
                                <h2><?php echo $_POST["search"]; ?></h2>
                                <?php
                                    //$check_query=mysql_query("SELECT Description FROM stocks where name='{$_POST["search"]}'");
                                   // $des=mysql_fetch_array($check_query);
                                    //echo $des[0],"</br>";
                                    $search=new search();
                                    $search->connect();
                                    $highest=$search->findhighest($_POST["search"]);
                                    $average=$search->findaverage($_POST["search"]);
                                    $lowest=$search->findlowest($_POST["search"]);
                                    echo "The highest price of ",$_POST["search"]," in the latest one year is ",$highest,"</br>";
                                    echo "The average price of ",$_POST["search"]," in the latest one year is ",$average,"</br>";
                                    echo "The lowest price of ",$_POST["search"]," in the latest one year is ", $lowest,"</br>";

                                ?>
                                <input type="submit" name="add" placeholder="add to list"  value="<?php echo $_POST["search"];?>" />
                                <h6>Press the button to add the stock to the list</h6>
                                <?php
                            }
                            if (isset($_POST["submit"]))
                            {	
                                $_SESSION["stockname"]=$_POST["submit"];
                                ?>
                                    <script>
                             window.open("HistoricalChart.php");
                            </script>
                            <?php
                            }
                            ?>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div><!--/.row-->
                
        <div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">Choose your stock!</div>
					<div class="panel-body">
							<form role="form">
							
								<div class="form-group">
									<label>StockName</label>
									<input class="form-control" placeholder="stockname" name="email_stock">
								</div>
								<div class="form-group">
									<label>Stop Loss Price</label>
									<input type="price" class="form-control" name = "email_price">
								</div>

												
								<button type="submit" class="btn btn-primary" name="email_submit">Submit Button</button>
								<button type="reset" class="btn btn-default">Reset Button</button>
						</form>
					</div>
                </div>
            </div>

        <div class="col-md-6">
            <div class="panel panel-default">
				<div class="panel-heading">Your notification</div>
					<div class="panel-body">
							<form role="form">
							 <?php
							         $_SERVER['PHP_SELF'];
							         $stock=mysql_query("SELECT stock FROM UserInfo WHERE username='$username'");
							         $stock=mysql_result($stock,0);
							         if($stock!=NULL)
							         {
                                     $today=strtotime("Today");
                                     $date1=date("Y/m/d",$today);

                                     $time= mysql_query("SELECT MAX(time) FROM RealtimePrice WHERE Symbol=\"{$stock}\" AND Date=\"{$date1}\"");
                                     $time=mysql_result($time,0);
                                     $query = mysql_query("SELECT Price FROM RealtimePrice WHERE Symbol=\"{$stock}\" AND Time=\"{$time}\""); 
                                     $price =  mysql_result($query,0);
                                        $sub="submit";
                                        echo "You have select ". $stock.", current price is ".$price,".</br>";
                                    }
                                    else
                                    {
                                    	echo "You didn't select any stock.";
                                    }
                                
                            if (isset($_POST["email_submit"]))
                            {	
                               
                                if (isset($_POST["email_stock"]) and isset($_POST["email_price"])){
                                    $email_price=$_POST["email_price"];
                                    $email_stock=$_POST["email_stock"];
                                    $query = mysql_query("UPDATE Userinfo SET stock=\"{$email_stock}\",eprice=\"{$email_price}\" WHERE username = \"{$username}\"")or die($query."<br/><br/>".mysql_error());
                                }
                                echo "<script>alert('You have successfully add notification to database!');location.href='Email.php';</script>";
                                exit;
                                ?>
                            <?php
                            }
                            ?>
						</form>
					</div>
				</div>
			</div><!-- /.col-->
		</div><!-- /.row -->
		
	</div><!--/.main-->

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/bootstrap-table.js"></script>
	<script>
		!function ($) {
			$(document).on("click","ul.nav li.parent > a > span.icon", function(){		  
				$(this).find('em:first').toggleClass("glyphicon-minus");	  
			}); 
			$(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>	
</body>

</html>
