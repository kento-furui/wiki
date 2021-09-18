@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
<a href="/">index</a>
<h1>
    {{ $taxon->canonicalName }}
    {{ $taxon->eol ? $taxon->eol->jp : null }}
    {{ $taxon->eol ? $taxon->eol->en : null }}
</h1>
<div id="images" style="overflow-x: scroll; white-space: nowrap; height:90px">
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
</div>
<div class="table-responsive">
    <table class="table" style="table-layout: fixed;">
        <tr>
            <th width="90px">画像</th>
            <td>
                <img src="{{ $taxon->eol ? $taxon->eol->img : null }}" id="thumb">
            </td>
        </tr>
        <tr>
            <th>ツリー</th>
            <td>
                <table>
                    @foreach (array_reverse($tree) as $k => $t)
                    <tr>
                        <td><img src="{{ $t->eol ? $t->eol->img : null }}" class="tree"></td>
                        <th>{{ $t->taxonRank }}</th>
                        <td><a href="/taxon/{{ $t->taxonID }}">{{ $t->canonicalName }}</a></td>
                        <td>{{ $t->eol ? $t->eol->jp : null }}</td>
                        <td>{{ $t->eol ? $t->eol->en : null }}</td>
                    </tr>
                    @endforeach
                </table>
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
            <td>
                <input type="text" name="jp" class="jp" size="30" id="{{ $taxon->EOLid }}"
                    value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
            </td>
        </tr>
        <tr>
            <th>英名</th>
            <td>
                <input type="text" name="en" class="en" size="30" id="{{ $taxon->EOLid }}"
                    value="{{ $taxon->eol ? $taxon->eol->en : null }}" />
            </td>
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
    </table>
</div>
@include('taxon._table', ['taxa' => $taxon->children])
<div id="wikipedia">
    @if ($taxon->eol && !empty($taxon->eol->jp))
    <input type="hidden" name="title" value="{{ $taxon->eol->jp }}" />
    @else
    <input type="hidden" name="canonical" value="{{ $taxon->canonicalName }}" />
    @endif
</div>
@endsection