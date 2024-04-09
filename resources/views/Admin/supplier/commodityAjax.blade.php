<h4>关联商品</h4>
<div class="info_table">
    <table class="table table-striped table-hover">
        <thead>
        <tr class="bt-commodity">
            <td>商品名</td>
            <td>提交时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        @foreach($commodityLists as $k => $v)
        <tr class="bt-commodity-details">
            <td>{{$v['commodity_name']}}</td>

            <td>{{$v['created_at']}}</td>
            <td><a href="/admin/commodity/{{$v['id']}}">查看详情</a></td>
        </tr>

        @endforeach
        </tbody>
    </table>
    <input type="hidden" class="pageTotal" value="<?php echo $resProduct['totalPage']?>">
    <input type="hidden" class="total" value="<?php echo $resProduct['total']?>">
    <div id="ajx1">

    </div>
</div>
<style>
    .int1{
        position: relative;
        background-color: #ffffff;
        border:2px solid #008cba;
        border-radius:4px;
        font-size: 10px;
        color:  #87cefa;
        padding: 5px 8px;
        margin: 4px 2px;
        text-align: center;
        -webkit-transition-duration: 0.4s; /* Safari */
        transition-duration: 0.4s;
        text-decoration: none;
        overflow: hidden;
        cursor: pointer;
    }
</style>
<script>
    var pageTotal=$(".pageTotal").val();
    var total=$(".total").val();

    for(var i=0;i++;i<=pageTotal){
        console.log(total);
    }

    $(document).ready(function(){
        for(var i=1;i<=pageTotal;i++){
            var s='<input type="button" class="int1" id="id'+i+'" value="'+i+'" onclick="btn('+i+')"/>';
            $("#ajx1").append(s);
            if(i=={{$cpage}}){
                $("#id"+i).css("background-color","#8D8D8D");
            }
        }
    });
    function btn(pageNum) {
        var requestUrl = '/admin/supplier/commodityAjax';
        var requestData = {'pageSize':'8','pageNum':pageNum,'id':"<?php echo $id;?>"};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestUrl,
            type:'post',
            async:false,
            data:requestData,
            dataType:'html',
            success:function(res){
                $(".supplier-commodity-list").html(res);
            },
            error:function(){
            }
        })
    }

</script>