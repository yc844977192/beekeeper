<script src="https://cdnjs.cloudflare.com/ajax/libs/peity/3.3.0/jquery.peity.min.js"></script>
{{--<canvas id="lineChartContainer{{$trend_name}}" width="560px" height="120px" style="border: solid 1px">--}}
    {{--<!-- 折线图将在此处绘制 -->--}}
{{--</canvas>--}}
<style>
    .peity{
        width: 60px;
        height: 25px;
    }
</style>
<div>
    <span class="line{{$trend_name}}" id="linechart" data-peity-width="80">{{$str}}</span>
</div>
<script>
    $(function(){
        $(".line{{$trend_name}}").peity("line");
    });
    function insertBlackList(id) {
        var requestUrl = '/admin/arithmetic/insert_black_list';
        var requestData = {'warehouse_id':id};
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
                    toastr.success("添加成功"); // 提示文字
                     window.location.reload(); // 刷新当前页面
                }
                else{

                    toastr.error("添加失败"); // 提示文字
                }
            },
            error:function(){
            }
        })

    }
</script>
