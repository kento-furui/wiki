<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/page.css">
    <title>Life</title>
</head>

<body>
    <div class="container" style="padding: 1%">
        <form method="GET" action="/page">
            <div class="row">
                <div class="col-10">
                    <input class="form-control" type="text" name="search" value="{{ $request ? $request->search : null }}">
                </div>
                <div class="col-2">
                    <input type="submit" value="Search" class="btn btn-success" style="width: 100%">
                </div>
            </div>
        </form>
        <div class="row">
            @foreach ($taxa as $taxon)
            <div class="col-4">
                <a href="/page/{{ $taxon->taxonID }}">
                    {{ $taxon->canonicalName }}
                    @if ($taxon->image)
                    <img src="{{ $taxon->image->eolThumbnailURL }}">
                    @endif
                </a>
            </div>

            @endforeach

        </div>
</body>

</html>