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
});