<link rel="stylesheet" href="/css/mystyle.css">
<div class="box-header with-border">
    <h3 class="box-title">显示</h3>

    <div class="box-tools">
        @if($commodityLogList['c_id']!='')
        <div class="btn-group pull-right" style="margin-right: 5px">
            <a target="_blank" href="<?php echo config('system.PUT_CACHE').'type/detail?id='.$commodityLogList['id'].'.html';?>" class="btn btn-sm btn-primary" title="预览">
               <span class="hidden-xs"> 预览</span>
            </a>
        </div>
        @endif
        <div class="btn-group pull-right" style="margin-right: 5px">
            <a href="./<?php echo $commodityLogList['id'];?>/edit" class="btn btn-sm btn-primary" title="编辑">
                <i class="fa fa-eye"></i><span class="hidden-xs"> 编辑</span>
            </a>
        </div>
        <div class="btn-group pull-right" style="margin-right: 5px">
            <a href="./" class="btn btn-sm btn-default" title="列表">
                <i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
        </div>
    </div>
</div>
<div class="commodity" >
    <div class="commodity-details">
        <div class="h-commodity"><h4 >商品详情</h4></div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">商品名称：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['commodity_name']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">商品价格：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="<?php echo strip_tags($commodityLogList['commodity_price']); ?>">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">业务描述：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$commodityLogList['commodity_description']}}
            </textarea>
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">专题URL：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['commodity_url']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">标签：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['commodity_label']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">设为热门：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['commodity_hot']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">是否显示：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['commodity_shown']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">场景：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$commodityLogList['commodity_scenarios']}}
            </textarea>
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">业务描述：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$commodityLogList['commodity_description']}}
            </textarea>
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">商品详情：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$commodityLogList['commodity_details']}}
            </textarea>
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">提交时间：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['created_at']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">分类：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$commodityLogList['p_id']}}-{{$commodityLogList['g_id']}}-{{$commodityLogList['c_id']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
        <label for="business_customer_name" class="col-sm-2 control-label">缩略图：</label>
        <span class="select2-selection select2-selection--single">
        <img src="/{{ $commodityLogList['commodity_thumb'] }}" width="160px" height="160px">
        </span>
        </div>
        </div>

    <div class="commodity-supplier-list">

    </div>
    <div class="commodity-offerer-list">

    </div>
    <div class="clear-float"></div>
</div>

<script>
    //商品ajax
    var requestUrl = '/admin/commodity/supplierAjax';
    var requestData = {'pageSize':'8','pageNum':'1','id':"<?php echo $commodityLogList['id'];?>"};
    queryList();
    function queryList(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestUrl,
            type:'post',
            async:false,
            data:requestData,
            dataType:'html',
            success:function(res){
                $(".commodity-supplier-list").html(res);
            },
            error:function(){
            }
        })
    }

    //报价人
    //供应商ajax显示
    var requestOffererUrl = '/admin/commodity/offererAjax';
    var requestOffererData = {'pageSize':'8','pageNum':'1','id':"<?php echo $commodityLogList['id'];?>"};
    queryOffererList();
    function queryOffererList(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestOffererUrl,
            type:'post',
            async:false,
            data:requestOffererData,
            dataType:'html',
            success:function(res){
                console.log(res);
                $(".commodity-offerer-list").html(res);
            },
            error:function(){
            }
        })
    }

</script>