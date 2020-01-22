<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="/js/axios.min.js"></script>
    <title>{$title}</title>
    <style>
        .img {
            height: 150px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        #page {
            height: auto;
        }
        #page ul li{
            float: left;
            list-style-type: none;
        }
    </style>
</head>
<body>
<div class="container">
    <ul class="list-group mt-2"></ul>
    <div id="page" >
        <ul class="page mt-2"></ul>
    </div>
</div>
<script>
    request('/'+getType());
    /**
     *渲染页面列表
     * @param param
     */
    function request(param) {
        axios.get("/v1/api"+param)
            .then(res => {
                let data = res.data
                console.log(data)
                if (data.msg == 'ok') {
                    let page = data.data.page;
                    setPage(page)
                    let list = data.data.list || [];
                    let html = '';
                    list.forEach(function ($v, $k) {
                        let url = $v['play_url'] || []
                        let html_url = ''
                        url.forEach(function ($v1, $i) {
                            html_url += '<div><a href="/movie/player#' + $v1 + '">' + $v.name + '(资源' + ($i + 1) + ')</a></div>\n'
                        })
                        html += '<li class="list-group-item">\n' +
                            '            <div class="img">\n' +
                            '                <img src="' + $v.png + '" alt="" class="rounded">\n' +
                            '            </div>\n' + html_url +
                            '        </li>'
                    })
                    $(".list-group").html(html)
                } else {
                    alert('当前资源暂无')
                    history.back(-1)
                }
            })
    }

    /**
     * 分页
     * @param page
     */
    function setPage(page) {
        if (page < 0 || page == '') return;
        let html ='';
        for (let i=0;i < page;i++) {
            html += '<li class="page-item"><a class="page-link" href="javascript:;">'+i+'</a></li>'
        }
        $('.page').html(html)
        $(".page-item a").on('click',function () {
            request('/'+getType()+'/'+this.text)
        })
    }

    /**
     * 获取类型
     */
    function getType() {
        let url = document.location.toString();
        let arrType = url.split("#");
        let type = arrType[1];
        if (type == '') {
            type = 9
        }
        return type
    }
</script>
</body>
</html>