(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');
    //后台 文章 获取分类数据
    categoryShow(csrf_token);
    //后台 文章 编辑时，获取文章数据
    var id = $("form#formPosts").find("input[name=id]").val();
    if (id != 0) {
        $.ajax({
            url: "/admin/posts/show",
            type: "POST",
            async: false,
            data: { _token: csrf_token, id: id },
            success: function(data) {
                if (data.success) {
                    $("input[name=title]").val(data.data.title);
                    $("textarea[name=content]").text(data.data.content);
                    $('#selPosts').selectpicker('val', data.data.category_id);
                } else {
                    $.dialog(data);
                }
            }
        });
    }

    //后台 分类 获取数据
    function selCategoryRecursive(data, eleid) {
        if (data.length > 0) {
            $.each(data, function(index, item) {
                var specialChar = "";
                for (var i = 1; i < item.level; i++) {
                    specialChar += "---";
                }
                $(eleid).append('<option value="' + item.id + '">' + specialChar + item.name + '</option>');
                selCategoryRecursive(item.children, eleid);
            });
        }
    }

    function ulCategoryRecursive(data, eleid) {
        $.each(data, function(index, item) {
            var specialChar = "";
            for (var i = 1; i < item.level; i++) {
                specialChar += "---";
            }
            $("#" + eleid).append('<li class="list-group-item"><a href="javascript:void(0);" class="badge delCategory" data-id="' + item.id + '"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a><a href="javascript:void(0);" class="drop-down" data-id="' + item.id + '">' + specialChar + item.name + '</a></li>');
            if (item.children.length > 0) {
                $("#" + eleid).append('<ul class="list-group" id="drop-down-menu-' + item.id + '" style="display:none;"></ul>');
                ulCategoryRecursive(item.children, "drop-down-menu-" + item.id);
            }
        });
    }

    function categoryShow(csrf_token) {
        $.ajax({
            url: "/admin/category/show",
            type: "POST",
            async: false,
            data: { _token: csrf_token },
            success: function(data) {
                if (data.success) {
                    $("#selCategory").html('<option value="0">选择分类</option>');
                    $("#selPosts").html('<option value="0">选择分类</option>');
                    $("#ulCategory").html('');
                    $.each(data.data, function(index, item) {
                        $("#selCategory").append('<option value="' + item.id + '">' + item.name + '</option>');
                        selCategoryRecursive(item.children, "#selCategory");

                        $("#selPosts").append('<option value="' + item.id + '">' + item.name + '</option>');
                        selCategoryRecursive(item.children, "#selPosts");
                    });
                    ulCategoryRecursive(data.data, "ulCategory");
                }
            }
        });
    }

    //后台 分类 刷新头部菜单
    function topicShow(csrf_token) {
        $.ajax({
            url: "/admin/category/topic",
            type: "POST",
            async: false,
            data: { _token: csrf_token },
            success: function(data) {
                if (data.success) {
                    $("#topic").html('');
                    var html = '';
                    $.each(data.data, function(index, item) {
                        html += '<li class="dropdown">';
                        html += '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' + item.name + '<span class="caret"></span></a>';
                        html += '<ul class="dropdown-menu">';
                        if (item.children.length > 0) {
                            $.each(item.children, function(i, t) {
                                html += '<li><a href="/topic/id/' + t.id + '">' + t.name + '</a></li>';
                            });
                        }
                        html += '</ul>';
                        html += '</li>';
                    });
                    $("#topic").html(html);
                }
            }
        });
    }

    //后台 分类 添加
    $("#btnAddCategory").click(function(e) {
        e.preventDefault();
        var params = $("form#formCategory").serializeArray();
        $.ajax({
            url: "/admin/category/store",
            type: "POST",
            async: false,
            data: params,
            success: function(data) {
                if (data.success) {
                    $("#btnAddCategory").attr('data-dismiss', 'modal');
                    categoryShow(csrf_token);
                    $('#selCategory').selectpicker('refresh');
                    $('#postsCategory').selectpicker('refresh');
                    topicShow(csrf_token);
                }
                dialog(data);
            }
        });
    });

    //后台 分类 更新
    $("#btnEditCategory").click(function(e) {
        e.preventDefault();
        var params = $("form#formCategory").serializeArray();
        $.ajax({
            url: "/admin/category/update",
            type: "POST",
            async: false,
            data: params,
            success: function(data) {
                if (data.success) {
                    categoryShow(csrf_token);
                    $('#selCategory').selectpicker('refresh');
                    $('#selPosts').selectpicker('refresh');
                    topicShow(csrf_token);
                }
                $.dialog(data);
            }
        });
    });

    //后台 分类 删除
    $(".delCategory").click(function() {
        $.ajax({
            url: "/admin/category/destroy",
            type: "POST",
            async: false,
            data: { _token: csrf_token, id: $(this).attr('data-id') },
            success: function(data) {
                if (data.success) {
                    categoryShow(csrf_token);
                    $('#selCategory').selectpicker('refresh');
                    $('#selPosts').selectpicker('refresh');
                    topicShow(csrf_token);
                }
                $.dialog(data);
            }
        });
    });

    //后台 分类 点击下拉展示
    $('body').on('click', '.drop-down', function() {
        var eleid = "#drop-down-menu-" + $(this).attr('data-id');
        $(eleid).toggle();
    });

    //后台 文章 添加或更新
    $("#btnPosts").click(function(e) {
        e.preventDefault();
        var params = $("form#formPosts").serializeArray();
        var id = $("form#formPosts").find("input[name=id]").val();
        if (id != 0) {
            $.ajax({
                url: "/admin/posts/update",
                type: "POST",
                async: false,
                data: params,
                success: function(data) {
                    $.dialog(data);
                    if (data.success) setTimeout("window.location.href = '/';", 2000);
                }
            });
        } else {
            $.ajax({
                url: "/admin/posts/store",
                type: "POST",
                async: false,
                data: params,
                success: function(data) {
                    $.dialog(data);
                    if (data.success) setTimeout("window.location.href = '/';", 2000);
                }
            });
        }
    });
})(jQuery);