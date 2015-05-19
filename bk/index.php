<?php
include_once('config/config.php');

$main_page = 'index';
$templates = Template::getAll();
include_once('header.php');
?>

<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>
            CSV Converter
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-list fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"><?php echo count($templates) ?></div>
                            <div>Gestisci Templates</div>
                        </div>
                    </div>
                </div>
                <a href="templates.php">
                    <div class="panel-footer">
                        <span class="pull-left">Vai alla pagina</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-arrow-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-bullhorn fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">&nbsp;</div>
                            <div>Converti CSV</div>
                        </div>
                    </div>
                </div>
                <a href="conversion.php">
                    <div class="panel-footer">
                        <span class="pull-left">Vai alla pagina</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-arrow-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-cog fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">&nbsp;</div>
                            <div>Impostazioni</div>
                        </div>
                    </div>
                </div>
                <a href="impostazioni.php">
                    <div class="panel-footer">
                        <span class="pull-left">Vai alla pagina</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-arrow-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-book fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">&nbsp;</div>
                            <div>Istruzioni</div>
                        </div>
                    </div>
                </div>
                <a href="istruzioni.php">
                    <div class="panel-footer">
                        <span class="pull-left">Vai alla pagina</span>
                        <span class="pull-right"><i class="glyphicon glyphicon-arrow-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div><!-- /.container -->
<?php include_once('footer.php'); ?>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="js/template.js"></script>
</body>
</html>
