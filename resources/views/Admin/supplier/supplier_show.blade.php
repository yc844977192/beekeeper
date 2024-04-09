<link rel="stylesheet" href="/css/mystyle.css">
<div class="box-header with-border">
    <h3 class="box-title">显示</h3>

    <div class="box-tools">
        <div class="btn-group pull-right" style="margin-right: 5px">
            <a href="./<?php echo $supplierList['id'];?>/edit" class="btn btn-sm btn-primary" title="编辑">
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
        <div class="h-commodity"><h4 >供应商详情</h4></div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">名称：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_name']}}">
                </div>
            </div>
        </div>
        {{--<div class="form-group" style="overflow: hidden;">--}}
            {{--<label for="business_customer_name" class="col-sm-2 control-label">分类：</label>--}}
            {{--<div class="col-sm-8">--}}
                {{--<div class="input-group" style="width: 100%">--}}
                    {{--<input type="text" id="" name=""  class="form-control" value="{{$supplierList['p_id']}}">--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">性质：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_nature']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">网址：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_website']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">经营状态：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_state']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">电话：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_mobile']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">注册资本：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_capital']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">地址：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <textarea name="business_requirement" class="form-control business_states_log" rows="5">{{$supplierList['supplier_address']}}
            </textarea>
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">合作状态：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_cooperation_state']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">邮箱：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_email']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">微信：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_wechat']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">注册资本：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_capital']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">小程序：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_applet']}}">
                </div>
            </div>
        </div>      <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">公众号：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_accounts']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">公司出现总次数：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_total_number']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">关键词总次数：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_total_number_search']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">业务总次数：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_total_number_business']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">每天出现次数：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['supplier_number_everday']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">省：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['province_id']}}">
                </div>
            </div>
        </div>
        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">市：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['city_id']}}">
                </div>
            </div>
        </div>     <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">区：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['district_id']}}">
                </div>
            </div>
        </div>

        <div class="form-group" style="overflow: hidden;">
            <label for="business_customer_name" class="col-sm-2 control-label">提交时间：</label>
            <div class="col-sm-8">
                <div class="input-group" style="width: 100%">
                    <input type="text" id="" name=""  class="form-control" value="{{$supplierList['created_at']}}">
                </div>
            </div>
        </div>
    </div>
    <div class="supplier-commodity-list"></div>
    <div class="supplier-offerer-list"></div>
    <div class="clear-float"></div>
</div>
<script>
    //商品ajax
    var requestUrl = '/admin/supplier/commodityAjax';
    var requestData = {'pageSize':'8','pageNum':'1','id':"<?php echo $supplierList['id'];?>"};
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
                $(".supplier-commodity-list").html(res);
            },
            error:function(){
            }
        })
    }

    //报价人
    //供应商ajax显示
    var requestOffererUrl = '/admin/supplier/offererAjax';
    var requestOffererData = {'pageSize':'8','pageNum':'1','id':"<?php echo $supplierList['id'];?>"};
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
                $(".supplier-offerer-list").html(res);
            },
            error:function(){
            }
        })
    }

</script>