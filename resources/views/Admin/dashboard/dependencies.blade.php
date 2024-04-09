<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">商机</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="box-body dependencies">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <td width="240px">商机总数：</td>
                    <td><span>{{$businessCount['sum']}}</span></td>
                </tr>
                <tr>
                    <td width="240px">今日新增：</td>
                    <td><span >{{$businessCount['today_sum']}}</span></td>
                </tr>
            </table>
            <div style="margin-left: 10px;">商机状态：</div>
            <div style="margin-left: 10px;">
                <div style="float: left;margin-top:10px;"><span>待处理：</span><span>{{ $businessCount['d_sum']}}</span></div>
                <div style="float: left;margin-left: 10px;margin-top:10px;"><span>已分配待跟进：</span><span>{{ $businessCount['g_sum']}}</span></div>
                <div style="float: left;margin-left: 10px;margin-top:10px;"><span>已跟进待确定：</span><span>{{ $businessCount['q_sum']}}</span></div>
                <div style="float: left;margin-left: 10px;margin-top:10px;"><span>失败：</span><span>{{ $businessCount['s_sum']}}</span></div>
                <div style="float: left;margin-top:10px;"><span>暂缓：</span><span>{{ $businessCount['z_sum']}}</span></div>
                <div style="float: left;margin-left: 10px;margin-top:10px;"><span>成交：</span><span>{{ $businessCount['c_sum']}}</span></div>
                <div style="clear:both"></div>
            </div>
            <div style="padding-left: 5px;padding-top: 10px;"><h4>客户</h4></div>

            <table class="table table-striped" style="margin-top: 10px;">
                <tr>
                    <td width="240px">客户总数：</td>
                    <td><span>{{$customerCount['sum']}}</span></td>
                </tr>
                <tr>
                    <td width="240px">今日新增：</td>
                    <td><span >{{$customerCount['today_sum']}}</span></td>
                </tr>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>