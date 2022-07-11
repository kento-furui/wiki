<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/page.css">
    <title>Random</title>
</head>

<body>
    <div class="container" style="padding: 1%">
        <div class="row">
            <div class="col-12">
                <table class="table" style="color:antiquewhite" id="children">
                    @foreach ($taxa as $taxon)
                        <tr>
                            <td style="width: 100px">
                                <a href="/taxon/{{ $taxon->taxonID }}" target="_blank">
                                    @if ($taxon->image)
                                        <img src="{{ $taxon->image->eolThumbnailURL }}">
                                    @else
                                        <img src="/img/noimage.png" id="{{ $taxon->EOLid }}" class="noimage" width="98px">
                                    @endif
                                </a>
                            </td>
                            <td>
                                {{ $taxon->canonicalName }}<br>
                                @if ($taxon->number && !empty($taxon->number->json))
                                    @foreach (json_decode($taxon->number->json) as $key => $val)
                                        @if ($key == 'species')
                                            <div class="ranks">{{ $key }} {{ number_format($val) }}</div>
                                        @endif
                                    @endforeach
                                @endif
                                @if ($taxon->number && !empty($taxon->number->node))
                                    @foreach (json_decode($taxon->number->node) as $key => $val)
                                        <div class="ranks">{{ strtoupper($key) }} {{ number_format($val) }}</div>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($taxon->eol && !empty($taxon->eol->jp))
                                    {{ $taxon->eol->jp }}
                                @else
                                    <input type="hidden" class="nojp" id="{{ $taxon->EOLid }}">
                                @endif
                            </td>
                            <td>
                                @if ($taxon->eol && !empty($taxon->eol->en))
                                    {{ $taxon->eol->en }}
                                @else
                                    <input type="hidden" class="noen" id="{{ $taxon->EOLid }}">
                                @endif
                            </td>
                            <td>{{ $taxon->taxonRank }}</td>
                            <td>
                                @if ($taxon->taxonRank == 'species' && !$taxon->iucn)
                                    <input type="hidden" class="noiucn" value="{{ $taxon->canonicalName }}" id="{{ $taxon->taxonID }}">
                                @elseif ($taxon->iucn)
                                    {{ $taxon->iucn->status }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <script src="/js/taxon.js"></script>
    <script>
<<<<<<< HEAD
        document.querySelectorAll(".noimage").forEach(e => fetchImg(e));
        document.querySelectorAll(".noiucn").forEach(e => fetchIucn(e));
        document.querySelectorAll(".noen").forEach(e => fetchCname(e, "en"));
        document.querySelectorAll(".nojp").forEach(e => fetchCname(e, "jp"));
=======
        saveJpn(document.querySelectorAll('.nojp'));
        saveEng(document.querySelectorAll('.noen'));
        saveImg(document.querySelectorAll('.noimage'));
        saveIucn(document.querySelectorAll('.noiucn'));
>>>>>>> 8ef591c4c99783a32d26693be76a0d5674b06f5e
    </script>
</body>

</html>
