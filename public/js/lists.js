(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');
    // 前台 列表
    var lists = new Vue({
        el: '#lists',
        data: {
            lists: [],
            total: 0,
            page: 1,
        },
        methods: {
            show: function(page) {
                $.ajax({
                    url: "/lists",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token, category_id: $("#lists").attr('topic'), page: page },
                    success: function(json) {
                        if (json.success) {
                            lists.lists = json.data;
                            lists.total = json.total;
                            lists.page = json.page;
                        }
                    }
                });
            },
            destroy: function(e) {
                $.ajax({
                    url: "/admin/posts/destroy",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token, id: $(e.target).attr('data-id'), type: $(e.target).attr('data-type') },
                    success: function(data) {
                        $.dialog(data, '/');
                        lists.show(1);
                    }
                });
            },
            restore: function(e) {
                $.ajax({
                    url: "/admin/posts/restore",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token, id: $(e.target).attr('data-id') },
                    success: function(data) {
                        $.dialog(data);
                        lists.show(1);
                    }
                });
            },
            pager: function(e) {
                var action = $(e.target).attr('action');
                if (action == '+') {
                    var page = parseInt(this.page) + 1;
                } else {
                    var page = parseInt(this.page) - 1;
                }
                if (page > 0 && page <= this.total) this.show(page);
            }
        }
    })

    lists.show(1);
})(jQuery);