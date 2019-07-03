$(document).ready(function() {
    // To prevent multiple form submission
    $('form').submit(function(){
        $(this).find(':input[type=submit]').attr('disabled', true);
    });
});