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
            <th>保全状況</th>
            <td>
                <select name="status" class="status" id="{{ $taxon->EOLid }}">
                    <option value="">--</option>
                    <option value="EX" {{ $taxon->eol && $taxon->eol->status == "EX" ? "selected" : null }}>絶滅 (EX)</option>
                    <option value="EW" {{ $taxon->eol && $taxon->eol->status == "EW" ? "selected" : null }}>野生絶滅 (EW)</option>
                    <option value="CR" {{ $taxon->eol && $taxon->eol->status == "CR" ? "selected" : null }}>絶滅寸前 (CR)</option>
                    <option value="EN" {{ $taxon->eol && $taxon->eol->status == "EN" ? "selected" : null }}>絶滅危惧 (EN)</option>
                    <option value="VU" {{ $taxon->eol && $taxon->eol->status == "VU" ? "selected" : null }}>危急 (VU)</option>
                    <option value="CD" {{ $taxon->eol && $taxon->eol->status == "CD" ? "selected" : null }}>保全対策依存 (CD)</option>
                    <option value="NT" {{ $taxon->eol && $taxon->eol->status == "NT" ? "selected" : null }}>準絶滅危惧 (NT)</option>
                    <option value="LC" {{ $taxon->eol && $taxon->eol->status == "LC" ? "selected" : null }}>低危険種 (LC)</option>
                    <option value="DD" {{ $taxon->eol && $taxon->eol->status == "DD" ? "selected" : null }}>データ不足 (DD)</option>
                    <option value="NE" {{ $taxon->eol && $taxon->eol->status == "NE" ? "selected" : null }}>未評価 (NE)</option>
                </select>
            </td>
        <tr>
            <th>ソース</th>
            <td>{{ $taxon->source }}</td>
        </tr>
        <tr>
            <th>EOL</th>
            <td><a target="_blank"
                    href="https://eol.org/pages/{{ $taxon->EOLid }}">https://eol.org/pages/{{ $taxon->EOLid }}</a></td>
        </tr>
        <tr>
            <th>外部URL</th>
            <td><a target="_blank" href="{{ $taxon->furtherInformationURL }}">{{ $taxon->furtherInformationURL }}</a>
            </td>
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