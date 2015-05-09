<?php
include_once('config/config.php');

$main_page = 'impostazioni';
Configuration::loadConfiguration();
include_once('header.php');
?>
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>
             Impostazioni
        </h3>
    </div>
    <div class="row">
            <div class="col-md-12 template-form-container">
                <form class="form-horizontal" role="form" data-toggle="validator" id="setting_form" autocomplete="off" action="save_impostazioni.php" method="post">
                    <div class="form-group">
                        <label for="HEADER_LINE" class="col-md-2 control-label">Formato File Importato</label>
                        <div class="col-md-10">
                            <select class="form-control" id="IMPORT_FILE_TYPE" name="IMPORT_FILE_TYPE" required>
                                <option value="csv" <?php if(Configuration::get('IMPORT_FILE_TYPE') == 'csv'){ echo 'selected'; } ?>>csv</option>
                                <option value="txt" <?php  if(Configuration::get('IMPORT_FILE_TYPE') == 'txt'){ echo 'selected'; } ?>>txt</option>
                                <option value="ods" <?php  if(Configuration::get('IMPORT_FILE_TYPE') == 'ods'){ echo 'selected'; } ?>>ods</option>
                            </select>
                            <p class="help-block">numero di righe che compongono l'intestazione del csv.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="HEADER_LINE" class="col-md-2 control-label">Linee Intestazione</label>
                        <div class="col-md-10">
                            <select class="form-control" id="HEADER_LINE" name="HEADER_LINE" required>
                                <option value="0" <?php if(Configuration::get('HEADER_LINE') == 0){ echo 'selected'; } ?>>0</option>
                                <option value="1" <?php  if(Configuration::get('HEADER_LINE') == 1){ echo 'selected'; } ?>>1</option>
                            </select>
                            <p class="help-block">numero di righe che compongono l'intestazione del csv.</p>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="SEPARATOR" class="col-md-2 control-label">Separatore</label>
                            <div class="col-md-10">
                                <select class="form-control" id="SEPARATOR" name="SEPARATOR" required >
                                    <option value="">Scegli</option>
                                    <option value="," <?php echo (Configuration::get('SEPARATOR') == ',')? 'selected' : ''; ?>>,</option>
                                    <option value=";" <?php echo (Configuration::get('SEPARATOR') == ';')? 'selected' : ''; ?>>;</option>
                                    <option value="t" <?php echo (Configuration::get('SEPARATOR') == 't')? 'selected' : ''; ?>>Tab</option>
                                    <option value="|" <?php echo (Configuration::get('SEPARATOR') == '|')? 'selected' : ''; ?>>|</option>
                                    <option value="-" <?php echo (Configuration::get('SEPARATOR') == '-')? 'selected' : ''; ?>>-</option>
                                </select>
                                <p class="help-block">valore che separa i singoli campi del csv ( "|", ",", ... ).</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="TEXT_CONTAINER" class="col-md-2 control-label">Contenitore testo</label>
                            <div class="col-md-10">
                                <select class="form-control" id="TEXT_CONTAINER" name="TEXT_CONTAINER" />
                                    <option value="n" <?php echo (Configuration::get('TEXT_CONTAINER') == "n")? 'selected' : ''; ?>>Nessuno</option>
                                    <option value='"' <?php echo (Configuration::get('TEXT_CONTAINER') == '"')? 'selected' : ''; ?>>"</option>
                                    <option value="'" <?php echo (Configuration::get('TEXT_CONTAINER') == "'")? 'selected' : ''; ?>>'</option>
                                </select>
                                <p class="help-block">Se ogni elemento testuale è contenuto in particolari tag es. ("", '') .</p>
                            </div>
                        </div>
                    <div class="form-group">
                        <label for="NEW_LINE" class="col-md-2 control-label">Separatore di righe</label>
                        <div class="col-md-10">
                            <select class="form-control" id="NEW_LINE" name="NEW_LINE" >
                                <option value="\r\n" <?php echo Configuration::get('NEW_LINE') == '\r\n' ? 'selected' : ''; ?>>\r\n</option>
                                <option value="\n"  <?php echo Configuration::get('NEW_LINE') == '\n' ? 'selected' : ''; ?>>\n</option>
                            </select>
                            <p class="help-block">la combinazione di caratteri utilizzata per separare le righe</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input type="submit" value="Aggiorna" name="updateImpostazioni" class="btn btn-success" />
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