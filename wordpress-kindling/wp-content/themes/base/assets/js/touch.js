export default function(){
    if( $(".js-item-toggle").is(':hidden') ){

        $(".touchevents li.dropdown > a").on("touchstart",function(e){
            var link = $(this);
            if (link.hasClass('hover')) {
                return true;
            } else {
                $('.hover').removeClass('hover');
                link.addClass('hover');
                e.preventDefault();
                return false;
            }
        });
    }
}
