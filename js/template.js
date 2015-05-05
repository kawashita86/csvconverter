$(function()
{
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();

        var controlForm = $('.template-form-container form '),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('input').val('');
        controlForm.find('.entry:not(:last) .btn-add')
            .removeClass('btn-add').addClass('btn-remove')
            .removeClass('btn-success').addClass('btn-danger')
            .html('<span class="glyphicon glyphicon-minus"></span>');
    }).on('click', '.btn-remove', function(e)
    {
        $(this).parents('.entry:first').remove();

        e.preventDefault();
        return false;
    });

    $(document).on('change', 'select[name="cell_formatting[]"]', function(e){
        var entry = $(this).parents('.entry');
        if($(this).val() == '5') {
           entry.find('.hide-conversion').hide();
           entry.find('.hide-fixed').show();
       } else {
            entry.find('.hide-fixed').hide();
            entry.find('.hide-conversion').show();
        }
    });
});
