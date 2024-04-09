<input type="button" id="ceshi" onclick="tijiao()" value="更新全部商品状态">
<script>
    function tijiao() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'/admin/offerer/setCommodity',
            type:'post',
            async:false,
            dataType:'json',
            success:function(res){
                if(res.status==1){
                    alert("更新成功");
                }
         }
    })};
</script>