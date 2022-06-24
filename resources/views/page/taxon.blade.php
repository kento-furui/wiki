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
            <div class="col-12">
                @foreach (array_reverse($tree) as $t)
                    <a style="color: antiquewhite" href="/taxon/{{ $t->taxonID }}">{{ $t->canonicalName }}</a> >
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h5>{{ $taxon->taxonRank }}</h5>
                <h4 style="color: whitesmoke; margin-bottom: 2%">
                    {{ $taxon->scientificName }}<br>
                    {{ $taxon->eol ? $taxon->eol->jp : null }}<br>
                    {{ $taxon->eol ? $taxon->eol->en : null }}
                    @if ($taxon->iucn)
                        <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
                    @endif
                </h4>
                <div style="margin-bottom: 1%">
                    @if ($taxon->number && !empty($taxon->number->status))
                        @foreach (json_decode($taxon->number->status) as $key => $val)
                            <span class="{{ $key }}">{{ $key }} {{ $val }}</span>
                        @endforeach
                    @endif
                </div>
                <div>
                    @if ($taxon->number && !empty($taxon->number->json))
                        @foreach (json_decode($taxon->number->json) as $key => $val)
                            <div class="ranks">{{ number_format($val) }} {{ $key }}</div>
                        @endforeach
                    @endif
                </div>
                <div>
                    <a href="/taxon/{{ $taxon->parentNameUsageID }}" class="btn btn-danger">Parent</a>
                    <a href="/extinct/{{ $taxon->taxonID }}" class="btn btn-danger"
                        onclick="return confirm('Extinct?')">Extinct</a>
                    <a href="/represent/{{ $taxon->taxonID }}" class="btn btn-danger">Represent</a>
                </div>
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
        <div class="row">
            <div class="col-12">
                <h3>Children</h3>
                <table class="table" style="color:antiquewhite">
                    @foreach ($taxon->children as $child)
                        <tr>
                            <td>
                                <a href="/taxon/{{ $child->taxonID }}">
                                    <img src="{{ $child->image ? $child->image->eolThumbnailURL : '/img/noimage.png' }}"
                                        style="max-width: 98px">
                                </a>
                            </td>
                            <td>{{ $child->taxonID }}</td>
                            <td>{{ $child->EOLid }}</td>
                            <td>
                                {{ $child->scientificName }}<br>
                                @if ($child->number && !empty($child->number->status))
                                    @foreach (json_decode($child->number->status) as $key => $val)
                                        <span style="border: 1px white solid; padding:5px">{{ $key }} {{ $val }}</span>
                                    @endforeach
                                    <br>
                                @endif
                                @if ($child->number && !empty($child->number->json))
                                    @foreach (json_decode($child->number->json) as $key => $val)
                                        <span style="border: 1px white solid; padding:5px">{{ $key }} {{ $val }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $child->eol ? $child->eol->jp : null }}</td>
                            <td>{{ $child->eol ? $child->eol->en : null }}</td>
                            <td>{{ $child->taxonRank }}</td>
                            <td>{{ $child->iucn ? $child->iucn->status : null }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="images">
                <a href="javascript:void(0)" class='btn btn-primary' onclick="next()">Next</a>
            </div>
            <div class="col-12" id="wikipedia_ja"></div>
            <div class="col-12" id="wikipedia_en"></div>
        </div>
    </div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <input type="hidden" id="taxonID" value="{{ $taxon->taxonID }}" />
    <input type="hidden" id="en" value="{{ $taxon->canonicalName }}" />
    <input type="hidden" id="ja" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
    <script>
        let page = 1;
        fetchWiki(document.querySelector('#ja'));
        fetchWiki(document.querySelector('#en'));
        fetchImg(document.querySelector('#EOLid'));

        function next() {
            page++;
            document.querySelectorAll('.thumbnail').forEach(e => e.remove());
            fetchImg(document.querySelector('#EOLid'));
        }

        async function fetchImg(element) {
            if (element == null) return false;
            if (element.value == undefined) return false;
            const id = element.value;
            const url =
                `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=75&images_page=${page}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.taxonConcept.dataObjects == undefined) return false;
                for (const dataObject of data.taxonConcept.dataObjects) {
                    //console.log(dataObject);
                    let img = document.createElement("img");
                    img.style.margin = '2px';
                    img.className = 'thumbnail';
                    img.src = dataObject.eolThumbnailURL;
                    img.id = dataObject.dataObjectVersionID;
                    img.addEventListener('click', async function() {
                        const url =
                            `https://eol.org/api/data_objects/1.0/${dataObject.dataObjectVersionID}.json`;
                        const response = await fetch(url);
                        const data = await response.json();
                        //console.log(data.taxon.dataObjects[0]);
                        await fetch(`/api/image/update/${id}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify(data.taxon.dataObjects[0]),
                        });
                        location.reload();
                    });
                    document.querySelector("#images").appendChild(img);
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function fetchWiki(row) {
            if (row == undefined) return false;

            const lang = row.id;
            const value = row.value;
            const url =
                `https://${lang}.wikipedia.org/w/api.php?format=json&action=parse&prop=text&page=${value}&formatversion=2&redirects&origin=*`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.parse == undefined) return false;
                let text = data.parse.text;
                text = text.replaceAll(
                    'href="/wiki/',
                    `target="_blank" href="//${lang}.wikipedia.org/wiki/`
                );
                text = text.replaceAll(
                    'href="/w/',
                    `target="_blank" style="color:red" href="//${lang}.wikipedia.org/w/`
                );
                document.querySelector("#wikipedia_" + lang).innerHTML = text;
                //console.log(text);
            } catch (err) {
                console.error(err);
            }
        };
    </script>
</body>

</html>
