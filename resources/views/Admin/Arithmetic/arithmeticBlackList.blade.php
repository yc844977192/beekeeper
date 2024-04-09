<button onclick="delete_blackList({{$warehouse_id}})">-</button>
<script>
    function delete_blackList(warehouse_id) {
        var requestUrl = '/admin/arithmetic_blacklist/delete_blackList';
        var requestData = {'warehouse_id':warehouse_id};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestUrl,
            type:'post',
            async:false,
            data:requestData,
            dataType:'json',
            success:function(res){
                console.log(res);
                if(res){
                    toastr.success("移除成功"); // 提示文字
                    window.location.reload(); // 刷新当前页面
                }
                else{

                    toastr.error("移除失败"); // 提示文字
                }
            },
            error:function(){
            }
        })

    }
</script>