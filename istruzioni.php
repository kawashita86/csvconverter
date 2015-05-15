<?php
include_once('config/config.php');

$main_page = 'istruzioni';
include_once('header.php');
?>
<div id="top" ></div>
    <!--    <div class="col-sm-9 col-md-10 main"> -->
<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>Istruzioni</h3>
    </div>
    <div class="row">
            <div class="col-sm-3 col-md-3 sidebar scrollspy" role="complementary">
                <nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm"  >
                <ul class="nav bs-docs-sidenav" id="menu" >
                    <li>
                        <a href="#js-templates"><i class="glyphicon glyphicon-list"></i> Templates</a>
                        <ul class="nav">
                            <li><a href="#js-list">Lista</a></li>
                            <li><a href="#js-new-template">Creazione Template</a></li>
                            <li><a href="#dettagli-template">Dettagli Template</a></li>
                            <li><a href="#dettagli-celle">Dettagli celle</a></li>
                            <li><a href="#tipologie-celle">Tipologie celle</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#js-conversion">
                            <i class="glyphicon glyphicon-bullhorn"></i> Conversioni
                        </a>
                    </li>
                    <li >
                        <a href="#js-impostazioni">
                            <i class="glyphicon glyphicon-cog"></i> Impostazioni
                        </a>

                </ul>
                    <a class="back-to-top" href="#top">
                    Back to top
                    </a>
                </nav>
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
                <div id="callout-overview-not-both" class="bs-callout bs-callout-info">
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
                    sia ai dettagli delle singole celle.
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
                    <dl>
                        <dt>Nome:</dt><dd>example (alla conversione verrà usato come nome del file)</dd>
                        <dt>Descrizione:</dt><dd>example description (non necessaria)</dd>
                        <dt>Separatore:</dt><dd> TAB (determina l'uso del TAB come separatore di campo)</dd>
                        <dt>Linee Intestazione:</dt><dd> 0 (l'intestazione non verrà inclusa nel file convertito)</dd>
                        <dt>Contenitore Testo:</dt><dd> Nessuno (la cella testuale verrà copiata così come è nel file originale)</dd>
                        <dt>Carattere concatenazione:</dt><dd> ¶ (se verrà determinata la concatenazione di campi questo sarà il carattere usato)</dd>
                    </dl>
                </div>
                <h4 id="dettagli-celle">
                    Dettagli Celle
                    <a class="anchorjs-link" href="#dettagli-celle">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h4>
                <p>
                    Ogni cella può essere configurata secondo le specifiche del campo che si intende mappare.<br/>
                    La posizione della cella rispetto alle altre determina anche la posizione in cui verrà mostrata nel csv convertito.<br/>
                    Per aggiungere una cella è sufficiente utilizzare il bottone
                    <button class="btn btn-success" type="button">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>  che compare sull'ultima cella.<br/>
                    Per eliminare una cella è sufficiente premere il bottone
                    <button class="btn btn-danger" type="button">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </p>
                <div id="callout-overview-not-both" class="bs-callout bs-callout-info">
                    <p>
                        E' possibile spostare una cella utilizzando i bottoni contestuali
                        <button class="btn btn-info btn-up" type="button">
                            <span class="glyphicon glyphicon-arrow-up"></span>
                        </button> Sali di posizione e
                        <button class="btn btn-info btn-down" type="button">
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </button> Scendi di posizione<br/>

                        <b>Nessuna modifica viene salvata fino a che non è premuto l'apposito bottone in fondo alla pagina, è sufficiente ricaricare la pagina con <kbd>F5</kbd> per riportare il template nella situazione precedente</b>
                    </p>
                </div>
                <p>
                    Ogni cella contiene alcuni campi obbligatori ed altri opzionali, qui in seguito la lista con alcuni dettagli
                </p>
                <div id="callout-cell-input" class="bs-callout bs-callout-info">
                    <dl>
                        <dt>Nome:</dt><dd>OPZIONALE, se inserito verrà utilizzato nell'instestazione del csv</dd>
                        <dt>Tipologia:</dt><dd>OBBLIGATORIA, determina come verrà gestito il campo, e la comparsa di alcuni parametri avanzati per determinate tipologie</dd>
                        <dt>Posizione:</dt><dd>OPZIONALE, è richiesta per tutti i tipi di campo ad esclusione di quelli con concatenazione, valore fisso o campo vuoto</dd>
                    </dl>
                </div>
                <h4 id="tipologie-celle">
                    Tipologie Celle
                    <a class="anchorjs-link" href="#tipologie-celle">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h4>
                <p>
                    Ogni cella può essere definita come un particolare tipo, questo determina la formattazione del dato contenuto nella posizione espressa dall'input <b>Pos.</b> secondo
                    alcune regole particolari qui sotto elencate.
                </p>
                <div id="callout-cell-type" class="bs-callout bs-callout-info">
                    <dl>
                        <dt>Testo</dt><dd>
                            il contenuto della cella viene formattato come testo puro, eliminando qualunque tag HTML o caratteri di "a capo".
                        </dd>
                        <dt>Decimale con il punto</dt><dd>
                            il contenuto della cella viene formattato come numero decimale con simbolo ".". (Es. 10.52) .<br/>
                            Selezionando questa tipologia comparirà un pannello per gestire parametri avanzati quali:
                            <ul>
                                <li>L'eliminazione di caratteri extra</li>
                                <li>Il numero di cifre da utilizzare nell'arrotondamento</li>
                            </ul>
                        </dd>
                        <dt>HTML</dt><dd>
                            il contenuto della cella viene formattato come testo html, mantenendo la formattazione originale al 100%, senza alcuna modifica sulla conversione.
                        </dd>
                        <dt>Quantità</dt><dd>
                            il contenuto della cella viene formattato come una quantità convertendo il valore in un intero.<br/>
                            Selezionando questa tipologia comparirà un pannello per gestire parametri avanzati quali:
                            <ul>
                                <li>Il tipo di arrotondamento in caso di cifre decimali (ad intero inferiore o superiore)</li>
                                <li>Se convertire le cifre negative a 0</li>
                            </ul>
                        </dd>
                        <dt>Valore fisso</dt><dd>
                            se è necessario inserire un valore che venga ripetuto per ogni riga del csv, alla selezione comparirà un input in cui sarà possibile inserire qualunque tipo di testo,numero o tag html.
                        </dd>
                        <dt>Concatena</dt><dd>
                            se è necessario concatenare due o più celle in una, questa tipologia permette di definire quali celle unire tramite un input aggiuntivo.
                            <b>Nel campo vanno inseriti i numeri delle celle da unire, separati da una virgola</b>
                        </dd>
                        <dt>Decimale con la virgola</dt><dd>
                            il contenuto della cella viene formattato come numero decimale con simbolo ",". (Es. 10,52) .<br/>
                            Selezionando questa tipologia comparirà un pannello per gestire parametri avanzati quali:
                            <ul>
                                <li>L'eliminazione di caratteri extra</li>
                                <li>Il numero di cifre da utilizzare nell'arrotondamento</li>
                            </ul>
                        </dd>
                        <dt>Campo vuoto</dt><dd>
                            se è necessario inserire un campo senza contenuto per ogni riga del csv, simile al campo Valore fisso ma è preimpostato per inserire un campo vuoto.
                        </dd>
                    </dl>
                </div>
                <h1 id="js-conversion" class="page-header">
                    Conversioni
                    <a class="anchorjs-link" href="#js-conversion">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h1>
                <p>Questa pagina permette di convertire un file di partenza in un file di destinazione secondo le regole specificate all'interno di un template precedentemente salvato.
                   Nel form di conversione vengono offerte diverse opzioni per personalizzare la conversione secondo le necessità.<br/>
                   Una volta selezionate le impostazioni desiderate è sufficiente premere il bottone "Converti" ed attendere che la procedura venga eseguita, al termine verrà aperta una finestra con il file da scaricare sul vostro pc.
                    <br/>Qui in seguito vengono elencati i campi presenti nel form con una descrizione delle funzionalità.
                </p>
                <div id="callout-conversion-type" class="bs-callout bs-callout-info">
                    <dl>
                        <dt>Template</dt><dd>Con questa <code>select</code> è possibile selezionare uno fra i templati salvati ed utilizzarlo per la conversione del file</dd>
                        <dt>Formato file</dt><dd>La scelta del formato in cui verrà fatto scaricare il file convertito, la scelta è fra .txt e .csv</dd>
                        <dt>Encoding</dt><dd>L'encoding utilizzato nel creare il file di conversione. Due sono le scelte disponibili:
                            <ul>
                                <li>Default: il file viene convertito con lo standard UTF-8, formato interazionale utilizzabile su ogni piattaforma</li>
                                <li>Windows/Excel: il file viene convertito con il formato UTF-16LE/Bom specifico per il programma Ms Excel su windows. Utile per questioni di visualizzazione del file senza usare il wizard csv di Excel.</li>
                            </ul>
                        </dd>
                        <dt>Carica un File</dt><dd>La scelta del file da caricare prendendolo dal nostro PC</dd>
                        <dt>File caricati precedentemente</dt><dd>Una <code>select</code> che mostra l'ultimo/i file caricato. può essere utile nel caso si desideri convertire lo stesso file con più template senza effettuare ogni volta l'upload.</dd>
                    </dl>
                </div>
                   <div class="alert alert-danger">
                       <b>N.b.</b> Dopo aver caricato un file tramite l'input "Carica un File" è necessario aggiornare la pagina perchè venga visualizzato all'interno della select "File caricati precedentemente".
                   </div>
                <h1 id="js-impostazioni" class="page-header">
                    Impostazioni
                    <a class="anchorjs-link" href="#js-impostazioni">
                        <span class="anchorjs-icon"></span>
                    </a>
                </h1>
                <p>Questa pagina permette di salvare le impostazioni generiche legate alla tipologia di file che viene utilizzata come sorgente di conversione.<br/>
                    Il form presenta diversi campi che servono a determinare le caratteristiche principali del file sorgente.
                    <br/>Qui in seguito vengono elencati i campi presenti nel form con una descrizione delle funzionalità.
                </p>
                <div id="callout-impostazioni-type" class="bs-callout bs-callout-info">
                    <dl>
                        <dt>Formato file importato</dt><dd>La scelta del formato del file da importare, la scelta è fra .txt e .csv</dd>
                        <dt>Linee intestazione</dt><dd>Il file da importare contiene la prima riga con l'intestazione? in caso selezionare 1 altrimenti 0</dd>
                        <dt>Separatore</dt><dd>Il separatore utilizzato nel file da importare</dd>
                        <dt>Contenitore testo</dt><dd>Il file da importare contiene un particolare carattere per incapsulare i campi testuali? il valore <b>Automatico</b> stabilirà in maniera implicità quale carattere utilizzare. Specificare diversamente solo in caso di fallimento</dd>
                        <dt>Separatore di righe</dt><dd>Quale carattere di nuova riga è utilizzato nel file da importare? Lasciare il campo di default salvo errate conversioni</dd>
                    </dl>
                </div>
    </div>
</div>
    </div></div>
<?php include_once('footer.php'); ?>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="js/template.js"></script>
<script>
    $('.bs-docs-sidebar').affix({
        offset: {
            top: 0,
            bottom: 60
        }
    })
</script>
</body>
</html>