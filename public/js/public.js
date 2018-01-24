(function($) {
    $.dialog = function(data) {
        if (data.success) {
            $("#dialog-msg").addClass("alert-success");
            $("#dialog-msg").removeClass("alert-danger")
        } else {
            $("#dialog-msg").addClass("alert-danger");
            $("#dialog-msg").removeClass("alert-success");
        }
        $("#dialog-msg").html(data.msg);
        $('#dialog').modal({
            keyboard: true
        })
    }

    //前台 登出
    $("#logout").click(function(e) {
        e.preventDefault();
        $("#logoutForm").submit();
    });
})(jQuery);