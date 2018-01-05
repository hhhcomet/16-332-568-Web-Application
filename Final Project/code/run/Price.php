<!-- written by Jingxuan Chen
     Debugged by Xiuqi Ye
     Assisted by Feng Rong -->
<?php 
exec("se_project_prediction_ann.exe");
exec("Bayesian.exe");
session_start();
@$username=$_SESSION["username"];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Price Prediction-Forecaster</title>

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
		$file=fopen("ANN_prediction.csv","r");
		 for ($i=0;$i<10;$i++)
		 {
		 	$data = fgetcsv($file,1000,",");
		 	$num = count($data);
		 	for ($b=0;$b < $num; $b++)
		 	{
		 		$result[$i]=$data;
		 	} 
		 }
		 fclose($file);
		 $file=fopen("Bayesian_Prediction.csv","r");
		 for ($i=0;$i<11;$i++)
		 {
		 	$data = fgetcsv($file,1000,",");
		 	$num = count($data);
		 	for ($b=0;$b < $num; $b++)
		 	{
		 		$result2[$i]=$data;
		 	} 
		 }
		 fclose($file);
		 $file=fopen("svm_predict.csv","r");
		 for ($i=0;$i<10;$i++)
		 {
		 	$data = fgetcsv($file,1000,",");
		 	$num = count($data);
		 	for ($b=0;$b < $num; $b++)
		 	{
		 		$result3[$i]=$data;
		 	} 
		 }
		 fclose($file);
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
				<a class="navbar-brand" href="#"><span>Storck</span>FORECASTER</a>
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
			<li class="active"><a href="index.php"><span class="glyphicon glyphicon-dashboard"></span> Home</a></li>
			<li ><a href="function1.php"><span class="glyphicon glyphicon-dashboard"></span> StockList</a></li>
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
				<li class="active">PricePrediction</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Price Predition</h1>
			</div>
		</div><!--/.row-->
									
		
        
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Your Stocks</div>
					<div class="panel-body">
						<div class="canvas-wrapper">
							    <!-- Title area -->
                        <div class="titleArea" method="post">
                                <div class="button" >
                                 <form action="<?php echo $_SERVER['PHP_SELF']?>" id="validate" class="form" method="post">
                                   <?php
                                    $check_query = mysql_query("SELECT distinct symbol from historicalprices"); 
                                 
                                    while ($row=mysql_fetch_row($check_query))
                                    {
                                        $sub="submit";
                                        echo "<input type=".$sub." name=".$sub." value=".$row[0]." />";
                                    }
                                    ?>
                                    </div>
                        </div>
						</div>
        
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> Prediction Strategy</div>
					<div class="panel-body">
                    <?php
                    if (isset($_POST["submit"]))
                    {
                        for ($i=0;$i<10;$i++)
                        {
                            if ($result3[$i][0]==$_POST["submit"])
                            	//echo $result3[$i][0];
                                $stock=$result3[$i];
                        }
                    ?>
                <h4>Short Term(1 day): SVM</h4>
				 <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
				 <h5>The prediction of today's close price:</h5>

                <thead>
                    <tr>
                        <td>Ticker</td>
						<td>Last Close Price</td>
						<td>Prediction Price</td>
                    </tr>
                </thead>
                <tbody>
				<?php 			 
                    echo "<tr>";
                       echo   "<td> ".$stock[0]." </td>";
                       echo "<td> ".$stock[1]. "</td>";
                       echo "<td> ".$stock[2]. "</td>";
					   echo "</tr>";
                  ?>
                </tbody>
                </table>
                <?php
                }
                ?>
					</div>
				</div>
                <div class="panel panel-default">
					<div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> Prediction Strategy</div>
					<div class="panel-body">
                    <?php
                    if (isset($_POST["submit"]))
                    {
                        for ($i=1;$i<11;$i++)
                        {
                            if ($result2[$i][1]==$_POST["submit"])
                                $stock2=$result2[$i];
                        }
                    ?>
                <h4>Short Term(1 day): Bayesian</h4>
				 <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
				 <h5>The prediction of today's close price:</h5>
                <thead>
                    <tr>
                        <td>Ticker</td>
						<td>Predict Price Next Day</td>
                    </tr>
                </thead>
                <tbody>
				<?php 			 
                    echo "<tr>";
                       echo   "<td> ".$stock2[1]." </td>";
                       echo "<td> ".$stock2[2]. "</td>";
					   echo "</tr>";
                  ?>
                </tbody>
                </table>
                <?php
                }
                ?>
					</div>
				</div>
                <div class="panel panel-default">
					<div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> Prediction Strategy</div>
					<div class="panel-body">
                    <?php
                    if (isset($_POST["submit"]))
                    {
                        for ($i=0;$i<10;$i++)
                        {
                            if ($result[$i][1]==$_POST["submit"])
                                $stock3=$result[$i];
                        }
                    ?>
            <h4 >Long Term: Neural Network</h4>
			<h5>The prediction price for the next 7 days to <?php echo $stock3[1] ?> are </h5>
                 <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
                <thead>
                    <tr>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
						<td>4</td>
						<td>5</td>
						<td>6</td>
						<td>7</td>
                    </tr>
                </thead>
                <tbody>
				<?php 			 
                    echo "<tr>";
                       echo "<td> ".$stock3[2]." </td>";
                       echo "<td> ".$stock3[3]." </td>";
                       echo "<td> ".$stock3[4]." </td>";
                       echo "<td> ".$stock3[5]." </td>";
                       echo "<td> ".$stock3[6]." </td>";
                       echo "<td> ".$stock3[7]." </td>";
                       echo "<td> ".$stock3[8]." </td>";
					   echo "</tr>";
                  ?>
                </tbody>
				</table>
                <?php
                    }
                ?>
					</div>
				</div>
			</div><!--/.col-->         
		</div><!--/.row-->
	</div>	<!--/.main-->
		  



