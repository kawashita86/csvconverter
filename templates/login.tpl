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

<body  data-spy="scroll" data-target=".scrollspy" >
<div class="container theme-showcase" role="main">

    <div class="row">
        <div class="page-header">
            <h2>Login</h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        {if isset($errors) && $errors}
                            <div class="alert alert-danger">
                                <ol>
                                    {foreach from=$errors key=k item=error}
                                        <li>{$error}</li>
                                    {/foreach}
                                </ol>
                            </div>
                        {/if}
                        <form class="form-horizontal" role="form" method="POST" action="{$link->getPageLink('index')}">
                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" value="1"> Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <input type="submit" class="btn btn-primary" style="margin-right: 15px;" name="loginMe" value="Login"/>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl" title=footer}
</body>
</html>