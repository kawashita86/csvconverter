$(function()
{
    $(document).on('click', '.btn-add', function(e)
    {
        e.preventDefault();

        var controlForm = $('.template-form-container form fieldset.entries-border'),
            currentEntry = $(this).parents('.entry:first'),
            newEntry = $(currentEntry.clone()).appendTo(controlForm);

        newEntry.find('.formatting-extension').hide();
        newEntry.find('.hide-fixed').hide();
        newEntry.find('.hide-conversion').show();
        newEntry.find('input:not([type=hidden])').val('');
        newEntry.find('select').val('');

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
            if(current.find('select[name="cell_formatting[]"]').val() !== '' && current.find('select[name="cell_formatting[]"]').val().length !== 0 ) {
                var previous = current.prev('.entry');
                previous.before(current);
            }
     }).on('click', '.btn-down', function(e)
    {
        e.preventDefault();

        var current = $(this).parents('.entry');
        var next = current.next('.entry');
        if(next.find('select[name="cell_formatting[]"]').val() !== '' && next.find('select[name="cell_formatting[]"]').val().length !== 0 ) {

            next.after(current);
        }
    });

    $(document).on('change', 'select[name="cell_formatting[]"]', function(e){
        var entry = $(this).parents('.entry');
        var current_type = $(this).val();
        if(current_type == '5') {
           entry.find('.hide-fixed input').attr('placeholder', 'Inserisci valore');
           entry.find('.hide-fixed label').text('Valore Fisso');
           entry.find('.formatting-extension').hide();
           entry.find('.hide-fixed').show();
       } else if(current_type == '6') {
            entry.find('.hide-fixed input').attr('placeholder', 'Colonne separate da ,');
            entry.find('.hide-fixed label').text('Concatenazione');
            entry.find('.formatting-extension').hide();
            entry.find('.hide-fixed').show();
        } else if(current_type == 4){
            entry.find('.formatting-extension').hide();
            entry.find('.quantity-extension').show();

        } else if(current_type == 2 || current_type == 7){
            entry.find('.formatting-extension').hide();
            entry.find('.price-extension').show();
        }
        else
        {
            entry.find('.formatting-extension').hide();
            entry.find('.hide-fixed').hide();
        }
    });

    $('#template_form').on('validated.bs.validator', function(){
        $('.check_no_negative').each(function(){
           if($(this).parent().find('input[type=checkbox]').is(':checked') == true)
                $(this).attr('disabled', true);
        });

        $('.check_strip_element').each(function(){
            if($(this).parent().find('input[type=checkbox]').is(':checked')  == true)
                $(this).attr('disabled', true);
        });

        return true;
    });


    $('#conversion_form').on('validate.bs.validator', function(e){
                if ($('#previous_file').val().length === 0 && $('#new_file').val().length === 0) {
                    console.log('passed');
                    e.preventDefault();
                    return false;
                }
                else
                    return true;
        });

});
