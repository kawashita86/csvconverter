{include file="header.tpl" title=header}
<div class="container theme-showcase" role="main">
    {if isset($conf)}
        <div class="alert {if $conf eq 1}alert-success{else}alert alert-danger{/if} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {if $conf eq 1}
                Aggiornamento avvenuto con successo
            {/if}
            {if $conf eq 2}
                Si sono verificati degli errori durante l'aggiornamento
            {/if}
        </div>
    {/if}
    <div class="page-header">
        <h3>
            Impostazioni
        </h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" data-toggle="validator" id="setting_form" autocomplete="off" action="{$link->getPageLink('impostazioni')}" method="post">
                <fieldset class="scheduler-border">
                <legend class="scheduler-border">Impostazioni File da Importare</legend>
                <div class="form-group">
                    <label for="HEADER_LINE" class="col-md-2 control-label">Formato File Importato</label>
                    <div class="col-md-10">
                        <select class="form-control" id="IMPORT_FILE_TYPE" name="IMPORT_FILE_TYPE" required>
                            <option value="csv" {if $file_type eq 'csv'}selected{/if}>csv</option>
                            <option value="txt" {if $file_type eq 'txt'}selected{/if}>txt</option>
                            <!--<option value="ods" <?php  if(Configuration::get('IMPORT_FILE_TYPE') == 'ods'){ echo 'selected'; } ?>>ods</option>-->
                        </select>
                        <p class="help-block">Formato del file che verrà importato per la conversione.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="HEADER_LINE" class="col-md-2 control-label">Linee Intestazione</label>
                    <div class="col-md-10">
                        <select class="form-control" id="HEADER_LINE" name="HEADER_LINE" required>
                            <option value="0" {if $header_line eq 0}selected{/if}>0</option>
                            <option value="1" {if $header_line eq 1}selected{/if}>1</option>
                        </select>
                        <p class="help-block">numero di righe che compongono l'intestazione del csv.</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="SEPARATOR" class="col-md-2 control-label">Separatore</label>
                    <div class="col-md-10">
                        <select class="form-control" id="SEPARATOR" name="SEPARATOR" required >
                            <option value="">Scegli</option>
                            <option value="," {if $separator eq ','}selected{/if}>,</option>
                            <option value=";" {if $separator eq ';'}selected{/if}>;</option>
                            <option value="t" {if $separator eq 't'}selected{/if}>Tab</option>
                            <option value="|" {if $separator eq '|'}selected{/if}>|</option>
                            <option value="-" {if $separator eq '-'}selected{/if}>-</option>
                        </select>
                        <p class="help-block">valore che separa i singoli campi del csv ( "|", ",", ... ).</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="TEXT_CONTAINER" class="col-md-2 control-label">Contenitore testo</label>
                    <div class="col-md-10">
                        <select class="form-control" id="TEXT_CONTAINER" name="TEXT_CONTAINER" >
                        <option value="n" {if $text_container eq 'n'}selected{/if}>Automatico</option>
                        <option value='"' {if $text_container eq '"'}selected{/if}>"</option>
                        <option value="'" {if $text_container eq "'"}selected{/if}>'</option>
                        </select>
                        <p class="help-block">Se ogni elemento testuale è contenuto in particolari tag es. ("", '') .</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="NEW_LINE" class="col-md-2 control-label">Separatore di righe</label>
                    <div class="col-md-10">
                        <select class="form-control" id="NEW_LINE" name="NEW_LINE" >
                            <option value="\r\n" {if $new_line eq "\r\n"}selected{/if}>\r\n</option>
                            <option value="\n"  {if $new_line eq "\n"}selected{/if}>\n</option>
                        </select>
                        <p class="help-block">il carattere utilizzato come "New Line"</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="submit" value="Aggiorna" name="updateImpostazioni" class="btn btn-info" />
                </div>
                </fieldset>
            </form>
            <div class="clearfix" style="margin-top:15px;"></div>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Gestisci File Caricati</legend>
                    <table id="template-table" class="table table-hover">
                        <thead>
                        <tr>
                        <tr>
                            <th>Nome File</th>
                            <th>Azioni</th>
                        </tr>
                        </thead>
                        <tbody>
                        {if $files|count > 0}
                        {foreach $files as $file}
                            <tr>
                                <td>{$file}</td>
                                <td>
                                    <a href="{$link->getPageLink('impostazioni')}&deleteFile=1&filename={$file|urlencode}" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancella</a>
                                </td>
                            </tr>
                        {/foreach}
                        {else}
                            <tr><td colspan="2">Nessun file salvato</td></tr>
                        {/if}
                        </tbody>
                    </table>
                </fieldset>


            <div class="col-md-12">
                <a class="btn btn-default" href="{$link->getPageLink('index')}" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
            </div>
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