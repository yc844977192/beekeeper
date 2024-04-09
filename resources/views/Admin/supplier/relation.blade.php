@if($insert_data)
    新增：<?php echo htmlspecialchars_decode($insert_data);?>
@endif
<br />
@if($delete_data)
    删除：<?php echo htmlspecialchars_decode($delete_data);?>
@endif
<br />
<div>请选择要同步的供应商：</div>
@foreach($offerer_list as $k=>$v)
<input type="checkbox" name="newCommodity" id="newCommodity" value="{{$v['id']}}">{{$v['offerer_name']}}
@endforeach
<input type="hidden" name="jsontoList" id="jsontoList" value="{{$jsontoList}}">