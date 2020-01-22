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
</head>
<body>
<div class="container">
    <h2>分类列表</h2>
    <div class="list-group"></div>
</div>

<script>
    request()
    function request() {
        axios.get("/list.json")
            .then(res => {
                let data = res.data
                if (data) {
                    let list = data['type']
                    let html = ''
                    list.forEach(function ($v,$k) {
                        html += '<a href="/movie/detail#'+$v.type_id+'" class="list-group-item list-group-item-action">'+$v.type_name+'</a>'
                    })
                    $(".list-group").html(html)
                }
            })
    }
</script>
</body>
</html>