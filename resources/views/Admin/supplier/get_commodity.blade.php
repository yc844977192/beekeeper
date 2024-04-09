    {{--<link rel="stylesheet" href="/zTree/css/demo.css" type="text/css">--}}
    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/zTree/js/jquery.ztree.excheck.js"></script>
    <SCRIPT type="text/javascript">
        var setting = {
            treeObj:null,
            check: {
                enable: true,
                chkStyle: "checkbox",
                chkboxType: { "Y": "ps", "N": "ps" }
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            async:{
                autoParam:["id", "name"],
                enable:true,
            }
        };

        var zNodes = <?php echo $tlist;?>;

        function setCheck() {
        }
        function showCode(str) {
            if (!code) code = $("#code");
            code.empty();
            code.append("<li>" + str + "</li>");
        }
        $(document).ready(function(){
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            setCheck();
            $("#py").bind("change", setCheck);
            $("#sy").bind("change", setCheck);
            $("#pn").bind("change", setCheck);
            $("#sn").bind("change", setCheck);
        });
        //获取所有选中节点的值
        function GetCheckedAll() {
            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getCheckedNodes(true);
            var newnodes = treeObj.getChangeCheckedNodes();
            var oldCommodity = JSON.parse($("#temp_id").val());
            $("#commodity_id").val(JSON.stringify(nodes));
            if (newnodes.length == 0) {
                sweetAlert("标签字段没有变化");
            } else {
                var temp_count=$("#temp_count").val();
                if(temp_count>0){
                    //有变动项目，询问是否添加到报价人
                    var r = confirm("有变动类目，是否同步到供应商");
                    if (r == true) {
                        //ajax添加报价人
                        var toUrl = '/admin/supplier/getOffererAjax';
                        var newnodesdata = JSON.stringify(newnodes);
                        var toData = {'id': "<?php echo $id;?>", "newnodes": newnodesdata};
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: toUrl,
                            type: 'post',
                            async: false,
                            data: toData,
                            dataType: 'html',
                            success: function (res) {
                                $("#relation").html(res);
                            },
                            error: function () {
                            }
                        })
                        Button1();
                    }
                }
                else {
                    alert("没有获取关联报价人信息...");
                }

            }
        }
        $(document).ready(function(){
            var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
            var nodes = treeObj.getCheckedNodes(true);
            $("#commodity_id").val( JSON.stringify(nodes));
            $("#temp_id").val(JSON.stringify(nodes));
        });

        function Button1() {
            document.getElementById("MyDiv").style.display = 'block';
            document.getElementById("fade").style.display = 'block';
            var bgdiv = document.getElementById("fade");
            bgdiv.style.width = document.body.scrollWidth;

        }
        //关闭弹出层
        function shut() {
            document.getElementById("MyDiv").style.display = 'none';
            document.getElementById("fade").style.display = 'none';
        };
        function getNewCommodity() {
            var str='';
            $(":checkbox[name='newCommodity']:checked").each(function(){
                str =str+$(this).val()+",";
            });
            var jsontoList=$("#jsontoList").val();
            var toUrl = '/admin/supplier/offererCommodity';
            var toData = {'id':"<?php echo $id;?>","jsontoList":jsontoList,ids:str};
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url:toUrl,
                type:'post',
                async:false,
                data:toData,
                dataType: 'json',
                success:function(res){
                    if(res.status==1){
                        alert(res['message'])
                    }
                    shut();
                    location.reload(true);
                },
                error:function(){
                }
            })

        }
    </SCRIPT>
<div class="content_wrap">
    <div class="zTreeDemoBackground left">
        <ul id="treeDemo" class="ztree"></ul>
    </div>
</div>
<div style="margin-top: 10px;">
<input type="hidden" id="commodity_id" name="commodity_temp_id">
    <input type="hidden" id="temp_id" name="temp_id">
    <a  onclick="GetCheckedAll()" href="javascript:;">设置报价人关联商品</a>
    <div id="fade" class="black_overlay"></div>
    <div id="MyDiv" class="white_content">
        <div id="relation">
        </div>
        <input type="hidden" id="supplier_id" name="supplier_id" value="{{$id}}">
        <input type="hidden" id="temp_count" name="temp_count" value="{{$temp_count}}">
        <input type="hidden" id="newCommodityValue" name="newCommodityValue">
        <input type="button" onclick="getNewCommodity()" value="确认">
        <input type="button" onclick="shut()" value="取消">
    </div></div>
<style>
    .ztree li {
        padding: 0;
        margin: 0;
        list-style: none;
        line-height: 14px;
        text-align: left;
        white-space: nowrap;
        outline: 0;
    }
    ul.ztree {
        margin-top: 10px;
        border: 1px solid #617775;
        background: #f0f6e4;
        width: 220px;
        height: 360px;
        overflow-y: scroll;
        overflow-x: auto;
    }
    .black_overlay {
        display: none;
        position: absolute;
        top: 0%;
        left: 0%;
        width: 100%;
        height: 100%;
        background-color: black;
        z-index: 1001;
        -moz-opacity: 0.8;
        opacity: .80;
        filter: alpha(opacity=80);
    }

    .white_content {
        display: none;
        position: absolute;
        top: 10%;
        left: 10%;
        width: 80%;
        height: 80%;
        border: 16px solid lightblue;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }

    .white_content_small {
        display: none;
        position: absolute;
        top: 20%;
        left: 30%;
        width: 40%;
        height: 50%;
        border: 16px solid lightblue;
        background-color: white;
        z-index: 1002;
        overflow: auto;
    }
</style>
