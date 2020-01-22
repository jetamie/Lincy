<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{$title}</title>
    <link rel="stylesheet" href="/css/DPlayer.min.css">
    <link rel="stylesheet" href="/css/video-js.min.css">
    <script src="/js/hls.min.js"></script>
    <script src="/js/DPlayer.min.js"></script>
    <script src="/js/jquery2.1.4.min.js"></script>
    <style>
        #cont {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        #back {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 999;
            background: rgba(255,255,255, 0.8);
            cursor: pointer;
            font-weight: 600;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
<div id="cont">
    <div id="back">back</div>
    <div id="dplayer" style="position: absolute;top:0;left: 0;width: 100%;height: 100%"></div>
</div>
<script>
    var playListId = 0;
    var url = document.location.toString();
    var arrUrl = url.split("#");
    var movieUrl = arrUrl[1];
    if (movieUrl == '') {
        alert('地址为空！')
        history.back(-1)
    }
    $("#playList0").removeClass("btn btn-info");
    $("#playList0").addClass("btn btn-primary");
    $("#back").click(function () {
        console.log('back')
        history.back(-1)
    })
    dp= new DPlayer({
        container: document.getElementById('dplayer'),
        autoplay: true,
        theme: '#FADFA3',
        loop: true,
        lang: 'zh-cn',
        screenshot: true,
        hotkey: true,
        preload: 'auto',
        logo: '',
        volume: 0.7,
        mutex: true,
        video: {
            url: movieUrl,
            type: 'auto'
        },
        subtitle: {
            url: "",
            type: 'webvtt',
            fontSize: '25px',
            bottom: '7%',
            color: '#b7daff',
        },
        contextmenu: [
            {
                text: 'Lincy',
                link: 'https://github.com/jetamie/Lincy'
            },
        ],
        highlight: [
            {
                time: 20,
                text: '仅供学习交流参考'
            },
            {
                time: 120,
                text: '关注公众号：米唐'
            }
        ]
    });
    dp.on('waiting', function() {
        $("#play-title").text('加载缓慢请稍等...');
    });
    dp.on('playing', function() {
        $("#play-title").text('正在播放');
    });
    dp.on('pause', function() {
        $("#play-title").text('播放暂停');
    });
    dp.play();
    dp.subtitle.show()
</script>
</body>
</html>