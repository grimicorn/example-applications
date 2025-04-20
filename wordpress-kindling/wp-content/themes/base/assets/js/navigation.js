import debounce from "./debounce";

let navigation = {
    $itemToggle: undefined,
    $menuToggle: undefined,
    $menu: undefined,
    debounce: debounce.new(),

    menuToggleInit() {
        navigation.$menuToggle.on("click", function(e) {
            e.preventDefault();
            $(this).toggleClass("open");
            navigation.$menu.toggleClass("open");

            if (!navigation.$menu.hasClass("open")) {
                navigation.$menu.find(".dropdown").removeClass("open");
            }
        });
    },

    itemToggleInit() {
        navigation.$itemToggle.on("click", function(e) {
            e.preventDefault();
            $(this)
                .parent(".dropdown")
                .toggleClass("open");
        });
        navigation.$itemToggle.parent('.dropdown').first().children('a').attr('href',"#").on("click", function(e){
            e.preventDefault();
            $(this)
                .parent(".dropdown")
                .toggleClass("open");
        });//links with a hash toggle their sub menu
    },

    handleResize() {
        if (navigation.$itemToggle.css("display") === "none") {
            $(".dropdown.open").removeClass("open");
        }
    }
};

export default function() {
    navigation.$itemToggle = $(".js-item-toggle");
    navigation.$menu = $(".js-navigation-menu");
    navigation.$menuToggle = $(".js-menu-toggle");

    navigation.itemToggleInit();
    navigation.menuToggleInit();

    $(window).resize(function() {
        navigation.debounce.set(navigation.handleResize, 500);
    });
}
