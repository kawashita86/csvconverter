<?php
include_once('config/config.php');

$main_page = 'conversion';
$templates = Template::getAll();

$files = preg_grep('~\.(csv|txt)$~', scandir('import'));

include_once('header.php');
?>
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>Converti CSV</h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" data-toggle="validator" id="conversion_form" autocomplete="off" action="../conversion_exec.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="template_name" class="col-md-3 control-label">Template</label>
                    <div class="col-md-8">
                        <select class="form-control" id="template_name" name="template_name" required>
                            <option value="">Scegli il template</option>
                            <?php if(!empty($templates)){
                                foreach($templates as $template){ ?>
                                <option value="<?php echo $template['id_template'] ?>"><?php echo $template['name'] ?></option>
                            <?php } }?>
                        </select>
                    </div>

                    <div class="col-md-1">
                            <a class="btn btn-success btn-add" href="../templates_manage.php">
                                <span class="glyphicon glyphicon-plus"></span>
                            </a>
                        </div>
                </div>
                <div class="form-group">
                    <label for="separator" class="col-md-3 control-label">Formato file</label>
                    <div class="col-md-9">
                        <select class="form-control" id="file_type" name="file_type" required>
                            <option value="csv">CSV</option>
                            <option value="txt">TXT</option>
                        </select>
                        <p class="help-block">formato del file in cui esportare.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="separator" class="col-md-3 control-label">Encoding</label>
                    <div class="col-md-9">
                        <select class="form-control" id="encoding" name="encoding" required>
                            <option value="default">Default</option>
                            <option value="windows">Windows/Excel</option>
                        </select>
                        <p class="help-block">encoding del file in cui esportare (lasciare default salvo necessità particolari).</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="text_container" class="col-md-3 control-label">Carica un File</label>
                    <div class="col-md-9">
                        <input type="file" value="" name="file" id="new_file" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Oppure</label>
                </div>
                <div class="form-group">
                    <label for="previous_file" class="col-md-3 control-label" >File caricati precedentemente</label>
                    <div class="col-md-9">
                       <select name="previous_files" id="previous_file" class="form-control" >
                           <option value=""> Scegli</option>
                           <?php foreach($files as $file){
                              echo '<option value="'.$file.'">'.$file.'</option>';
                           } ?>
                       </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-11 col-md-offset-1">
                        <a class="btn btn-default" href="../index.php" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
                        <input type="submit" class="btn btn-info" name="submitConversion" value="Converti">
                    </div>
                </div>
            </form>
        </div>
    </div>

</div><!-- /.container -->

<?php include_once('footer.php'); ?>
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../bower_components/bootstrap-validator/dist/validator.min.js"></script>
<!--<script src="js/template.js"></script>-->
</body>
</html>
