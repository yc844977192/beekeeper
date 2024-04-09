<h4>关联供应商</h4>
<div class="info_table">
    <table class="table table-striped table-hover">
        <thead>
        <tr class="bt-commodity">
            <td>供应商</td>
            <td>电话</td>
            <td>创建时间</td>
            <td>操作</td>
        </tr>
        </thead>
        <tbody>
        @foreach($supplierLists as $key => $value)
            <tr class="bt-commodity-details">
                <td>{{$value['supplier_name']}}</td>
                <td>{{$value['supplier_mobile']}}</td>
                <td>{{$value['province_id']}}</td>
                <td><a href="/admin/supplier/{{$value['id']}}">查看详情</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <input type="hidden" class="pageTotal2" value="<?php echo $resProduct['totalPage']?>">
    <input type="hidden" class="total2" value="<?php echo $resProduct['total']?>">
    <div id="ajx2">

    </div>
</div>
<style>
    .int2{
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
    var pageTotal2=$(".pageTotal2").val();
    var total2=$(".total2").val();


    $(document).ready(function(){
        for(var i=1;i<=pageTotal2;i++){
            var s='<input type="button" class="int2" id="ids'+i+'" value="'+i+'" onclick="btn2('+i+')"/>';
            $("#ajx2").append(s);
            if(i=={{$cpage}}){
                $("#ids"+i).css("background-color","#8D8D8D");
            }

        }
    });
    function btn2(pageNum) {
        var requestUrl = '/admin/offerer/supplierAjax';
        var requestData = {'pageSize':'8','pageNum':pageNum,'id':"<?php echo $id;?>"};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestUrl,
            type:'post',
            async:false,
            data:requestData,
            dataType:'html',
            success:function(res){
                $(".offerer-supplier-list").html(res);
            },
            error:function(){
            }
        })
    }

</script>