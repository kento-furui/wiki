<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>@yield('title')</title>
    <style>
        .container {
            padding: 1% 0;
        }

        table tr td {
            white-space: nowrap;
            overflow: hidden;
            vertical-align: middle;
        }

        table tr th {
            vertical-align: middle;
        }

        table#taxa tr td img {
            width: 98px;
        }

        .mw-editsection {
            display: none;
        }

        .infobox {
            border: 1px solid #a2a9b1;
            background-color: #f8f9fa;
            color: black;
            margin: 0.5em 0 0.5em 1em;
            padding: 0.2em;
            float: right;
            clear: right;
            text-align: left;
            font-size: 88%;
            line-height: 1.5em;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="/" method="GET" class="form-inline" style="margin-bottom: 1%">
            <div class="input-group">
                <input type="text" name="search" class="form-control" />
                <button class="btn btn-success" type="submit">find</button>
            </div>
        </form>
        @yield('content')
    </div>
    <script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
</body>

</html>