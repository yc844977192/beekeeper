<form method="POST" action="record/uploadFiles">
    {!! csrf_field() !!}
    <div class="form-group">
        <label for="name">文件位置</label>
        <input type="text" class="form-control" id="name" name="name[]" placeholder="Enter your name">
    </div>
    <div id="dynamic-inputs"></div>
    <button type="button" class="btn btn-primary" onclick="addInput()">Add Input</button>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
    function addInput() {
        var wrapper = document.createElement('div');
        wrapper.classList.add('form-group');
        var input = document.createElement('input');
        input.type = 'text';
        input.classList.add('form-control');
        input.name = 'name[]';
        input.placeholder = 'Enter another name';
        wrapper.appendChild(input);
        document.getElementById('dynamic-inputs').appendChild(wrapper);
    }
</script>

