<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/page.css">
    <link rel="stylesheet" href="/v6.14.1-dist/ol.css">
    <link rel="stylesheet" href="/lightbox2/dist/css/lightbox.min.css">
    <script src="/v6.14.1-dist/ol.js"></script>
</head>

<body>
    <div class="container" style="padding: 1%">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="/">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" type="text" name="search" value="{{ isset($request) ? $request->search : null }}">
                        </div>
                        <div class="col-2">
                            <input type="submit" value="Search" class="btn btn-success" style="width: 100%">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @yield('content')
    </div>
    <script src="/lightbox2/dist/js/lightbox-plus-jquery.min.js"></script>
</body>

</html>
