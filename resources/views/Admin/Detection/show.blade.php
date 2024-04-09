<style>
    .progress-container {
        width: 300px;
        background-color: #f0f0f0;
        border-radius: 5px;
        overflow: hidden;
        float: left;
        margin-left: 10px;
    }

    .progress-bar {
        width: 0;
        height: 20px;
        background-color: #3c8dbc;
        text-align: center;
        line-height: 20px;
        color: white;
    }
</style>
<div>正在监测的关键词集合和监测时间:</div>
<div style="margin-top: 10px;">
    <div style="float: left">检测({{$list["jc"]}}万)：</div>
    <div class="progress-container">
        <div class="progress-bar" style="width: {{$d["jc"]}}px;"></div>
    </div>
    <div style="float: left">{{$d["jc"]}}天</div>
    <div style="clear: both"></div>
</div>

<div style="margin-top: 10px;">
    <div style="float: left">监测({{$list["jjc"]}}万)：</div>
    <div class="progress-container">
        <div class="progress-bar" style="width:{{$d["jjc"]}}px;"></div>
    </div>
    <div style="float: left">{{$d["jjc"]}}天</div>
    <div style="clear: both"></div>
</div>


<div style="margin-top: 10px;">
    <div style="float: left">评估({{$list["pg"]}}万)：</div>
    <div class="progress-container">
        <div class="progress-bar" style="width: {{$d["jjc"]}}px;"></div>
    </div>
    <div style="float: left">{{$d["pg"]}}天</div>
    <div style="clear: both"></div>
</div>

<div style="margin-top: 10px;">
    <div style="float: left">鉴定({{$list["jd"]}}万)：</div>
    <div class="progress-container">
        <div class="progress-bar" style="width: {{$d["jd"]}}px;"></div>
    </div>
    <div style="float: left">{{$d["jd"]}}天</div>
    <div style="clear: both"></div>
</div>
<div style="margin-top: 10px; font-style: italic;font-size: 12px">*系统正在每日监测包含以上所有关键词的网民搜索量</div>
<h1 style="margin-top: 50px;font-size: 24px">提交数据查询需求:</h1>
<div style="margin-top: 10px;">
    <div style="float: left">查 询 人：</div>
    <div style="float: left;margin-left: 8px;"><input type="text" id="name" name="name" height="15px;"></div>
    <div style="clear: both"></div>
</div>
<div style="margin-top: 10px;">
    <div style="float: left">查询条件：</div>
    <div style="float: left"><input type="text" name="name" id="where" height="15px;"></div>
    <div style="clear: both"></div>
</div>
<button style="width: 100px;margin-top: 20px;" onclick="sl()">提交</button>
<div style="margin-top: 10px; font-style: italic;font-size: 12px">*系统管理员在完成查询后通过钉钉发送查询结果</div>
<script>
    function sl() {
        var name=$("#name").val();
        var where=$("#where").val();
        //ajax添加报价人
        var toUrl = '/admin/detection-list/getSelectWhere';
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: toUrl,
            type: 'post',
            async: false,
            data:{name:name,where:where},
            dataType: 'json',
            success: function (res) {
                if(res.status==1){
                    toastr.success("提交成功"); // 提示文字
                }
                else{
                    toastr.error("提交失败"); // 提示文字
                }
            },
            error: function () {
            }
        })

        
    }
</script>