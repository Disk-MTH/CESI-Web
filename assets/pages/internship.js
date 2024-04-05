$("[data-wish]").each(function() {
    var wish = $(this).attr("data-wish");
    if (wish === "1") {
        $(this).addClass("fa-bookmark");
    } else {
        $(this).addClass("fa-bookmark-o");
    }
});