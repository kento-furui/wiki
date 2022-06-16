@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'media'])
    <div class="row" style="margin: 1% 0">
        <div class="col-12">
            @for ($i = 1; $i <= 28; $i++)
                <a href="?page={{ $i }}" class="btn {{ app('request')->input('page') == $i ? "btn-primary" : "btn-success" }} id="{{ $i }}">{{ $i }}</a>
            @endfor
        </div>
    </div>
    <div class="row" id="media"></div>
    @include('page.children', ['app' => 'media'])
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <script>
        const params = new URLSearchParams(window.location.search);
        fetchImg( document.querySelector('#EOLid'), params.get('page') );

        async function fetchImg(element, page) {
            if (page == null) page = 1;
            if (element == null) return false;
            if (element.value == undefined) return false;
            const id = element.value;
            const url =
                `https://eol.org/api/pages/1.0/${id}.json?details=true&images_per_page=16&images_page=${page}`;
            try {
                const response = await fetch(url);
                const data = await response.json();
                if (data.taxonConcept.dataObjects == undefined) return false;
                const container = document.querySelector("#images");
                for (const dataObject of data.taxonConcept.dataObjects) {
                    console.log(dataObject);
                    let p = document.createElement("p");
                    let div = document.createElement("div");
                    let img = document.createElement("img");

                    div.className = "col-3";
                    img.style.width = "100%";
                    img.src = dataObject.eolMediaURL;
                    p.innerHTML = dataObject.description;

                    div.appendChild(img);
                    div.appendChild(p);

                    document.querySelector("#media").appendChild(div);
                }
            } catch (err) {
                console.error(err);
            }
        };
    </script>
@endsection
