@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'page'])
    <div class="row">
        <div class="col-6" id="table" style="overflow: hidden">
            <table class="table" style="color: antiquewhite;">
                <tr>
                    <td colspan=2 id="status">Counting...</td>
                </tr>
                <tr>
                    <td colspan=2 id="ranks">Counting...</td>
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
                    <td><a class="btn btn-primary" href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a></td>
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
                        <a href="/extinct/{{ $taxon->taxonID }}" class="btn btn-danger" onclick="return confirm('Extinct?')">Extinct</a>
                        <a href="/represent/{{ $taxon->taxonID }}" class="btn btn-danger">Represent</a>
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
    <a href="javascript:void(0)" class='btn btn-primary' onclick="next()">Next</a>
    <div id="images"></div>
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <input type="hidden" id="taxonID" value="{{ $taxon->taxonID }}" />
    <script>
        ranks( document.querySelector('#taxonID') );
        status( document.querySelector('#taxonID') );

        async function ranks(element) {
            const id = element.value;
            try {
                const response = await fetch(`/api/taxon/ranks/${id}`);
                const json = await response.json();
                //consol.log(json);
                document.querySelector('#ranks').innerHTML = '';
                for (const key in json) {
                    let div = document.createElement('div');
                    div.className = 'ranks';
                    div.style.marginRight = '5px';
                    div.innerHTML = json[key] + ' ' + key;
                    document.querySelector('#ranks').appendChild(div);
                }
            } catch (err) {
                document.querySelector('#ranks').innerHTML = 'Too many to count.';
            }
        }

        async function status(element) {
            const id = element.value;
            try {
                const response = await fetch(`/api/taxon/status/${id}`);
                const json = await response.json();
                //consol.log(json);
                document.querySelector('#status').innerHTML = '';
                for (const key in json) {
                    let span = document.createElement('span');
                    span.className = key;
                    span.style.marginRight = '5px';
                    span.innerHTML = key + ' ' + json[key];
                    document.querySelector('#status').appendChild(span);
                }
            } catch (err) {
                document.querySelector('#status').innerHTML = 'Too many to count.';
            }
        }

        let page = 1;
        function next() {
            page++;
            document.querySelector('#images').innerHTML = '';
            fetchImg( document.querySelector('#EOLid') );
        }

        fetchImg( document.querySelector('#EOLid') );

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
