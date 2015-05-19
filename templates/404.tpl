{include file="header.tpl" title=header}
<style>
    {literal}
    .error-template {padding: 40px 15px;text-align: center;}
    .error-actions {margin-top:15px;margin-bottom:15px;}
    .error-actions .btn { margin-right:10px; }
    {/literal}
</style>
<div class="container theme-showcase" role="main">
    <div class="row">
        <div class="col-md-12">
            <div class="error-template">
                <h1>
                    Oops!</h1>
                <h2>
                    404 Not Found</h2>
                <div class="error-details">
                    Sorry, an error has occured, Requested page not found!
                </div>
                <div class="error-actions">
                    <a href="{$link->getPageLink('index')}" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Torna alla Home </a><a href="mailto:francesco_viglino@hotmail.it" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-envelope"></span> Supporto </a>
                </div>
            </div>
        </div>
    </div>
</div>
{include file="footer.tpl" title=footer}
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
