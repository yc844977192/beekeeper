<style>
        /* 文件上传容器样式 */
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ddd;
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        /* 上传区域文字样式 */
        .file-upload-text {
            font-size: 18px;
            color: #888;
            margin-bottom: 10px;
        }

        /* 上传图标样式 */
        .file-upload-icon {
            font-size: 50px;
            color: #aaa;
        }

        /* 上传按钮样式 */
        .file-upload-btn {
            background-color: #428bca;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        /* 上传按钮悬停样式 */
        .file-upload-btn:hover {
            background-color: #3071a9;
        }

        /* 隐藏默认的文件选择按钮 */
        input[type="file"] {
            display: none;
        }

        /* 上传按钮样式 */
        .file-upload-btn-sub {
            background-color: #428bca;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 80%;
            margin-top: 10px;
            margin-left: 10%;
        }
        /* 上传按钮悬停样式 */
        .file-upload-btn-sub:hover {
            background-color: #3071a9;
        }

        /* ... 前面的样式代码 ... */

        /* 图片预览容器样式 */
        .image-preview {
            margin-top: 20px;
            width: 200px;
            height: 200px;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            display: none;
        }

        /* 图片预览样式 */
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
        }
</style>
<body>
<form action="call_deal/upload_excel"  method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}
<div class="file-upload">
    <!-- 图片预览容器 -->
    <div class="image-preview"></div>
    <div class="file-upload-text">点击或拖放文件到此处</div>
    <i class="file-upload-icon">+</i>
    <input class="file-upload-btn" type="button" value="选择文件" onclick="document.getElementById('fileInput').click()" />
    <input name="excel_file" id="fileInput" type="file" />
</div>
    <button class="file-upload-btn-sub">确认，提交</button>
</form>
<script>
    // 获取上传的文件输入框
    var fileInput = document.getElementById('fileInput');
    // 获取图片预览容器
    var previewContainer = document.querySelector('.image-preview');

    // 监听文件选择事件
    fileInput.addEventListener('change', function (event) {
        $(".image-preview").show();
        $(".file-upload-text").hide();
        $(".file-upload-icon").hide();
        $(".file-upload-icon").hide();
        // 获取选中的文件
        var file = event.target.files[0];

        // 创建 FileReader 对象
        var reader = new FileReader();

        // 监听 FileReader 加载完成事件
        reader.addEventListener('load', function (event) {
            // 创建图片元素
            var img = document.createElement('img');
            img.src = event.target.result;

            // 清空图片预览容器并添加图片元素
            previewContainer.innerHTML = '';
            previewContainer.appendChild(img);
        });

        // 读取文件内容
        reader.readAsDataURL(file);
    });
</script>