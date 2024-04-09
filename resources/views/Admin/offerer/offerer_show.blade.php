<link rel="stylesheet" href="/css/mystyle.css">
<div class="box-header with-border">
    <h3 class="box-title">显示</h3>

    <div class="box-tools">
        <div class="btn-group pull-right" style="margin-right: 5px">
            <a href="./<?php echo $offererList['id'];?>/edit" class="btn btn-sm btn-primary" title="编辑">
                <i class="fa fa-eye"></i><span class="hidden-xs"> 编辑</span>
            </a>
        </div><div class="btn-group pull-right" style="margin-right: 5px">
            <a href="./" class="btn btn-sm btn-default" title="列表">
                <i class="fa fa-list"></i><span class="hidden-xs">&nbsp;列表</span></a>
        </div>
    </div>
</div>
<div class="commodity" >
    <div class="commodity-details">
        <div class="h-commodity"><h4 >报价人详情</h4></div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">姓名：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_name']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">手机：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_mobile']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">职位：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_position']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">邮箱：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_email']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">地址：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_address']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">微信：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['offerer_wechat']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">备注：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$offererList['offerer_remark']}}
            </textarea>
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">提交时间：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$offererList['created_at']}}">
                </div>
            </div>
        </div>
    </div>
    <div class="offerer-supplier-list">

    </div>
    <div class="offerer-commodity-list">

    </div>
    <div class="clear-float"></div>
</div>
<script>
    //供应商ajax显示
    var requestSupplierUrl = '/admin/offerer/supplierAjax';
    var requestSupplierData = {'pageSize':'8','pageNum':'1','id':"<?php echo $offererList['id'];?>"};
    querySupplierList();
    function querySupplierList(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:requestSupplierUrl,
            type:'post',
            async:false,
            data:requestSupplierData,
            dataType:'html',
            success:function(res){
                console.log(res);
                $(".offerer-supplier-list").html(res);
            },
            error:function(){
            }
        })
    }

    //商品ajax
    var requestUrl = '/admin/offerer/commodityAjax';
    var requestData = {'pageSize':'8','pageNum':'1','id':"<?php echo $offererList['id'];?>"};
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
                $(".offerer-commodity-list").html(res);
            },
            error:function(){
            }
        })
    }

</script>