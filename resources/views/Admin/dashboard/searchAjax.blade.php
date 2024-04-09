<table class="table table-striped table-hover">
    <thead>
    <tr >
        <td>名称</td>
        <td>描述</td>
        <td>电话</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    @foreach($list as $k=>$v)
        <tr>
            <td>{{$v['name']}}</td>
            <td>{{$v['split_from_data']}}</td>
            <td>{{$v['mobile']}}</td>
            <td>{{$v['created_at']}}</td>
            <td><a href="{{$v['url']}}">查看详情</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
<input type="hidden" class="pageTotal2" value="<?php echo $resProduct['totalPage']?>">
<input type="hidden" class="total2" value="<?php echo $resProduct['total']?>">
<div id="ajx2">

</div>

<script>
    var pageTotal2=$(".pageTotal2").val();
    var total2=$(".total2").val();


    $(document).ready(function(){
        for(var i=1;i<=pageTotal2;i++){
            var s='<input type="button" class="int2" id="id'+i+'" value="'+i+'" onclick="btn2('+i+')"/>';
            $("#ajx2").append(s);
            if(i=={{$cpage}}){
                $("#id"+i).css("background-color","#8D8D8D");
            }

        }
    });
    function btn2(pageNum) {
        var  searchKeyWord=$("#searchKeyWord").val();
        var requestUrl = '/admin/api/getSearchResult';
        var requestData = {'pageSize':'10','pageNum':pageNum,'searchKeyWord':searchKeyWord};
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestUrl,
            type:'post',
            async:false,
            data:requestData,
            dataType:'html',
            success:function(res){
                //console.log(res);
                $(".show-search-result").html(res);
            },
            error:function(){
            }
        })
    }
</script>