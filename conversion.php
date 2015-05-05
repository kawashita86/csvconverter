<?php
include_once('config/config.php');

$main_page = 'conversion';
$templates = Template::getAll();
include_once('header.php');
?>
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>Converti CSV</h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" data-toggle="validator" id="template_form" autocomplete="off" action="conversion_exec.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="template_name" class="col-md-2 control-label">Template</label>
                    <div class="col-md-9">
                        <select class="form-control" id="template_name" name="template_name" required>
                            <option value="">Select a template</option>
                            <?php if(!empty($templates)){
                                foreach($templates as $template){ ?>
                                <option value="<?php echo $template['id_template'] ?>"><?php echo $template['name'] ?></option>
                            <?php } }?>
                        </select>
                    </div>

                    <div class="col-md-1">
                            <a class="btn btn-success btn-add" href="templates_manage.php">
                                <span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </div>
                </div>
                <div class="form-group">
                    <label for="separator" class="col-md-2 control-label">File Format</label>
                    <div class="col-md-10">
                        <select class="form-control" id="file_type" name="file_type">
                            <option value="csv">CSV</option>
                            <option value="txt">TXT</option>
                        </select>
                        <p class="help-block">formato del file in cui esportare.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text_container" class="col-md-2 control-label">File Upload</label>
                    <div class="col-md-10">
                        <input type="file" value="" name="file" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-11 col-md-offset-1">
                        <input type="submit" class="btn btn-info" name="submitConversion" value="Converti">
                    </div>
                </div>
            </form>
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
