var address = window.location.pathname;
var menus = $(".nav.nav-list").children("li:not(:first-child)");

if(address.replace(/\//g, "") === "admin") {
    $(".nav.nav-list").children("li").first().addClass("active")
}
else {
    $.each(menus, function(){
        var $this = $(this);

        if(address.indexOf($this.children("a").attr("href")) === 0) {
            $this.addClass("active");
        }
    });
}

function Masker(mask) {
    this.html = $("html");
    this.masker = $(mask);

    Masker.prototype.start = function() {
        var doc = document.documentElement;
        var left = (window.pageXOffset || doc.scrollLeft) - (doc.clientLeft || 0);
        var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);

        this.html.css("overflow", "hidden");
        this.masker.css("top", top);
        this.masker.show();
    };

    Masker.prototype.stop = function() {
        this.html.css("overflow", "initial");
        this.masker.css("top", 0);
        this.masker.hide();
    };
}

var $masker;

$(document).ready(function() {
    // Side Bar Toggle
    $('.hide-sidebar').click(function() {
        $('#sidebar').hide('fast', function() {
            $('#content').removeClass('span9');
            $('#content').addClass('span12');
            $('.hide-sidebar').hide();
            $('.show-sidebar').show();
        });
    });

    $('.show-sidebar').click(function() {
        $('#content').removeClass('span12');
        $('#content').addClass('span9');
        $('.show-sidebar').hide();
        $('.hide-sidebar').show();
        $('#sidebar').show('fast');
    });

    $masker = new Masker(".mask.global");
});