@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
<a href="/">index</a>
<h1>{{ $taxon->scientificName }}</h1>
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button"
            role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
        <button class="nav-link" id="nav-wiki-tab" data-bs-toggle="tab" data-bs-target="#nav-wiki" type="button"
            role="tab" aria-controls="nav-wiki" aria-selected="true">Wikipedia</button>
        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button"
            role="tab" aria-controls="nav-profile" aria-selected="false">Parents</button>
        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button"
            role="tab" aria-controls="nav-contact" aria-selected="false">Children</button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <div id="images" style="overflow-x: scroll; white-space: nowrap">
            <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
        </div>
        <table class="table" style="table-layout: fixed;">
            <tr>
                <th width="90px">学名</th>
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
                <th>画像</th>
                <td>
                    @if ($taxon->eol)
                    <img src="{{ $taxon->eol->img }}" id="thumb">
                    @endif
                </td>
            </tr>
            <tr>
                <th>親ノード</th>
                <td>
                    @if ($taxon->parent)
                    <a href="{{ $taxon->parent->taxonID }}">
                        {{ $taxon->parent->scientificName }}
                        {{ $taxon->parent->eol ? $taxon->parent->eol->jp : null }}</a>
                    @endif
                </td>
            </tr>
            <tr>
                <th>ツリー</th>
                <td>
                    @foreach (array_reverse($tree) as $k => $t)
                    <a href="{{ $t->taxonID }}" style="display: block; margin-left: {{ $k * 5 }}px">
                        {{ $t->canonicalName }}
                        {{ $t->eol ? $t->eol->jp : null}}</a>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>子ノード</th>
                <td>
                    @foreach ($taxon->children as $c)
                    <a href="{{ $c->taxonID }}" style="display: block;">
                        {{ $c->canonicalName }}
                        {{ $c->eol ? $c->eol->jp : null}}</a>
                    @endforeach
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
    <div class="tab-pane fade" id="nav-wiki" role="tabpanel" aria-labelledby="nav-wiki-tab">
        <input type="hidden" name="jp" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
        <div id="wikipedia"></div>
    </div>
    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
        @include('taxon._table', ['taxa' => array_reverse($tree)])
    </div>
    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
        @include('taxon._table', ['taxa' => $taxon->children])
    </div>
</div>
@endsection