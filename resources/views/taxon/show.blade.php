@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
<a href="/">index</a>
<h1>
    {{ $taxon->scientificName }}
    {{ $taxon->eol ? $taxon->eol->jp : null }}
    {{ $taxon->eol ? $taxon->eol->en : null }}
</h1>
<div class="tab-content" id="nav-tabContent">
    <div id="images" style="overflow-x: scroll; white-space: nowrap">
        <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
    </div>
    <table class="table" style="table-layout: fixed;">
        <tr>
            <th width="90px">画像</th>
            <td>
                @if ($taxon->eol)
                <a href="/taxon/represent/{{ $taxon->taxonID }}">
                    <img src="{{ $taxon->eol->img }}" id="thumb">
                </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>学名</th>
            <td>{{ $taxon->scientificName }}</td>
        </tr>
        <tr>
            <th>ランク</th>
            <td>{{ $taxon->taxonRank }}</td>
        </tr>
        <tr>
            <th>和名</th>
            <td>{{ $taxon->eol ? $taxon->eol->jp : null }}</td>
        </tr>
        <tr>
            <th>英名</th>
            <td>{{ $taxon->eol ? $taxon->eol->en : null }}</td>
        </tr>
        <tr>
            <th>ソース</th>
            <td>{{ $taxon->source }}</td>
        </tr>
        <tr>
            <th>EOL</th>
            <td><a href="https://eol.org/pages/{{ $taxon->EOLid }}">https://eol.org/pages/{{ $taxon->EOLid }}</a></td>
        </tr>
        <tr>
            <th>外部URL</th>
            <td><a href="{{ $taxon->furtherInformationURL }}">{{ $taxon->furtherInformationURL }}</a></td>
        </tr>
        <tr>
            <th>親ノード</th>
            <td>
                @foreach (array_reverse($tree) as $k => $t)
                <div style="margin-left: {{ $k*5 }}px">
                    <img src="{{ $t->eol ? $t->eol->img : null }}" width="20px" >
                    <a href="/taxon/{{ $t->taxonID }}">
                        {{ $t->canonicalName }}
                        {{ $t->eol ? $t->eol->jp : null }}
                    </a>
                </div>
                @endforeach
            </td>
        </tr>
    </table>
    <h2>子ノード</h2>
    @include('taxon._table', ['taxa' => $taxon->children])
    <div id="wikipedia">
        <input type="hidden" name="jp" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
    </div>
</div>
@endsection