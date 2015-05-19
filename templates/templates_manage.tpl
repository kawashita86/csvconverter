{include file="header.tpl" title=header}

<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>{if !isset($template)}Aggiungi{else}Modifica{/if} Templates</h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" id="template_form" autocomplete="off" action="{$link->getPageLink('templates')}" method="post">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Dettagli Template</legend>
                    <div class="form-group">
                        <label for="template_name" class="col-md-2 control-label">Nome</label>
                        <div class="col-md-10">
                            <input type="text" value="{if isset($template)}{$template->name}{/if}" class="form-control" id="template_name" name="template_name" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="template_name" class="col-md-2 control-label">Descrizione</label>
                        <div class="col-md-10">
                            <textarea  class="form-control" id="template_description" name="template_description">{if isset($template)}{$template->description}{/if}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="separator" class="col-md-2 control-label">Separatore</label>
                        <div class="col-md-10">
                            <select class="form-control" id="separator" name="separator" required >
                                <option value="">Scegli</option>
                                <option value="," {if isset($template) && $template->separator eq ','}selected{/if}>,</option>
                                <option value=";" {if isset($template) && $template->separator eq ';'}selected{/if}>;</option>
                                <option value="t" {if isset($template) && $template->separator eq 't'}selected{/if}>Tab</option>
                                <option value="|" {if isset($template) && $template->separator eq '|'}selected{/if}>|</option>
                                <option value="-" {if isset($template) && $template->separator eq ':'}selected{/if}>-</option>
                            </select>
                            <p class="help-block">valore che separa i singoli campi del csv ( "|", ",", ... ).</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="heading_lines" class="col-md-2 control-label">Linee Intestazione</label>
                        <div class="col-md-10">
                            <select class="form-control" id="heading_lines" name="heading_lines" required>
                                <option value="0" {if isset($template) && $template->line_header eq '0'}selected{/if}>0</option>
                                <option value="1" {if isset($template) && $template->line_header eq '1'}selected{/if}>1</option>
                            </select>
                            <p class="help-block">numero di righe che compongono l'intestazione del csv.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="set_bom" class="col-md-2 control-label">Codifica BOM</label>
                        <div class="col-md-10">
                            <select class="form-control" id="set_bom" name="set_bom" >
                                <option value="1" {if isset($template) && $template->set_bom eq '1'}selected{/if}>Si</option>
                                <option value='0' {if isset($template) && $template->set_bom eq '0'}selected{/if}>No</option>
                            </select>
                            <p class="help-block">Se desideri utilizzare la codifica Byte Order Mark (BOM).</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="text_container" class="col-md-2 control-label">Contenitore testo</label>
                        <div class="col-md-10">
                            <select class="form-control" id="text_container" name="text_container" >
                                <option value="n" {if isset($template) && $template->text_container eq 'n'}selected{/if}>Automatico</option>
                                <option value='"' {if isset($template) && $template->text_container eq '"'}selected{/if}>"</option>
                                <option value="'" {if isset($template) && $template->text_container eq "'"}selected{/if}>'</option>
                            </select>
                            <p class="help-block">Se ogni elemento testuale è contenuto in particolari tag es. ("", '') .</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="concatenation_char" class="col-md-2 control-label">Carattere concatenzione</label>
                        <div class="col-md-10">
                            <select class="form-control" id="concatenation_char" name="concatenation_char" >
                                <option value="n" {if isset($template) && $template->concatenation_char eq 'n'}selected{/if}>Nessuno</option>
                                <option value='¶' {if isset($template) && $template->concatenation_char eq '¶'}selected{/if}>¶</option>
                                <option value="-" {if isset($template) && $template->concatenation_char eq '-'}selected{/if}>-</option>
                                <option value="~" {if isset($template) && $template->concatenation_char eq '~'}selected{/if}>~</option>
                            </select>
                            <p class="help-block">Se desideri concatenare colonne del csv questo è il carattere che verrà utilizzato nell'unione .</p>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="entries-border">
                    <legend >Dettagli Celle</legend>
                    {if isset($cell_made) && $cell_made|count > 0}
                    {foreach $cell_made as $c}
                    <div class="form-group entry panel-default panel">
                        <div class="col-md-12 panel-body">
                            <div class="form-group row">
                                <input type="hidden" name="cell_id[]" value="{$c.id_cell}"/>
                                <label for="cell_name[]" class="col-md-1 control-label">Nome</label>

                                <div class="col-md-3">
                                    <input type="text" class="form-control" placeholder="Name"
                                           name="cell_name[]" value="{$c.name|stripcslashes}">
                                </div>
                                <label for="cell_formatting[]" class="col-md-1 control-label">Tipo</label>

                                <div class="col-md-3">
                                    <select class="form-control"  name="cell_formatting[]">
                                        <option value=""> - Scegli</option>
                                        {foreach $cell_type as $type}
                                                <option value="{$type.id_cell_type}" {if $c.id_type == $type.id_cell_type}selected{/if}>{$type.name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <label for="cell_position[]" class="col-md-1 control-label">Posizione</label>

                                <div class="col-md-1">
                                    <input type="number" class="form-control" placeholder="0"
                                           name="cell_position[]" value="{$c.position}">
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
                        <div class="col-md-12 formatting-extension hide-fixed" {if $c.id_type != 5 && $c.id_type != 6}style="display:none"{/if}>
                        <div class="form-group row">
                            <div class="col-md-2 col-md-offset-1">
                                <span class="label label-info">Impostazioni avanzate</span>
                            </div>
                            <label for="cell_name[]" class="col-md-2 control-label">{if $c.id_type == 6}Concatenazione{else}Valore Fisso{/if}</label>
                            <div class="col-md-3">
                                <input type="text" value="{$c.fixed_value}" name="special_value[]" class="form-control" placeholder="Inserisci valore" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 formatting-extension price-extension" {if $c.id_type != 2 && $c.id_type != 7}style="display:none"{/if}>
                    <div class="form-group row">
                        <div class="col-md-2 col-md-offset-1">
                            <span class="label label-info">Impostazioni avanzate</span>
                        </div>
                        <label for="cell_name[]" class="col-md-2 control-label">Numero Decimali</label>
                        <div class="col-md-1">
                            <select class="form-control" name="price_round[]">
                                <option value="0">-</option>
                                <option value="2" {if $c.price_round == 2}selected{/if}>2 </option>
                                <option value="3" {if $c.price_round == 3}selected{/if}>3</option>
                                <option value="4" {if $c.price_round == 4}selected{/if}>4</option>
                            </select>
                        </div>
                        <label for="" class="col-md-2 control-label">Elimina carattere ¤</label>
                        <div class="col-md-2">
                            <select class="form-control" name="strip_element[]" >
                                <option value="0" >No</option>
                                <option value="1" {if $c.strip_element == 1}selected{/if}>Si</option>
                            </select>
                        </div>
                    </div>
        </div>
        <div class="col-md-12 formatting-extension quantity-extension" {if $c.id_type != 4}style="display:none"{/if}>
        <div class="form-group row">
            <div class="col-md-2 col-md-offset-1">
                <span class="label label-info">Impostazioni avanzate</span>
            </div>
            <label for="" class="col-md-1 control-label">Arrotondamento</label>
            <div class="col-md-2">
                <select class="form-control" name="quantity_round[]" >
                    <option value="0" {if $c.quantity_round == 0}selected{/if}>Inferiore</option>
                    <option value="1" {if $c.quantity_round == 1}selected{/if}>Superiore</option>
                </select>
            </div>
            <label for="" class="col-md-2 control-label">Valori negativi a 0</label>
            <div class="col-md-2">
                <select class="form-control" name="no_negative[]" >
                    <option value="0" >No</option>
                    <option value="1" {if $c.no_negative == 1 }selected{/if}>Si</option>
                </select>
            </div>
        </div>
    </div>
</div>
{/foreach}
{/if}

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
                    {foreach $cell_type as $type}
                         <option value="{$type.id_cell_type}">{$type.name}</option>
                    {/foreach}
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
                    <option value="2" selected>2 </option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            </div>
            <label for="" class="col-md-2 control-label">Elimina carattere ¤</label>
            <div class="col-md-2">
                <select class="form-control" name="strip_element[]" >
                    <option value="0" selected>No</option>
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
                    <option value="0" selected>Inferiore</option>
                    <option value="1">Superiore</option>
                </select>
            </div>
            <label for="" class="col-md-2 control-label">Valori negativi a 0</label>
            <div class="col-md-2">
                <select class="form-control" name="no_negative[]" >
                    <option value="0" selected>No</option>
                    <option value="1" >Si</option>
                </select>
            </div>
        </div>
    </div>
</div>
</fieldset>
<input type="hidden" name="id_template" value="{if isset($template)}{$template->id}{/if}" />
<input type="hidden" name="submitTemplate" value="1"/>
</form>
</div>
<div class="col-md-12">
    <a class="btn btn-default" href="{$link->getPageLink('templates')}" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
    <button type="button" class="btn btn-info" name="submitTemplate" onclick="$('#template_form').trigger('submit');"><i class="glyphicon glyphicon-floppy-disk"></i> Salva</button>
</div>
</div>

</div><!-- /.container -->

{include file="footer.tpl" title=footer}
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="js/template.js"></script>
</body>
</html>
