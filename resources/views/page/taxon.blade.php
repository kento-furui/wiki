<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous" />
    <link rel="stylesheet" href="/css/page.css">
    <title>{{ $taxon->canonicalName }}</title>
</head>

<body>
    <div class="container" style="padding: 1%">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="/">
                    <div class="row">
                        <div class="col-10">
                            <input class="form-control" type="text" name="search"
                                value="{{ isset($request) ? $request->search : null }}">
                        </div>
                        <div class="col-2">
                            <input type="submit" value="Search" class="btn btn-success" style="width: 100%">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="tree">
            <div class="col-12" style="color:whitesmoke">
                @foreach (array_reverse($tree) as $t)
                    <a style="color: antiquewhite"
                        href="/taxon/{{ $t->taxonID }}">{{ $t->eol && !empty($t->eol->jp) ? $t->eol->jp : $t->canonicalName }}</a>
                    >
                @endforeach
            </div>
        </div>
        <table class="table">
            <tr>
                <td>
                    @if ($taxon->number && !empty($taxon->number->json))
                        @foreach (json_decode($taxon->number->json) as $key => $val)
                            <div class="ranks">{{ number_format($val) }} {{ $key }}</div>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    @if ($taxon->number && !empty($taxon->number->status))
                        @foreach (json_decode($taxon->number->status) as $key => $val)
                            @if (!empty($val))
                                <span class="{{ $key }}">{{ $key }} {{ $val }}</span>
                            @endif
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    @if ($taxon->number && !empty($taxon->number->node))
                        @foreach (json_decode($taxon->number->node) as $key => $val)
                            @if (!empty($val))
                                <div class="ranks">{{ $key }} {{ number_format($val) }}</div>
                            @endif
                        @endforeach
                    @endif
                </td>
            </tr>
        </table>
        <div class="row">
            <div class="col-6" style="overflow-x: hidden">
                <table class="table" style="color: antiquewhite">
                    <tr>
                        <td>{{ $taxon->taxonRank }}</td>
                    </tr>
                    <tr>
                        <td>
                            {{ $taxon->scientificName }}
                            @if ($taxon->iucn)
                                <div class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $taxon->taxonID }}</td>
                    </tr>
                    <tr>
                        <td>{{ $taxon->EOLid }}</td>
                    </tr>
                    <tr>
                        <td>{{ $taxon->image ? $taxon->image->eolMediaURL : null }}</td>
                    </tr>
                    <tr>
                        <td>{{ $taxon->image ? $taxon->image->dataObjectVersionID : null }}</td>
                    </tr>
                    <tr>
                        <td><input type="text" class="jp" size="80" id="{{ $taxon->EOLid }}"
                                value="{{ $taxon->eol ? $taxon->eol->jp : null }}"></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="en" size="80" id="{{ $taxon->EOLid }}"
                                value="{{ $taxon->eol ? $taxon->eol->en : null }}"></td>
                    </tr>
                    <tr>
                        <td>
                            <select name="iucn" id="{{ $taxon->taxonID }}" class="form-control iucn" style="width: 100px">
                                <option>--</option>
                                <option value="EN" @if($taxon->iucn && $taxon->iucn->status == "EN") selected @endif>EN</option>
                                <option value="DD" @if($taxon->iucn && $taxon->iucn->status == "DD") selected @endif>DD</option>
                                <option value="LC" @if($taxon->iucn && $taxon->iucn->status == "LC") selected @endif>LC</option>
                                <option value="VU" @if($taxon->iucn && $taxon->iucn->status == "VU") selected @endif>VU</option>
                                <option value="NT" @if($taxon->iucn && $taxon->iucn->status == "NT") selected @endif>NT</option>
                                <option value="CR" @if($taxon->iucn && $taxon->iucn->status == "CR") selected @endif>CR</option>
                                <option value="CD" @if($taxon->iucn && $taxon->iucn->status == "CD") selected @endif>CD</option>
                                <option value="EX" @if($taxon->iucn && $taxon->iucn->status == "EX") selected @endif>EX</option>
                                <option value="EW" @if($taxon->iucn && $taxon->iucn->status == "EW") selected @endif>EW</option>                                    
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <a href="/taxon/{{ $taxon->parentNameUsageID }}" class="btn btn-success">Parent</a>
                            <a href="/rand/" class="btn btn-success">Rand</a>
                            <a href="/extinct/{{ $taxon->taxonID }}" class="btn btn-danger"
                                onclick="return confirm('Extinct?')">Extinct</a>
                            <a href="/represent/{{ $taxon->taxonID }}" class="btn btn-success">Represent</a>
                            <a href="javascript:void(0)" class="btn btn-success" onclick="next()">Next</a>
                        </td>
                        </td>
                </table>
            </div>
            <div class="col-6">
                @if ($taxon->image)
                    <img src="{{ $taxon->image->eolMediaURL }}" width="100%" id="preferred">
                    <p>{!! $taxon->image->title !!}</p>
                @else
                    <img src="/img/noimage.png" width="100%">
                @endif
            </div>
        </div>
        <div class="row" style="height: 500px">
            <div class="col-12" id="images"></div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table" style="color:antiquewhite" id="children">
                    @foreach ($taxon->children as $child)
                        <tr>
                            <td style="width: 100px">
                                <a href="/taxon/{{ $child->taxonID }}">
                                    @if ($child->image)
                                        <img src="{{ $child->image->eolThumbnailURL }}">
                                    @else
                                        <img src="/img/noimage.png" id="{{ $child->EOLid }}" class="noimage"
                                            width="98px">
                                    @endif
                                </a>
                            </td>
                            <td>
                                {{ $child->canonicalName }}<br>
                                @if ($child->number && !empty($child->number->json))
                                    @foreach (json_decode($child->number->json) as $key => $val)
                                        @if ($key == 'species')
                                            <div class="ranks">{{ $key }} {{ number_format($val) }}</div>
                                        @endif
                                    @endforeach
                                @endif
                                @if ($child->number && !empty($child->number->node))
                                    @foreach (json_decode($child->number->node) as $key => $val)
                                        @if (!empty($val))
                                            <div class="ranks">{{ strtoupper($key) }} {{ number_format($val) }}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if ($child->eol && !empty($child->eol->jp))
                                    {{ $child->eol->jp }}
                                @else
                                    <input type="hidden" class="nojp" id="{{ $child->EOLid }}">
                                @endif
                            </td>
                            <td>
                                @if ($child->eol && !empty($child->eol->en))
                                    {{ $child->eol->en }}
                                @else
                                    <input type="hidden" class="noen" id="{{ $child->EOLid }}">
                                @endif
                            </td>
                            <td>{{ $child->taxonRank }}</td>
                            <td>
                                @if ($child->taxonRank == 'species' && !$child->iucn)
                                    <input type="hidden" class="noiucn" value="{{ $child->canonicalName }}"
                                        id="{{ $child->taxonID }}">
                                @elseif ($child->iucn)
                                    {{ $child->iucn->status }}
                                @endif
                            </td>
                        </tr>
                
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="wikipedia_ja"></div>
            <hr>
            <div class="col-12" id="wikipedia_en"></div>
            <hr>
            <div class="col-12" id="wikispecies"></div>
        </div>
    </div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <input type="hidden" id="taxonID" value="{{ $taxon->taxonID }}" />
    <input type="hidden" id="en" value="{{ $taxon->canonicalName }}" />
    <input type="hidden" id="ja" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
    <script src="/js/taxon.js"></script>
    <script>    
        document.querySelector('.jp').addEventListener('change', function() {
            const body = {
                jp: this.value
            };
            update(this.id, body);
        });
        
        document.querySelector('.en').addEventListener('change', function() {
            const body = {
                en: this.value
            };
            update(this.id, body);
        });

        document.querySelector('.iucn').addEventListener('change', function() {
            iucn(this.id, this.value);
        })
    
        let page = 1;
        fetchImages(document.querySelector('#EOLid'));
        
        document.querySelectorAll(".noimage").forEach(e => fetchImg(e));
        document.querySelectorAll(".noiucn").forEach(e => fetchIucn(e));
        document.querySelectorAll(".noen").forEach(e => fetchCname(e, "en"));
        document.querySelectorAll(".nojp").forEach(e => fetchCname(e, "jp"));

        fetchWiki(document.querySelector('#ja'));
        fetchWiki(document.querySelector('#en'));
        fetchSpec(document.querySelector('#en'));
    </script>
</body>

</html>
