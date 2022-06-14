<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link href="/css/style.css" rel="stylesheet" />
    <title>@yield('title', 'Index')</title>
</head>

<body>
    <div class="container-fluid" style="padding: 1%">
        <form action="/" method="GET" class="form-inline" style="margin-bottom: 1%">
            <div class="input-group">
                <input type="text" class="form-control" value="{{ $name ?? '' }}" name="name" placeholder="Scientific Name" />
                <input type="text" class="form-control" value="{{ $jp ?? '' }}" name="jp" placeholder="Japanese" />
                <input type="text" class="form-control" value="{{ $en ?? ''  }}" name="en" placeholder="English" />
                <input type="text" class="form-control" value="{{ $rank ?? '' }}" name="rank" placeholder="Taxon Rank" />
                <button class="btn btn-success" type="submit">find</button>
            </div>
        </form>
        @yield('content')
    </div>
    <script src="/js/app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"> </script>
</body>

</html>