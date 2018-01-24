(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');
    //前台 文章
    function postsShow(csrf_token) {
        $.ajax({
            url: "/posts",
            type: "POST",
            async: false,
            data: { _token: csrf_token, id: $("#posts").attr('postsId') },
            success: function(data) {
                if (data.success) {
                    $("#title").html(data.data.title);
                    $("#content").html(data.data.content);
                    $("#category").append(data.data.category);
                    $("#user").append(data.data.user);
                    $("#time").append(data.data.time);
                } else {
                    $("#posts").html("");
                }
            }
        });
    }

    postsShow(csrf_token);
})(jQuery);