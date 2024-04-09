<input type="button" id="ceshi" onclick="tijiao()" value="更新前端缓存">
<script>
    function tijiao() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'/admin/nav/put_menu_cache',
            type:'post',
            async:false,
            //data:{'name':'ceshi'},
            success:function(res){
                console.log(res);
                if(res.status==1){
                    alert("更新成功");
                }
         }
    })};
</script>