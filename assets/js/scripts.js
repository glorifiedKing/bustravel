/*! Smooth Scroll Anchorage */
$(document).ready(function() {
    $("a").on("click", function(t) {
        if ("" !== this.hash) {
            t.preventDefault();
            $("html, body").animate({
                scrollTop: $(this.hash).offset().top - 70
            }, 800)
        }
    })
});

/*! Smooth Scroll To Top */
$("#to-top").click(function () {
    $("html, body").animate({
        scrollTop: (0, 0)
    }, 800)
});