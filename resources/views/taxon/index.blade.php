<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>Hello, world!</title>
</head>

<body>
    <div class="container">
        <form action="/" method="GET" class="form-inline" style="margin: 1% 0;">
            <div class="input-group">
                <input type="text" name="search" class="form-control"/>
                <button class="btn btn-success" type="submit">find</button>
            </div>
        </form>
        {{ $taxa->links() }}
        @include('taxon._table', ['taxa' => $taxa]);
        {{ $taxa->links() }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
</body>

</html>