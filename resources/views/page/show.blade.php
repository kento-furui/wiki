@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'page'])
    <div class="row">
        <div class="col-6" id="table" style="overflow: hidden">
            <table class="table" style="color: antiquewhite;">
                <tr>
                    <th style="width:1%">Status</th>
                    <td>
                        @if ($taxon->iucn)
                            <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
                        @else
                            @foreach ($status as $key => $val)
                                <span class="{{ $key }}">{{ $key }} {{ $val }}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width:1%">Ranks</th>
                    <td>
                        @foreach ($ranks as $key => $val)
                            <div class="ranks">{{ $val }} {{ $key }}</div>
                        @endforeach
                    </td>
                </tr>
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
                    <td>{{ $taxon->furtherInformationURL }}</td>
                </tr>
                <tr>
                    <th>Usage</th>
                    <td>{{ $taxon->acceptedNameUsageID }}</td>
                </tr>
                <tr>
                    <th>Parent</th>
                    <td><a href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $taxon->taxonomicStatus }}</td>
                </tr>

                <tr>
                    <th>Dataset</th>
                    <td>{{ $taxon->datasetID }}</td>
                </tr>
                <tr>
                    <th>EOL</th>
                    <td>{{ $taxon->EOLid }}</td>
                </tr>
                <tr>
                    <th>Annotation</th>
                    <td>{{ $taxon->EOLidAnnotations }}</td>
                </tr>
                <tr>
                    <th>Admin</th>
                    <td>
                        <a href="javascript:void()" class="btn btn-danger" onclick="next()">Next</a>
                        <a href="/extinct/{{ $taxon->taxonID }}" class="btn btn-danger" onclick="return confirm('Extinct?')">Extinct</a>
                        <a href="/represent/{{ $taxon->taxonID }}" class="btn btn-danger" onclick="return confirm('Represent?')">Represent</a>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-6" id="image">
            <img src="{{ $taxon->image ? $taxon->image->eolMediaURL : '/img/noimage.png' }}" width="100%" id="preferred">
            <h4>{{ $taxon->image ? $taxon->image->title : null }}</h4>
        </div>
    </div>
    @include('page.children', ['app' => 'page'])
    <div id="images"></div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <script>
        let page = 1;
        const element = document.querySelector('#EOLid'); 
        fetchImg( element );

        function next() {
            page++;
            document.querySelector("#images").innerHTML = '';
            fetchImg( element );
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
    </script>
@endsection
