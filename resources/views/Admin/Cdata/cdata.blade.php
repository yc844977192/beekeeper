<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>页面开发中</title>
    <style>


        h1 {
            font-size: 48px;
            margin: 100px auto 0;
            text-align: center;
        }
        p {
            font-size: 24px;
            margin: 20px auto 0;
            text-align: center;
        }

        .spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            position: relative;
        }

        .spinner .rect {
            width: 6px;
            height: 100%;
            position: absolute;
            left: 47%;
            animation: spinner-rect 1.2s infinite ease-in-out;
        }

        .spinner .rect1 {
            left: -15px;
            animation-delay: -1.1s;
        }

        .spinner .rect2 {
            left: -5px;
            animation-delay: -1.0s;
        }

        .spinner .rect3 {
            left: 5px;
            animation-delay: -0.9s;
        }

        .spinner .rect4 {
            left: 15px;
            animation-delay: -0.8s;
        }

        @keyframes spinner-rect {
            0% {
                transform: scale(1, 0.5);
            }
            20% {
                transform: scale(1, 1);
            }
            40% {
                transform: scale(1, 0.5);
            }
            60% {
                transform: scale(1, 1);
            }
            80% {
                transform: scale(1, 0.5);
            }
            100% {
                transform: scale(1, 0.5);
            }
        }
    </style>
</head>
<body>
<div class="text-show">
    <h1>页面开发中</h1>
    <p>敬请期待...</p>
</div>

<div class="spinner">
    <div class="rect rect1"></div>
    <div class="rect rect2"></div>
    <div class="rect rect3"></div>
    <div class="rect rect4"></div>
</div>
</body>
</html>
