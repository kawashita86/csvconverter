<?php
include_once('config/config.php');

$main_page = 'istruzioni';
include_once('header.php');
?>
    <!--    <div class="col-sm-9 col-md-10 main"> -->
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>Istruzioni</h3>
    </div>
    <div class="row">
            <div class="col-sm-3 col-md-3 sidebar">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">
            <i class="glyphicon glyphicon-search"></i>
        </button>
      </span>
                </div>

                <ul class="nav nav-pills nav-stacked" id="menu">
                    <li>
                        <a href="#templates">
                            <i class="glyphicon glyphicon-list"></i><span class="hidden-sm text"> Templates</span>
                        </a>
                    </li>
                    <li>
                        <a href="#conversioni">
                            <i class="glyphicon glyphicon-bullhorn"></i><span class="hidden-sm text"> Conversioni</span>
                        </a>
                    </li>
                    <li >
                        <a href="#impostazioni">
                            <i class="glyphicon glyphicon-cog"></i><span class="hidden-sm text"> Impostazioni</span>
                        </a>

                </ul>
            </div>
        <div class="col-sm-9 col-md-9 main">
            <div class="bs-docs-section">
                <h1 id="js-templates" class="page-header">
                    Templates
                    <a class="anchorjs-link" href="#js-templates">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h1>
                <h2 id="js-list">
                   Lista
                    <a class="anchorjs-link" href="#js-list">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h2>
                <p>
                    La pagina dei template mostra una tabella contenente la lista di tutti i templati creati,
                    ordinati per data di creazione ed un bottone a fondo pagina che permette di aggiungere uno nuovo.
                </p>
                <div id="callout-overview-not-both" class="bs-callout bs-callout-danger">
                    <h4 id="using-the-compiled-javascript">
                        Utilizzare i bottoni contestuali
                        <a class="anchorjs-link" href="#using-the-compiled-javascript">
                            <span class="anchorjs-icon"></span>
                        </a>
                    </h4>
                    <p>
                        E' possibile modificare o eliminare un template utilizzando i pulsanti contestuali
                        <button class="btn btn-warning"><i class="glyphicon glyphicon-pencil"></i> Modifica</button> e
                        <button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Elimina</button>
                    </p>
                </div>
                <h2 id="js-new-template">
                    Creazione Template
                    <a class="anchorjs-link" href="#js-new-template">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h2>
                <p>
                    La creazione di un nuovo template avviene tramite l'interfaccia raggiungibile dal bottone
                    <a href="templates_manage.php" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
                    presente nella pagina "Templates".
                    Qui viene mostrato un form nel quale è possibile inserire tutti i dettagli legati sia alla formattazione generale del template,
                    sia ai dettagli delle celle.
                </p>
                <h4 id="dettagli-template">
                    Dettagli Template
                    <a class="anchorjs-link" href="#dettagli-template">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h4>
                <p>
                    Qui vanno impostati i campi generici del template, che determineranno i dettagli quali carattere separatore, inclusione dell'intestazione e carattere di concatenazione.
                </p>
                <div class="bs-example" data-example-id="simple-page-header">
                    <ul>
                        <li>Nome: example (alla conversione verrà usato come nome del file)</li>
                        <li>Descrizione: example description (non necessaria)</li>
                        <li>Separatore: TAB (determina l'uso del TAB come separatore di campo)</li>
                        <li>Linee Intestazione : 0 (l'intestazione non verrà inclusa nel file convertito)</li>
                        <li>Contenitore Testo: Nessuno (la cella testuale verrà copiata così come è nel file originale)</li>
                        <li>Carattere concatenazione: ¶ (se verrà determinata la concatenazione di campi questo sarà il carattere usato)</li>
                    </ul>
                </div>
                <h4 id="dettagli-celle">
                    Dettagli Celle
                    <a class="anchorjs-link" href="#dettagli-celle">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h4>

        </div>
    </div>
</div>
    </div>
<?php include_once('footer.php'); ?>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="js/template.js"></script>
</body>
</html>