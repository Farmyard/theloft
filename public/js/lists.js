(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');
    // 前台 列表
    function lists(categoryId, page) {
        var success;
        $.ajax({
            url: "/lists",
            type: "POST",
            async: false,
            data: { _token: csrf_token, category_id: categoryId, page: page },
            success: function(data) {
                if (data.success) {
                    $("#lists").html('');
                    $.each(data.data, function(index, item) {
                        var html = '<div class="item"><h5>' + index + '  <span class="badge">' + item.length + '</span></h5><ul class="list-group">';
                        $.each(item, function(i, t) {
                            if (t.is_del) html += '<s>';
                            html += '<li class="list-group-item" style="margin-bottom: 5px;">';
                            if (t.is_del) {
                                html += '<a class="badge restorePosts" data-id="' + t.id + '"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>'; //恢复按钮
                                html += '<a class="badge delPosts" data-type="force" data-id="' + t.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>'; //删除按钮
                            } else {
                                if (t.del_btn) html += '<a class="badge delPosts" data-type="soft" data-id="' + t.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>'; //删除按钮
                            }
                            if (t.edit_btn) html += '<a class="badge" href="/admin/posts/edit/id/' + t.id + '"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>'; //编辑按钮
                            html += '<a style="color:#5e5d5d;" href="/posts/id/' + t.id + '">' + t.title + '</a>';
                            html += '</li>';
                            if (t.is_del) html += '</s>';
                        });
                        html += '</ul></div>';
                        $("#lists").append(html);
                    });
                    $("#listsPager").show();
                }
                success = data.success;
            }
        });
        return success;
    }

    lists($("#lists").attr('topic'), 1);

    //前台 分页
    $(".page").click(function(e) {
        var categoryId = $("#lists").attr('topic');
        if ($(this).attr('action') == "+") {
            var page = parseInt($(this).attr('current')) + 1;
            if (lists(categoryId, page)) $(".page").attr('current', page);
        } else if ($(this).attr('action') == "-") {
            var page = parseInt($(this).attr('current')) - 1;
            if (page > 0 && lists(categoryId, page)) $(".page").attr('current', page);
        }
    });

    //后台 文章 删除
    $(".delPosts").click(function() {
        $.ajax({
            url: "/admin/posts/destroy",
            type: "POST",
            async: false,
            data: { _token: csrf_token, id: $(this).attr('data-id'), type: $(this).attr('data-type') },
            success: function(data) {
                $.dialog(data);
                if (data.success) setTimeout("location.reload();", 2000);
            }
        });
    });

    //后台 文章 恢复
    $(".restorePosts").click(function() {
        $.ajax({
            url: "/admin/posts/restore",
            type: "POST",
            async: false,
            data: { _token: csrf_token, id: $(this).attr('data-id') },
            success: function(data) {
                $.dialog(data);
                if (data.success) setTimeout("location.reload();", 2000);
            }
        });
    });
})(jQuery);