<?php 
//Written by: Xiaoyi Tang
//Assisted by Jingxuan Chen
//Debug by: Zhe Chang


session_start();
include_once "search.php";
@$username=$_SESSION["username"];
date_default_timezone_set('America/New_York');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stock-Forecaster</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
    <?php
    // Connect to the database
    $conn = @mysql_connect("localhost","root","");
    if (!$conn){
        die("Failed to connect database£º" . mysql_error());
    }
    $db=mysql_select_db("stockdatabase", $conn);
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
				<a class="navbar-brand" href="index.php"><span>Stock</span>FORECASTER</a>
                <span>
				<ul class="user-menu">
					<li class="dropdown pull-right">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php 
                            if ($username == null)
                                $username = User;
                            echo $username;
                    ?> <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#"><span class="glyphicon glyphicon-user"></span> Profile</a></li>
							<li><a href="#"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
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
			<li class="active"><a href="function1.php"><span class="glyphicon glyphicon-dashboard"></span> StockList</a></li>
            <li class="parent ">
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
			<li><a href="Email.php"><span class="glyphicon glyphicon-list-alt"></span> Email</a></li>
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
				<li><a href="#"><span class="glyphicon glyphicon-home"></span></a></li>
				<li class="active">StockList</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"></h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<em class="glyphicon glyphicon-user glyphicon-l"></em>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large">10</div>
							<div class="text-muted">Stock</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Stock List with Real-time Price</div>
					<div class="panel-body">
                     <div class="widget">
                          <div class="title"><h6>Latest Prices</h6></div>
                            <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
                                <thead>
                                    <tr>
                                        <td>Symbol</td>
                                       
                                        <td>Latest Price</td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                 $check_query=mysql_query("SELECT distinct Symbol FROM RealtimePrice");
                                 while ($row=mysql_fetch_assoc($check_query))
                                 {

                                    echo "<tr>";
                                     echo   "<td> ".$row["Symbol"]." </td>";
                                     $today=strtotime("Today");
                                     $date1=date("Y/m/d",$today);
                                     $time= mysql_query("SELECT MAX(time) FROM RealtimePrice WHERE Symbol=\"{$row['Symbol']}\" AND Date=\"{$date1}\"");
                                     $time=mysql_result($time,0);
                                     $query = mysql_query("SELECT Price FROM RealtimePrice WHERE Symbol=\"{$row['Symbol']}\" AND Time=\"{$time}\"");
                                     $price =  mysql_result($query,0);
                                     echo "<td> ". $price ."</td>";
                                       echo "</tr>";
                                 }

                                  ?>
                                </tbody>
                            </table>
                        </div>
					</div>
				</div>
			</div>
		</div>
        
        		
	</div>	<!--/.main-->

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
		$('#calendar').datepicker({
		});

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
