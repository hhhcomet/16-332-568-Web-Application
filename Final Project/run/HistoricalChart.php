<!-- Written by Feng Rong
     Assisted by Jingxuan Chen
     Debugged by Zhe Chang -->

<?php 
include_once "jsonHistorical.php";
jsonHistorical();

session_start();
$stockname= $_SESSION["stockname"];


?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Historical</title>

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

<script type="text/javascript">
function HistoricalChart (StockName){
    $(function () {
    var filename= "json/Historical/"+StockName+".json";
    var tittle = StockName+" historical";
    $.getJSON(filename, function (data) {
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
        //echo data[10][0];

        for (i; i < dataLength; i ++) {
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
        $('#container').highcharts('StockChart', {

            rangeSelector: {
                selected: 1
            },

            title: {
                text: tittle
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

            series: [{
                type: 'candlestick',
                name: StockName,
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
});
    
}

        

        </script>
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
                <a class="navbar-brand" href="index.php"><span>Stock</span>Price</a>
                <ul class="user-menu">
                    <li class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> user <span class="caret"></span></a>
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
            <li class="parent ">
                <a href="#">
                    <span class="glyphicon glyphicon-list"></span> PriceData<span data-toggle="collapse" href="#sub-item-1" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span> 
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
                    <span class="glyphicon glyphicon-list"></span> PriceData <span data-toggle="collapse" href="#sub-item-2" class="icon pull-right"><em class="glyphicon glyphicon-s glyphicon-plus"></em></span> 
                </a>
                <ul class="children collapse" id="sub-item-2">
                    <li>
                        <a class="" href="Indicator.php">
                            <span class="glyphicon glyphicon-share-alt"></span> Indicator
                        </a>
                    </li>
                    <li>
                        <a class="" href="Price.php">
                            <span class="glyphicon glyphicon-share-alt"></span> Price
                        </a>
                    </li>
                </ul>
            </li>
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

	
<script src="highstock.js"></script>
<script src="exporting.js"></script>
<script type="text/javascript">
var stockname = "<?php echo $stockname; ?>"; 
HistoricalChart(stockname);
</script>
<div id="container" style="height: 400px; min-width: 310px"></div>
</body>
</html>

