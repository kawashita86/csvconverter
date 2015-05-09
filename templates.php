<?php
include_once('config/config.php');

$templates = Template::getAll();
$main_page = 'templates';
include_once('header.php');
?>

<div class="container theme-showcase" role="main">
    <div class="page-header">
        <h3>Lista template</h3>
    </div>
    <table id="template-table" class="table table-striped table-hover">
        <thead>
        <tr>
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Descrizione</th>
                    <th>Azioni</th>
                </tr>
        </thead>
        <tbody>
                  <?php foreach($templates as $template){ ?>
                      <tr>
                      <td><?php echo $template['id_template'] ?></td>
                        <td><?php echo $template['name'] ?></td>
                        <td><?php echo $template['description'] ?></td>
                        <td>
                            <a href="templates_manage.php?id_template=<?php echo $template['id_template'];?>" class="btn btn-warning"><i class="glyphicon glyphicon-pencil"></i> Modifica</a>
                            <a href="save_template.php?deleteTemplate=1&id_template=<?php echo $template['id_template'];?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Cancella</a>
                        </td>
                     </tr>
                <?php } ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <a href="templates_manage.php" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i> Aggiungi nuovo</a>
        </div>
    </div>
</div><!-- /.container -->

<?php include_once('footer.php'); ?>
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/bootstrap-validator/dist/validator.min.js"></script>
<script src="bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="js/template.js"></script>
<script type="text/javascript">
    var oTable;
    $(document).ready(function () {
        oTable = $('#template-table').dataTable({
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "bProcessing": false,
            "bServerSide": false
        });
    });
</script>
</body>
</html>
