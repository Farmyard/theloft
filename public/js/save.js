(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');

    var formCategory = new Vue({
        el: '#formCategory',
        data: {
            options: []
        },
        methods: {
            add: function(e) {
                e.preventDefault();
                var params = $("#formCategory").serializeArray();
                $.ajax({
                    url: "/admin/category/store",
                    type: "POST",
                    async: false,
                    data: params,
                    success: function(data) {
                        if (data.success) {
                            category.show();
                            top.topic.show();
                        }
                        $.dialog(data);
                    }
                });
            },
            edit: function(e) {
                e.preventDefault();
                var params = $("#formCategory").serializeArray();
                $.ajax({
                    url: "/admin/category/update",
                    type: "POST",
                    async: false,
                    data: params,
                    success: function(data) {
                        if (data.success) {
                            category.show();
                            top.topic.show();
                        }
                        $.dialog(data);
                    }
                });
            }
        }
    });

    var formPosts = new Vue({
        el: '#formPosts',
        data: {
            options: []
        },
        methods: {
            show: function(id) {
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
                                $('select[name=category_id]').selectpicker('val', data.data.category_id);
                            } else {
                                $.dialog(data);
                            }
                        }
                    });
                }
            },
            save: function(e) {
                e.preventDefault();
                var params = $("#formPosts").serializeArray();
                var id = $("#formPosts").find("input[name=id]").val();
                if (id != 0) {
                    $.ajax({
                        url: "/admin/posts/update",
                        type: "POST",
                        async: false,
                        data: params,
                        success: function(data) {
                            if (data.success) {
                                $.dialog(data, "/");
                            } else {
                                $.dialog(data);
                            }
                        }
                    });
                } else {
                    $.ajax({
                        url: "/admin/posts/store",
                        type: "POST",
                        async: false,
                        data: params,
                        success: function(data) {
                            if (data.success) {
                                $.dialog(data, "/");
                            } else {
                                $.dialog(data);
                            }
                        }
                    });
                }
            }
        }
    });

    var category = new Vue({
        el: '#categoryLists',
        data: {
            lists: [],
        },
        watch: {
            lists: function(val) {
                $('.selectpicker').selectpicker('refresh');
                formPosts.show($("#formPosts").find("input[name=id]").val());
            }
        },
        methods: {
            show: function() {
                // 定义子级组件
                Vue.component('lists', {
                    template: '#lists-template',
                    props: {
                        model: Object
                    },
                    methods: {
                        destroy: function(e) {
                            $.ajax({
                                url: "/admin/category/destroy",
                                type: "POST",
                                async: false,
                                data: { _token: csrf_token, id: $(e.target).attr('data-id') },
                                success: function(data) {
                                    if (data.success) {
                                        category.show();
                                        top.topic.show();
                                    }
                                    $.dialog(data);
                                }
                            });
                        }
                    }
                });
                $.ajax({
                    url: "/admin/category/show",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token },
                    success: function(json) {
                        if (json.success) {
                            category.lists = json.lists;
                            formCategory.options = json.options;
                            formPosts.options = json.options;
                        }
                    }
                });
            },
        }
    });

    category.show();

    //后台 分类 点击下拉展示
    $('body').on('click', '.drop-down', function() {
        var eleid = "#drop-down-menu-" + $(this).attr('data-id');
        $(eleid).toggle();
    });
})(jQuery);