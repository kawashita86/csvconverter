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
    }).on('click', '.btn-up', function(e)
        {
            e.preventDefault();
            var current = $(this).parents('.entry');
            if(current.find('input[name="cell_position[]"]').val() !== '' || current.find('input[name="cell_position[]"]').val().length !== 0 ) {
                var previous = current.prev('.entry');
                previous.before(current);
            }
     }).on('click', '.btn-down', function(e)
    {
        e.preventDefault();

        var current = $(this).parents('.entry');
        var next = current.next('.entry');
        if(next.find('input[name="cell_position[]"]').val() !== '' || next.find('input[name="cell_position[]"]').val().length !== 0 ) {

            next.after(current);
        }
    });

    $(document).on('change', 'select[name="cell_formatting[]"]', function(e){
        var entry = $(this).parents('.entry');
        if($(this).val() == '5' || $(this).val() == '6') {
           entry.find('.hide-conversion').hide();
           entry.find('.hide-fixed').show();
       } else {
            entry.find('.hide-fixed').hide();
            entry.find('.hide-conversion').show();
        }
    });
});
