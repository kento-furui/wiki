<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>{{ $taxon->canonicalName }}</title>
</head>

<body>
    <div class="container">
        <h1>{{ $taxon->scientificName }}</h1>
        @foreach(array_reverse($tree) as $k => $t)
            @if ($t->eol && !empty($t->eol->img))
            <img src="{{ $t->eol->img }}" width="20px" style="margin-left: {{ $k * 10  }}px">
            @endif
            <a href="/taxon/{{ $t->taxonID }}">{{ $t->canonicalName }}</a><br>
        @endforeach
        @include('taxon._table', ['taxa' => $taxon->children])
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous">
    </script>
</body>

</html>