<style>
    .ext-icon {
        color: rgba(0,0,0,0.5);
        margin-left: 10px;
    }
    .installed {
        color: #00a65a;
        margin-right: 10px;
    }
</style>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">商品</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="products-list product-list-in-box">

            <li class="item">
                <div class="product-img">
                   商品总数：
                </div>
                <div class="product-info">
                    {{$commodityCount['sum']}}
                </div>
            </li>
            <li class="item">
                <div class="product-img">
                    今日新增：
                </div>
                <div class="product-info">
                    {{$commodityCount['today_sum']}}
                </div>
            </li>
            <div style="padding-left: 5px;padding-top: 10px;"><h4>报价单</h4></div>
            <table class="table table-striped" style="margin-top: 10px;">
                <tr>
                    <td width="240px">报价单总数：</td>
                    <td><span> {{$quotationCount['sum']}}</span></td>
                </tr>
                <tr>
                    <td width="240px">今日新增：</td>
                    <td><span >{{$quotationCount['today_sum']}}</span></td>
                </tr>
            </table>

            <!-- /.item -->
        </ul>
    </div>
    <!-- /.box-body -->
    {{--<div class="box-footer text-center">--}}
        {{--<a href="#" target="_blank" class="uppercase">华安检测</a>--}}
    {{--</div>--}}
    <!-- /.box-footer -->
</div>