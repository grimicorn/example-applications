const Clipboard = require('clipboard');
var clipboard = new Clipboard('.js-copy');
clipboard.on('success', function(e) {
    var $trigger = $( e.trigger );
    var html = $trigger.html();
    $trigger.prop('disabled', true)
            .addClass( 'copied' )
            .html( 'Copied' );

    setTimeout(function() {
        $trigger.removeClass( 'copied' )
                .html( html )
                .prop('disabled', false);
    }, 2000);
});
