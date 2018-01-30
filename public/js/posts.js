(function($) {
    var csrf_token = $("meta[name=csrf-token]").attr('content');
    // 前台 列表
    var posts = new Vue({
        el: '#posts',
        data: {
            title: '',
            content: '',
            category: '',
            user: '',
            time: ''
        },
        methods: {
            show: function() {
                $.ajax({
                    url: "/posts",
                    type: "POST",
                    async: false,
                    data: { _token: csrf_token, id: $("#posts").attr('postsId') },
                    success: function(json) {
                        if (json.success) {
                            posts.title = json.data.title;
                            posts.content = json.data.content;
                            posts.category = json.data.category;
                            posts.user = json.data.user;
                            posts.time = json.data.time;
                        }
                    }
                });
            }
        }
    })

    posts.show();
})(jQuery);