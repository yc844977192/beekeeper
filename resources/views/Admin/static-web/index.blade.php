<style>
    /* 样式可根据需要进行调整 */
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* 半透明黑色遮罩 */
        z-index: 1000;
    }
    .ls{
        display: none;
        position: fixed;
        top: 300px;
        left: 45%;
        z-index: 999;
        width: 15%;
        font-size: 18px;
        color: #1f648b;
    }
</style>
<div id="overlay"></div>
<div class="ls">静态页面生成中，请稍等...</div>
<button onclick="static_web({{$id}})" id="showBtn">生成静态</button>
@if($is_wap)
<button onclick="wap_static_web({{$id}})" id="showBtn">生成手机静态</button>
@endif
<script>
    function static_web(id){
        $('#overlay').show();
        $('.ls').show();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:"/admin/static-web/setStatic",
            type:'post',
            async:true,
            data:{id:id},
            dataType:'json',
            timeout: 999999999, // 设置一个很大的超时时间
            success:function(res){
                console.log(res);
                if(res){
                    $('#overlay').hide();
                    $('.ls').hide();
                    toastr.success("更新成功"); // 提示文字
                }
                else{
                    $('#overlay').hide();
                    $('.ls').hide();
                    toastr.error("更新失败"); // 提示文字
                }
            },
            error:function(){
                $('#overlay').hide();
                $('.ls').hide();
            }
        })
    }

    function wap_static_web(id){
        $('#overlay').show();
        $('.ls').show();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:"/admin/static-web/setWapStatic",
            type:'post',
            async:true,
            data:{id:id},
            dataType:'json',
            timeout: 999999999, // 设置一个很大的超时时间
            success:function(res){
                if(res){
                    $('#overlay').hide();
                    $('.ls').hide();
                    toastr.success("更新成功"); // 提示文字
                }
                else{
                    $('#overlay').hide();
                    $('.ls').hide();
                    toastr.error("更新失败"); // 提示文字
                }
            },
            error:function(){
                $('#overlay').hide();
                $('.ls').hide();
            }
        })
    }
</script>