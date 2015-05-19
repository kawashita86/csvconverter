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
        <h3>Lista Utenti</h3>
    </div>
    <table id="users-table" class="table table-striped table-hover">
        <thead>
        <tr>
        <tr>
            <th>Id</th>
            <th>Email</th>
            <th>Attivo</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        {foreach $users as $user}
            <tr>
                <td>{$user.id_user}</td>
                <td>{$user.email}</td>
                <td>{if $user.active}<button class="btn btn-success"><i class="glyphicon glyphicon-ok-circle"></i> </button>{else}<button class="btn btn-danger"><i class="glyphicon glyphicon-remove-circle"></i> </button>{/if}</td>
                <td>
                    {if $user.active}
                        <a href="{$link->getPageLink('users')}&toggleUser=1&id_user={$user.id_user}" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Disattiva</a>
                    {/if}
                    {if !$user.active}
                        <a href="{$link->getPageLink('users')}&toggleUser=1&id_user={$user.id_user}" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Attiva</a>
                    {/if}
                    <a href="{$link->getPageLink('users-manage')}&id_user={$user.id_user}" class="btn btn-warning"><i class="glyphicon glyphicon-pencil"></i> Modifica</a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-default" href="{$link->getPageLink('index')}" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
            <a href="{$link->getPageLink('users-manage')}" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
        </div>
    </div>
</div><!-- /.container -->

{include file="footer.tpl" title=footer}
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="js/template.js"></script>
<script type="text/javascript">
    {literal}  var oTable;
    $(document).ready(function () {
        oTable = $('#users-table').dataTable({
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "bProcessing": false,
            "bServerSide": false
        });
    });
    {/literal}
</script>
</body>
</html>