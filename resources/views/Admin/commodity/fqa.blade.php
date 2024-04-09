@if(!empty($commodity_fqa))
    @foreach($commodity_fqa as $k=>$v)
        <div class="faq-item">
            <button type="button" class="close position-absolute" aria-label="关闭">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="form-group">
                <input type="text" name="question[]" class="form-control" id="question" placeholder="请输入问题" value="{{$v['title']}}">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="answer[]" id="answer" rows="3" placeholder="请输入答案">{{$v['content']}}</textarea>
            </div>
        </div>
        @endforeach
    @else
    <div class="faq-item">
        <button type="button" class="close position-absolute" aria-label="关闭">
            <span aria-hidden="true">&times;</span>
        </button>
<div class="form-group">
    <input type="text" name="question[]" class="form-control" id="question" placeholder="请输入问题">
</div>
<div class="form-group">
    <textarea class="form-control" name="answer[]" id="answer" rows="3" placeholder="请输入答案"></textarea>
</div>
    </div>
@endif
<div class="questions-container"></div>
<button type="button" class="btn btn-primary add-question">新增</button>
@if(!empty($commodity_fqa))
{{--<button type="button" class="btn btn-primary" onclick="sc({{$commodity_fqa[0]["commodity_id"]}})">生成静态页面</button>--}}
@endif
<script>
    function sc(id) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url:"/admin/commodity/setCommodityStaticById",
            type:'post',
            async:false,
            data:{id:id},
            dataType:'json',
            success:function(res){
                console.log(res);
                if(res){
                    toastr.success("更新成功"); // 提示文字
                }
            },
            error:function(){
            }
        })

    }

</script>

