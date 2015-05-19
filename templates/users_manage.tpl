{include file="header.tpl" title=header}

<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>{if !isset($user)}Aggiungi{else}Modifica{/if} Utente</h3>
    </div>
    <div class="row">
        <div class="col-md-12 template-form-container">
            <form class="form-horizontal" role="form" id="user_form" autocomplete="off" action="{$link->getPageLink('users')}" method="post">
                    <div class="form-group">
                        <label for="template_name" class="col-md-2 control-label">Email</label>
                        <div class="col-md-10">
                            <input type="text" value="{if isset($user)}{$user->email}{/if}" class="form-control" id="email" name="email" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="template_name" class="col-md-2 control-label">Password</label>
                        <div class="col-md-10">
                            <input type="password"  class="form-control" id="password" name="password" value="" />
                        </div>
                    </div>
<input type="hidden" name="id_user" value="{if isset($user)}{$user->id}{/if}" />
<div class="col-md-12">
    <a class="btn btn-default" href="{$link->getPageLink('users')}" ><i class="glyphicon glyphicon-arrow-left"></i> Back</a>
    <input type="submit" class="btn btn-info" name="submitUser" value="Salva"/>
</div>
        </form>
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