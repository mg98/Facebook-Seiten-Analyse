$(function() {
    
    $("#start-analysis").click(function() {
        var $startButton = $(this).find("button");

        if ($startButton.hasClass("disabled")) {
            return false;
        }

        var href = $(this).prop("href");

        $startButton
            .addClass("disabled")
            .find("i")
            .removeClass("fa-play")
            .addClass("fa-circle-o-notch fa-spin fa-1x fa-fw margin-bottom");

        $("title").text("Analysiere ...")

        $.get(href, function(res) {
            if (res == 'success') {
                window.location.href = href + "/success";
            } else {
                window.location.href = href + "/failure?exception=" + encodeURI(res);
            }

        });

        $("#alerts .alert").hide();
        $("#alerts .alert-info").slideDown();

        return false;
    });
    
    $(".fbpage-delete").click(function() {
        var id = $(this).attr("id");
        var name = $(this).attr("name");

        if (
            confirm("Bist du sicher, dass du die Seite \"" + name + "\" (ID: " + id + ") löschen möchtest?" + 
                "\n\nAlle damit zusammenhängenden Daten, sowie die Nutzerdaten werden dadurch unwiderruflich gelöscht.")
        ) {
            $(this).parent("form").submit();
        }
    });

    $("#analysis-reset").click(function() {
        return confirm("Du bist dabei alle Nutzerdaten dieser Seite unwideruflich zu löschen. Möchtest du fortfahren?");
    });
    
});
//# sourceMappingURL=all.js.map
