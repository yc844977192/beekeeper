<link rel="stylesheet" href="/cdata/static/common.css">
<link rel="stylesheet" href=/cdata/static/dialogue.css">
<link rel="stylesheet" href="/cdata/static/wenda.css">
<link rel="stylesheet" href="/cdata/static/assets/css/amazeui.min.css">
<link rel="stylesheet" href="/cdata/static/assets/css/app.css">
<style>
#article-wrapper { padding-left: 0 !important;background-color:#FFF6E9;}
@media screen and (max-width:598px) {
.am-sr-only { position: relative; }
.am-icon-bars { display: none; }
}
</style>
<body>
<div class="layout-wrap">
    <div class="layout-content">
        <div class="container">
            <article class="article" id="article">
                <div class="article-box">

                    <ul id="article-wrapper">
                    </ul>
                    <div class="creating-loading" data-flex="main:center dir:top cross:center">
                        <div class="semi-circle-spin"></div>
                    </div>
                    <div id="fixed-block">
                        <div class="precast-block" id="kw-target-box" data-flex="main:left cross:center" style="margin-top: 0;">
                            <div id="target-box" class="box">
                                <input type="text" name="kw-target" placeholder="来问点什么吧" id="kw-target">
                            </div>
                            <div class="right-btn layout-bar">
                                {{--<input type="hidden" id="intoRoles" value="{{$role_content}}">--}}
                                <p class="btn ai-btn bright-btn" id="ai-btn" onclick="aiClick()" data-flex="main:center cross:center">
                                    查询</p>
                            </div>
                        </div>
                    </div>
                </div></article>
        </div>
    </div>
</div>
<script>
    function aiClick() {
        const safeHtml = $('#kw-target').val() || '';
        if (!safeHtml) {
            return toast({ time: 1000, msg: '来问点什么吧' });
        }
        var number = new Date().getSeconds();
        $('#article-wrapper').append('<li class="article-title2">会员:<li>');
        $('#article-wrapper').append('<li class="article-content2" id=content'+number+'><pre>'+safeHtml+'</pre></li>');
        createArticle();
    }
    function createArticle() {
        const safeHtml = $('#kw-target').val() || ''
        if (!safeHtml) {
            return toast({ time: 1000, msg: '来问点什么吧' })
        }
        var locationHref = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        locationHref.forEach(function (val) {
            var parameter = val.slice(0, val.indexOf('=')); //属性
            var data = val.slice(val.indexOf('=') + 1); //值
            if(parameter == 'user_id'){
                user_id = data;
            }
        })

        $('#article').removeClass('created')
        $('.creating-loading').addClass('isLoading')
        var intoRoles=$("#intoRoles").val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'cdata/delAjax3_5',
            type: 'post',
            dataType: 'json',
            data:{'gptinput':safeHtml,'intoRoles':intoRoles},
            success: function (res) {
                console.log(res);
                if(res.code !== 200){
                    toast({ time: 1000, msg: res.html});
                    _$('#kw-target').val('')
                    _$('.creating-loading').removeClass('isLoading')
                    return;
                }
                var title = res.data.title;
                var content = res.data.html;
                //var number = new Date().getSeconds();
                var number = new Date().getTime();
                console.log(number);

                _$('#article-wrapper').append('<li class="article-title">' + title + '<li>')
                _$('#article-wrapper').append('<li class="article-content" id=content'+number+'><pre></pre></li>')
                var i = 0;
                var interval;
                if (i > content.length){
                    clearInterval(interval)

                } else {
                    interval = setInterval(() => {
                        i++;
                        str = content.substr(0, i);
                        _$('#content' + number).find('pre').text(str);
                    }, 60);
                }
                toast({ time: 1000, msg: 'AI生成成功' })
                _$('#kw-target').val('')
                _$('.creating-loading').removeClass('isLoading')
            }
        })
    }
</script>