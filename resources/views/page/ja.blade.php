@extends('page')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'ja'])
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
    <div class="row">
        <div class="col-12" id="wikipedia"></div>
    </div>
    @include('page.children')
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    <input type="hidden" id="ja" value="{{ $taxon->eol ? $taxon->eol->jp : $taxon->canonicalName }}" />
    <script>
        const fetchWikipedia = async (row) => {
            if (row.value == "") return false;
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
                document.querySelector("#wikipedia").innerHTML = text;
                //console.log(text);
            } catch (err) {
                console.error(err);
            }
        };

        fetchWikipedia(document.querySelector('#ja'));
    </script>
@endsection
