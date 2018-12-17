jQuery(document).ready(function($){
    $('#add_pair').click(function() {
        $('#container_body').append('<tr><td><input type="text" Placeholder="Referer URL contains" class=" other_text large-text" name="url_contains[]"></td><td>' + $('#destination_holder').html() +'</td><td style="text-align:center"><div class="button-secondary delete_row" >Remove me!</div></td></tr>')
        $('select[name="destination_selector[]"]').last().dropdown();
    });

    $('.initial_select').dropdown();

    $('#save_form').click(function() {
        var saveResult = $('#form_redirects').serialize();
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: "saveredirects",
                data: saveResult
            },
            type: 'POST',
            complete: function(data) {
                location.reload();
            }
        });
    });

    $(".delete_row").click(function() {
        $(this).parents("tr").first().remove();
    });
});