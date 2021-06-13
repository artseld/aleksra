$(function() {
    var $containerForm = $('#containerForm');
    var $containerResults = $('#containerResults');
    var $form = $containerForm.find('form');
    var $textInp = $form.find("textarea[name='text']");
    var $submitBtn = $form.find("button[type='submit']");
    var $alertText = $('#alertText');

    $form.submit(function(event) {
        // Stop form from submitting normally
        event.preventDefault();
        // Get some values from elements on the page:
        var url = $form.attr("action");
        $textInp.prop('disabled', true);
        $submitBtn.prop('disabled', true);
        // Send the data using post
        var posting = $.post( url, { text: $textInp.val() } );
        // Put the results in a div
        posting.done(function(data) {
            // Sorry for callback hell ;)
            $containerForm.fadeOut(function() {
                $containerResults.empty().html(data).fadeIn(function() {
                    $containerResults.find('button').click(function() {
                        $containerResults.fadeOut(function() {
                            $containerResults.empty();
                            $textInp.prop('disabled', false);
                            $submitBtn.prop('disabled', false);
                            $containerForm.fadeIn();
                        });
                    });
                });
            });
        });
        posting.fail(function() {
            $alertText.fadeIn().delay(2000).fadeOut();
            $textInp.prop('disabled', false);
            $submitBtn.prop('disabled', false);
        });
    });
});
