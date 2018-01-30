(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');

    $.dialog = function(data, url) {
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
        });

        setTimeout(function() {
            $("#dialog").modal("hide");
            if (url) window.location = url;
        }, 1000);
    }

    //前台 登出
    $("#logout").click(function(e) {
        e.preventDefault();
        $("#logoutForm").submit();
    });

    top.topic = new Vue({
        el: '#topic',
        data: {
            navbar: []
        },
        methods: {
            show: function() {
                $.ajax({
                    url: "/topic",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token },
                    success: function(json) {
                        if (json.success) {
                            top.topic.navbar = json.data;
                        }
                    }
                });
            },
        }
    });
    top.topic.show();
})(jQuery);