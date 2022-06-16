@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'media'])
    <div class="row" style="margin: 1% 0">
        <div class="col-12">
            <a href="#" onclick="prev()" class="btn btn-success">Prev</a>
            <a href="#" onclick="next()" class="btn btn-success">Next</a>
        </div>
    </div>
    <div class="row" id="media"></div>
    @include('page.children', ['app' => 'media'])
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <script>
        let page = 1;
        fetchImg( document.querySelector('#EOLid') );

        function prev() {
            page--;
            document.querySelector("#media").innerHTML = "";
            fetchImg( document.querySelector('#EOLid') );
        }

        function next() {
            page++;
            document.querySelector("#media").innerHTML = "";
            fetchImg( document.querySelector('#EOLid') );
        }

        async function fetchImg(element) {
            if (page < 1) page = 1;
            if (element == null) return false;
            if (element.value == undefined) return false;
            const id = element.value;
            const url =
                `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=16&images_page=${page}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.taxonConcept.dataObjects == undefined) return false;

                for (const dataObject of data.taxonConcept.dataObjects) {
                    //console.log(dataObject);
                    let a = document.createElement("a");
                    let div = document.createElement("div");
                    let img = document.createElement("img");

                    div.className = "col-3";
                    img.style.width = "100%";
                    a.dataset.lightbox = "lightbox";
                    a.href = dataObject.eolMediaURL;
                    img.src = dataObject.eolMediaURL;
                    a.dataset.title = dataObject.description;

                    a.appendChild(img);
                    div.appendChild(a);

                    document.querySelector("#media").appendChild(div);
                }
            } catch (err) {
                console.error(err);
            }
        };
    </script>
@endsection
