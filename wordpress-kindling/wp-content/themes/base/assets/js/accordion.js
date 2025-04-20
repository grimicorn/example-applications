let accordion = {
    $accordionToggle: undefined,
    accordionToggleInit(){
        accordion.$accordionToggle.on("click", function(e){
            e.preventDefault();
            $(this).parent('.accordion-item').toggleClass('open');
            $(this).next('p').slideToggle();
            $('.js-accordion-toggle').not(this).next('p').slideUp();
            $('.js-accordion-toggle').not(this).parent('.accordion-item').removeClass('open');
        });
    }
};
export default function() {
    accordion.$accordionToggle = $(".js-accordion-toggle");
    accordion.accordionToggleInit();
}
