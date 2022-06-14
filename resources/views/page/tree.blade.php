<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>{{ $taxon->canonicalName }}</title>
    <link rel="stylesheet" href="/css/page.css">
</head>

<body>
    <div class="container" style="padding: 1%">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="/page">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" type="text" name="search">
                        </div>
                        <div class="col-2">
                            <input type="submit" value="Search" class="btn btn-success" style="width: 100%">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @foreach (array_reverse($tree) as $t)
                    <a style="color: antiquewhite" href="/tree/{{ $t->taxonID }}">{{ $t->canonicalName }}</a> >
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-1" id="thumbnail">
                <img src="{{ $taxon->image ? $taxon->image->eolThumbnailURL : '/img/noimage.png' }}" width="100%">
            </div>
            <div class="col-5" id="names">
                <h4 style="color: whitesmoke">{{ $taxon->scientificName }}</h4>
                <h5 style="color: lightgrey">{{ $taxon->eol ? $taxon->eol->jp : null }}</h5>
            </div>
            <div class="col-6" id="nav">
                <a class="btn btn-info" href="/page/{{ $taxon->taxonID }}">Page</a>
                <a class="btn btn-primary" href="/tree/{{ $taxon->taxonID }}">Tree</a>
                <a class="btn btn-info" href="/media/{{ $taxon->taxonID }}">Media</a>
                <a class="btn btn-info" href="/article/{{ $taxon->taxonID }}">Article</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="table" style="overflow: hidden">
                <table class="table" style="color: antiquewhite;">
                    <tr>
                        <td>
                            {{ $taxon->taxonRank }}
                            <h5>{{ $taxon->canonicalName }}</h5>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @foreach (array_reverse($tree) as $key => $t)
        <div class="row">
            <div class="col-1">
                <img src="{{ $t->image ? $t->image->eolThumbnailURL : '/img/noimage.png' }}" width="100px">
            </div>
            <div class="col-5">
                <a href="/page/{{ $t->taxonID }}">
                    <h5>{{ $t->scientificName }}</h5>
                    <h5>{{ $t->eol ? $t->eol->jp : null }}</h5>
                </a>
            </div>
        </div>
            @endforeach
    </div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <script src="/js/page.js"></script>
</body>

</html>
