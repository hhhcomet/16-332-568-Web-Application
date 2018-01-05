<?php 
//Written by: Rong Zhang
//Assisted by: Xiuqi Ye
//Debug by: Feng Rong
session_start();
//include_once 'VR.php';
//include_once "search.php";
//include_once 'KDJ.php';
@$username=$_SESSION["username"];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Historical-Forecaster</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery.waterwheelCarousel.js"></script>
<script type="text/javascript" src="js/jquery.waterwheelCarousel.setup.js"></script>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
		<?php

    // Connect to the database
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
            <li class="parent active">
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
				<li class="active">Historical</li>
			</ol>
		</div><!--/.row-->
        
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Historical Price</h1>
				
			</div>
		</div><!--/.row-->
        
        <div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">Choose a stock to check the charts!</div>
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
                        <div class="canvas-wrapper">
                        <?php 
                            include_once "jsonHistorical.php";
                            if (isset($_POST["submit"]))
                            {   
                                $_SESSION["stockname"]=$_POST["submit"];
                                jsonHistorical();
                            }
                            ?>
                        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script type="text/javascript">
    var Stockname= "<?php echo $_POST['submit'] ?>";
    var filename= 'json/Historical/'+Stockname+'.json';
    $.getJSON( filename , function (data) {

    // split the data set into ohlc and volume
    var ohlc = [],
        volume = [],
        dataLength = data.length,
        // set the allowed units for data grouping
        groupingUnits = [[
            'week',                         // unit name
            [1]                             // allowed multiples
        ], [
            'month',
            [1, 2, 3, 4, 6]
        ]],

        i = 0;

    for (i; i < dataLength; i += 1) {
        ohlc.push([
            data[i][0], // the date
            data[i][1], // open
            data[i][2], // high
            data[i][3], // low
            data[i][4] // close
        ]);

        volume.push([
            data[i][0], // the date
            data[i][5] // the volume
        ]);
    }


    // create the chart
    Highcharts.stockChart('container', {

        rangeSelector: {
            selected: 1
        },

        title: {
            text: Stockname+' Historical'
        },

        yAxis: [{
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'OHLC'
            },
            height: '60%',
            lineWidth: 2
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'Volume'
            },
            top: '65%',
            height: '35%',
            offset: 0,
            lineWidth: 2
        }],

        tooltip: {
            split: true
        },

        series: [{
            type: 'candlestick',
            name: Stockname,
            data: ohlc,
            dataGrouping: {
                units: groupingUnits
            }
        }, {
            type: 'column',
            name: 'Volume',
            data: volume,
            yAxis: 1,
            dataGrouping: {
                units: groupingUnits
            }
        }]
    });
});
</script>

<div id="container" style="height: 400px; min-width: 310px"></div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->								
	</div>	<!--/.main-->

    
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/jquery.waterwheelCarousel.js"></script>
    <script type="text/javascript" src="js/jquery.waterwheelCarousel.setup.js"></script>
    

	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
    
    
    
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
