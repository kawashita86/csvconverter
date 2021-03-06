<?php
include_once('config/config.php');

$main_page = '';
$cell_type = CellType::getAll();
$cell_conversion = CellConversion::getAll();

if(Tools::getValue('id_template')){
    $template = new Template((int)Tools::getValue('id_template'));
    $cell_made = $template->getAllCells();
}

include_once('header.php');
?>
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3><?php if(!isset($template)){ echo 'Aggiungi';} else { echo 'Modifica';} ?> Templates</h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" id="template_form" autocomplete="off" action="save_template.php" method="post">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Dettagli Template</legend>
                <div class="form-group">
                    <label for="template_name" class="col-md-2 control-label">Nome</label>
                    <div class="col-md-10">
                        <input type="text" value="<?php if(isset($template)){ echo $template->name; } ?>" class="form-control" id="template_name" name="template_name" required />
                    </div>
                </div>
                    <div class="form-group">
                        <label for="template_name" class="col-md-2 control-label">Descrizione</label>
                        <div class="col-md-10">
                            <textarea  class="form-control" id="template_description" name="template_description"><?php if(isset($template)){ echo $template->description; } ?></textarea>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="separator" class="col-md-2 control-label">Separatore</label>
                    <div class="col-md-10">
                        <select class="form-control" id="separator" name="separator" required >
                            <option value="">Scegli</option>
                            <option value="," <?php echo (isset($template) && $template->separator == ',')? 'selected' : ''; ?>>,</option>
                            <option value=";" <?php echo (isset($template) && $template->separator == ';')? 'selected' : ''; ?>>;</option>
                            <option value="t" <?php echo (isset($template) && $template->separator == 't')? 'selected' : ''; ?>>Tab</option>
                            <option value="|" <?php echo (isset($template) && $template->separator == '|')? 'selected' : ''; ?>>|</option>
                            <option value="-" <?php echo (isset($template) && $template->separator == '-')? 'selected' : ''; ?>>-</option>
                        </select>
                        <p class="help-block">valore che separa i singoli campi del csv ( "|", ",", ... ).</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="heading_lines" class="col-md-2 control-label">Linee Intestazione</label>
                    <div class="col-md-10">
                        <select class="form-control" id="heading_lines" name="heading_lines" required>
                            <option value="0" <?php if(isset($template) && $template->line_header == 0){ echo 'selected'; } ?>>0</option>
                            <option value="1" <?php if(isset($template) && $template->line_header == 1){ echo 'selected'; } ?>>1</option>
                        </select>
                        <p class="help-block">numero di righe che compongono l'intestazione del csv.</p>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="set_bom" class="col-md-2 control-label">Codifica BOM</label>
                        <div class="col-md-10">
                            <select class="form-control" id="set_bom" name="set_bom" >
                                <option value="1" <?php echo (isset($template) && $template->set_bom == "1")? 'selected' : ''; ?>>Si</option>
                                <option value='0' <?php echo (isset($template) && $template->set_bom == '0')? 'selected' : ''; ?>>No</option>
                            </select>
                            <p class="help-block">Se desideri utilizzare la codifica Byte Order Mark (BOM).</p>
                        </div>
                    </div>
                <div class="form-group">
                    <label for="text_container" class="col-md-2 control-label">Contenitore testo</label>
                    <div class="col-md-10">
                        <select class="form-control" id="text_container" name="text_container" >
                            <option value="n" <?php echo (isset($template) && $template->text_container == "n")? 'selected' : ''; ?>>Automatico</option>
                            <option value='"' <?php echo (isset($template) && $template->text_container == '"')? 'selected' : ''; ?>>"</option>
                            <option value="'" <?php echo (isset($template) && $template->text_container == "'")? 'selected' : ''; ?>>'</option>
                        </select>
                        <p class="help-block">Se ogni elemento testuale è contenuto in particolari tag es. ("", '') .</p>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="concatenation_char" class="col-md-2 control-label">Carattere concatenzione</label>
                        <div class="col-md-10">
                            <select class="form-control" id="concatenation_char" name="concatenation_char" >
                                <option value="n" <?php echo (isset($template) && $template->concatenation_char == "n")? 'selected' : ''; ?>>Nessuno</option>
                                <option value='¶' <?php echo (isset($template) && $template->concatenation_char == '¶')? 'selected' : ''; ?>>¶</option>
                                <option value="-" <?php echo (isset($template) && $template->concatenation_char == "-")? 'selected' : ''; ?>>-</option>
                                <option value="~" <?php echo (isset($template) && $template->concatenation_char == "~")? 'selected' : ''; ?>>~</option>
                            </select>
                            <p class="help-block">Se desideri concatenare colonne del csv questo è il carattere che verrà utilizzato nell'unione .</p>
                        </div>
                    </div>
               </fieldset>
               <fieldset class="entries-border">
                    <legend >Dettagli Celle</legend>
                <?php if(isset($cell_made) && count($cell_made) > 0) {
                    foreach ($cell_made as $c) {
                        ?>
                        <div class="form-group entry panel-default panel">
                            <div class="col-md-12 panel-body">
                                <div class="form-group row">
                                    <input type="hidden" name="cell_id[]" value="<?php echo $c['id_cell'] ?>"/>
                                    <label for="cell_name[]" class="col-md-1 control-label">Nome</label>

                                    <div class="col-md-3">
                                        <input type="text" class="form-control" placeholder="Name"
                                               name="cell_name[]" value="<?php echo stripslashes($c['name']) ?>">
                                    </div>
                                    <label for="cell_formatting[]" class="col-md-1 control-label">Tipo</label>

                                    <div class="col-md-3">
                                        <select class="form-control"  name="cell_formatting[]">
                                            <option value=""> - Scegli</option>
                                            <?php foreach ($cell_type as $type)
                                                echo '<option value="' . $type['id_cell_type'] . '" ' . ($c['id_type'] == $type['id_cell_type'] ? 'selected' : '') . '>' . $type['name'] . '</option>';
                                            ?>
                                        </select>
                                    </div>
                                    <label for="cell_position[]" class="col-md-1 control-label">Posizione</label>

                                    <div class="col-md-1">
                                        <input type="number" class="form-control" placeholder="0"
                                               name="cell_position[]" value="<?php echo $c['position'] ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-info btn-up" type="button">
                                            <span class="glyphicon glyphicon-arrow-up"></span>
                                        </button>
                                        <button class="btn btn-info btn-down" type="button">
                                            <span class="glyphicon glyphicon-arrow-down"></span>
                                        </button>
                                        <button class="btn btn-danger btn-remove" type="button">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 formatting-extension hide-fixed" <?php echo $c['id_type'] != 5 && $c['id_type'] != 6 ? 'style="display:none"': '' ?>>
                                <div class="form-group row">
                                    <div class="col-md-2 col-md-offset-1">
                                        <span class="label label-info">Impostazioni avanzate</span>
                                    </div>
                                    <label for="cell_name[]" class="col-md-2 control-label"><?php echo $c['id_type'] == 6 ? 'Concatenazione' : 'Valore Fisso'; ?></label>
                                    <div class="col-md-3">
                                        <input type="text" value="<?php echo $c['fixed_value'] ?>" name="special_value[]" class="form-control" placeholder="Inserisci valore" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 formatting-extension price-extension" <?php echo $c['id_type'] != 2 && $c['id_type'] != 7 ? 'style="display:none"': '' ?>>
                                <div class="form-group row">
                                    <div class="col-md-2 col-md-offset-1">
                                        <span class="label label-info">Impostazioni avanzate</span>
                                    </div>
                                    <label for="cell_name[]" class="col-md-2 control-label">Numero Decimali</label>
                                    <div class="col-md-1">
                                        <select class="form-control" name="price_round[]">
                                            <option value="0">-</option>
                                            <option value="2" <?php echo ($c['price_round'] == 2 ? 'selected' : ''); ?>>2 </option>
                                            <option value="3" <?php echo ($c['price_round'] == 3 ? 'selected' : ''); ?>>3</option>
                                            <option value="4" <?php echo ($c['price_round'] == 4 ? 'selected' : ''); ?>>4</option>
                                        </select>
                                    </div>
                                    <label for="" class="col-md-2 control-label">Elimina carattere ¤</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="strip_element[]" >
                                            <option value="0" >No</option>
                                            <option value="1" <?php echo ($c['strip_element'] == 1 ? 'selected' : ''); ?>>Si</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 formatting-extension quantity-extension" <?php echo $c['id_type'] != 4 ? 'style="display:none"': '' ?>>
                                <div class="form-group row">
                                    <div class="col-md-2 col-md-offset-1">
                                        <span class="label label-info">Impostazioni avanzate</span>
                                    </div>
                                    <label for="" class="col-md-1 control-label">Arrotondamento</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="quantity_round[]" >
                                            <option value="0" <?php echo ($c['quantity_round'] == 0 ? 'selected' : ''); ?>>Inferiore</option>
                                            <option value="1" <?php echo ($c['quantity_round'] == 1 ? 'selected' : ''); ?>>Superiore</option>
                                        </select>
                                    </div>
                                    <label for="" class="col-md-2 control-label">Valori negativi a 0</label>
                                    <div class="col-md-2">
                                        <select class="form-control" name="no_negative[]" >
                                            <option value="0" >No</option>
                                            <option value="1" <?php echo ($c['no_negative'] == 1 ? 'selected' : ''); ?>>Si</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
                <!-- row element for each field from now on -->
                <div class="form-group entry panel-default panel">
                    <div class="col-md-12 panel-body">
                        <div class="form-group row">
                            <input type="hidden" name="cell_id[]" value="" />
                            <label for="cell_name[]" class="col-md-1 control-label">Nome</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Name" name="cell_name[]">
                            </div>
                            <label for="cell_formatting[]" class="col-md-1 control-label">Tipo</label>
                            <div class="col-md-3">
                                <select class="form-control" name="cell_formatting[]" >
                                    <option value=""> - Scegli </option>
                                    <?php foreach($cell_type as $type)
                                        echo '<option value="'.$type['id_cell_type'].'">'.$type['name'].'</option>';
                                    ?>
                                </select>
                            </div>
                            <label for="cell_position[]" class="col-md-1 control-label">Posizione</label>
                            <div class="col-md-1">
                                <input type="number" class="form-control" placeholder="0" name="cell_position[]">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-info btn-up" type="button">
                                    <span class="glyphicon glyphicon-arrow-up"></span>
                                </button>
                                <button class="btn btn-info btn-down" type="button">
                                    <span class="glyphicon glyphicon-arrow-down"></span>
                                </button>
                                <button class="btn btn-success btn-add" type="button">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 formatting-extension hide-fixed" style="display:none;">
                        <div class="form-group row">
                            <div class="col-md-2 col-md-offset-1">
                                <span class="label label-info">Impostazioni avanzate</span>
                            </div>
                            <label for="cell_name[]" class="col-md-2 control-label">Valore Fisso</label>
                            <div class="col-md-3">
                                <input type="text" value="" name="special_value[]" class="form-control" placeholder="Inserisci valore" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 formatting-extension price-extension" style="display:none;">
                        <div class="form-group row">
                            <div class="col-md-2 col-md-offset-1">
                                <span class="label label-info">Impostazioni avanzate</span>
                            </div>
                            <label for="cell_name[]" class="col-md-2 control-label">Numero Decimali</label>
                            <div class="col-md-1">
                                <select class="form-control" name="price_round[]">
                                    <option value="">0</option>
                                    <option value="2">2 </option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                            </div>
                            <label for="" class="col-md-2 control-label">Elimina carattere ¤</label>
                            <div class="col-md-2">
                                <select class="form-control" name="strip_element[]" >
                                    <option value="0" >No</option>
                                    <option value="1" >Si</option>
                                </select>
                            </div>
                    </div>
                </div>
                    <div class="col-md-12 formatting-extension quantity-extension" style="display:none">
                        <div class="form-group row">
                            <div class="col-md-2 col-md-offset-1">
                                <span class="label label-info">Impostazioni avanzate</span>
                            </div>
                            <label for="" class="col-md-1 control-label">Arrotondamento</label>
                            <div class="col-md-2">
                                <select class="form-control" name="quantity_round[]" >
                                    <option value="0">Inferiore</option>
                                    <option value="1">Superiore</option>
                                </select>
                            </div>
                            <label for="" class="col-md-2 control-label">Valori negativi a 0</label>
                            <div class="col-md-2">
                                <select class="form-control" name="no_negative[]" >
                                    <option value="0" >No</option>
                                    <option value="1" >Si</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                </fieldset>
                <input type="hidden" name="id_template" value="<?php if(isset($template)) { echo $template->id; } ?>" />
                <input type="hidden" name="submitTemplate" value="1"/>
            </form>
        </div>
        <div class="col-md-12">
            <a class="btn btn-default" href="templates.php" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
            <button type="button" class="btn btn-info" name="submitTemplate" onclick="$('#template_form').trigger('submit');"><i class="glyphicon glyphicon-floppy-disk"></i> Salva</button>
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
