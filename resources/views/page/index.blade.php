<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <title>Life Index</title>
</head>

<body style="background: black">
    <div class="container-fluid" style="padding: 2%">
        <div class="row">
            @foreach ($taxa as $taxon)
                <div class="col-2" style="color: aliceblue; text-align: center;">
                    <img src="{{ $taxon->image ? $taxon->image->eolMediaURL : null }}" height="200px">
                    <h4>{{ $taxon->eol && !empty($taxon->eol->jp) ? $taxon->eol->jp : $taxon->canonicalName}}</h4>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>