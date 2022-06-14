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
                    <a style="color: antiquewhite" href="/page/{{ $t->taxonID }}">{{ $t->canonicalName }}</a> >
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
                <a class="btn btn-primary" href="/page/{{ $taxon->taxonID }}">Page</a>
                <a class="btn btn-info" href="/tree/{{ $taxon->taxonID }}">Tree</a>
                <a class="btn btn-info" href="/media/{{ $taxon->taxonID }}">Media</a>
                <a class="btn btn-info" href="/article/{{ $taxon->taxonID }}">Article</a>
            </div>
        </div>
        <div class="row">
            <div class="col-6" id="table" style="overflow: hidden">
                <table class="table" style="color: antiquewhite;">
                    <tr>
                        <td>
                            {{ $taxon->taxonRank }}
                            <h5>{{ $taxon->canonicalName }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @if ($taxon->iucn)
                                <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
                            @else
                                @foreach ($status as $key => $val)
                                    <div class="{{ $key }}">{{ $key }} {{ $val }}</div>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                    <tr style="color: black">
                        <td>{{ $taxon->number ? $taxon->number->nodes() : null }}</td>
                    </tr>
                    <tr>
                        <td>{{ $taxon->number ? $taxon->number->names() : null }}</td>
                    </tr>
                </table>
                <table class="table" style="color: antiquewhite;">
                    <tr>
                        <th style="width:1%">Source</th>
                        <td>{{ $taxon->source }}</td>
                    </tr>
                    <tr>
                        <th style="width:1%">En</th>
                        <td>{{ $taxon->eol ? $taxon->eol->en : null }}</td>
                    </tr>
                    <tr>
                        <th style="width:1%">Jp</th>
                        <td>{{ $taxon->eol ? $taxon->eol->jp : null }}</td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td>{{ $taxon->furtherInformationURL }} </td>
                    </tr>
                    <tr>
                        <th>Usage</th>
                        <td>{{ $taxon->acceptedNameUsageID }} </td>
                    </tr>
                    <tr>
                        <th>Parent</th>
                        <td><a href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $taxon->taxonomicStatus }} </td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <td>{{ $taxon->taxonRemarks }} </td>
                    </tr>
                    <tr>
                        <th>Dataset</th>
                        <td>{{ $taxon->datasetID }} </td>
                    </tr>
                    <tr>
                        <th>EOL</th>
                        <td>{{ $taxon->EOLid }} </td>
                    </tr>
                    <tr>
                        <th>Annotation</th>
                        <td>{{ $taxon->EOLidAnnotations }} </td>
                    </tr>
                    <tr>
                        <th>Landmark</th>
                        <td>{{ $taxon->Landmark }} </td>
                    </tr>
                </table>
            </div>
            <div class="col-6" id="image">
                <img src="{{ $taxon->image ? $taxon->image->eolMediaURL : '/img/noimage.png' }}" width="100%"
                    id="preferred">
                <h4>{{ $taxon->image ? $taxon->image->title : null }}</h4>
                <h5>{!! $taxon->image ? $taxon->image->description : null !!}</h5>
            </div>
        </div>
        <h2>Images <small style="font-size: 80%">{{ count($taxon->images) }}</small></h2>
        <div class="row" id="thumbnail">
            @foreach ($taxon->images as $image)
                <div class="col-1">
                    <img src="{{ $image->eolThumbnailURL }}" title="{{ $image->description }}" width="100%"
                        class="thumb" id="{{ $image->identifier }}">
                </div>
            @endforeach
        </div>
        <h2>Children <small style="font-size: 80%">{{ count($taxon->children) }}</small></h2>
        <div class="row" id="children">
            @foreach ($taxon->children as $child)
                <div class="col-3 child">
                    @if ($child->iucn)
                        <span class="{{ $child->iucn->status }}">{{ $child->iucn->status }}</span>
                    @endif
                    <a href="/page/{{ $child->taxonID }}">
                        <img src="{{ $child->image ? $child->image->eolMediaURL : '/img/noimage.png' }}" width="100%"
                            height="200px">
                        <div class="overlay">
                            <h5>{{ $child->canonicalName }}</h5>
                            <h5>{{ $child->eol ? $child->eol->jp : null }}</h5>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <script src="/js/page.js"></script>
</body>

</html>
