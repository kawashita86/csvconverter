<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>CSV Converter</title>

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <!-- Bootstrap core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/base.css" rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><i class="glyphicon glyphicon-retweet"></i> Csv Converter</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php if($main_page == 'index') { echo 'class="active"';} ?>><a href="<?php echo ($main_page == 'index')? '#' : 'index.php' ?>"><i class="glyphicon glyphicon-home"></i> Home</a></li>
                <li <?php if($main_page == 'templates') { echo 'class="active"';} ?>><a href="<?php echo ($main_page == 'templates')? '#' : 'templates.php' ?>"><i class="glyphicon glyphicon-list"></i> Templates</a></li>
                <li <?php if($main_page == 'conversion') { echo 'class="active"';} ?>><a href="<?php echo ($main_page == 'conversion')? '#' : 'conversion.php' ?>"><i class="glyphicon glyphicon-bullhorn"></i> Conversioni</a></li>
                <li <?php if($main_page == 'impostazioni') { echo 'class="active"';} ?>><a href="<?php echo ($main_page == 'impostazioni')? '#' : 'impostazioni.php' ?>"><i class="glyphicon glyphicon-cog"></i> Impostazioni</a></li>
                <li <?php if($main_page == 'istruzioni') { echo 'class="active"';} ?>><a href="<?php echo ($main_page == 'istruzioni')? '#' : 'istruzioni.php' ?>"><i class="glyphicon glyphicon-list"></i> Istruzioni</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>