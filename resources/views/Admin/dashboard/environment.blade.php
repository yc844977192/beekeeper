<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">供应商</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <td width="240px">供应商总数：</td>
                    {{--<td><span class="label label-primary"> {{$supplierCount['sum']}}</span></td>--}}
                    <td><span> {{$supplierCount['sum']}}</span></td>
                </tr>
                <tr>
                    <td width="240px">今日新增：</td>
                    <td><span > {{$supplierCount['today_sum']}}</span></td>
                </tr>


            </table>
            <div style="padding-left: 5px;padding-top: 10px;"><h4>报价人</h4></div>

            <table class="table table-striped" style="margin-top: 10px;">
                <tr>
                    <td width="240px">报价人总数：</td>
                    <td><span>{{$offererCount['sum']}}</span></td>
                </tr>
                <tr>
                    <td width="240px">今日新增：</td>
                    <td><span >{{$offererCount['today_sum']}}</span></td>
                </tr>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
<input type="hidden" id="password_is_modify" value="{{$password_is_modify}}">
<script>
//    if($("#password_is_modify").val()==0){
//        alert("为了不影响正常使用，请修改初始密码！");
//    }
</script>