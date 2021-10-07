@extends('layout')
@section('title', $taxon->canonicalName)
@section('content')
<a href="/">index</a>
<h1>
    {{ $taxon->canonicalName }}
    {{ $taxon->eol ? $taxon->eol->jp : null }}
    {{ $taxon->eol ? $taxon->eol->en : null }}
</h1>
@auth
<div id="images" style="overflow-x: scroll; white-space: nowrap; height:90px">
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
</div>
@else
<div id="images" style="overflow-x: scroll; white-space: nowrap; height:200px">
    <input type="hidden" id="guest" value="{{ $taxon->EOLid }}" />
</div>
@endauth
<div class="table-responsive">
    <table class="table" style="table-layout: fixed;">
        @auth
        <tr>
            <th width="90px">画像</th>
            <td>
                <img src="{{ $taxon->eol ? $taxon->eol->img : null }}" id="thumb">
            </td>
        </tr>
        @endauth
        <tr>
            <th width="90px">親ノード</th>
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
                @auth
                    <input type="text" name="edit_jp" class="form-control" id="{{ $taxon->EOLid }}" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
                @else
                    {{ $taxon->eol ? $taxon->eol->jp : null }}
                @endauth
            </td>
        </tr>
        <tr>
            <th>英名</th>
            <td>
                @auth
                    <input type="text" name="edit_en" class="form-control" id="{{ $taxon->EOLid }}" value="{{ $taxon->eol ? $taxon->eol->en : null }}" />
                @else
                    {{ $taxon->eol ? $taxon->eol->en : null }}
                @endauth
            </td>
        </tr>
        @auth
        <tr>
            <th>メンテナンス</th>
            <td>
                <a href="/taxon/sumall/{{ $taxon->taxonID }}" class="btn btn-success">Sum</a>
                <a href="/taxon/recurse/{{ $taxon->taxonID }}" class="btn btn-warning">Recurse</a>
                <a href="/taxon/{{ $taxon->parent ? $taxon->parent->taxonID : $taxon->taxonID }}" class="btn btn-default">Parent</a>
                <a href="/taxon/extinct/{{ $taxon->taxonID }}" class="btn btn-danger" onclick="return confirm('Extinct?')">Extinct</a>
                <a href="/taxon/represent/{{ $taxon->taxonID }}" class="btn btn-primary" onclick="return confirm('Represent?')">Represent</a>
                <select name="edit_status" style="display:inline-block; vertical-align: middle; width:200px" class="form-control" id="{{ $taxon->taxonID }}">
                    <option value="">--</option>
                    <option value="EX">EX - 絶滅</option>
                    <option value="EW">EW - 野生絶滅</option>
                    <option value="CR">CR - 絶滅寸前</option>
                    <option value="EN">EN - 絶滅危惧</option>
                    <option value="VU">VU - 危急</option>
                    <option value="NT">NT - 準絶滅危惧</option>
                    <option value="LC">LC - 低危険種</option>
                    <option value="DD">DD - データ不足</option>
                </select>
            </td>
        </tr>
        @endauth
        <tr>
            <th>保全状況</th>
            <td>
                @if ($taxon->iucn)
                    {!! $taxon->iucn->inline() !!}
                @endif
           </td>
        </tr>
        <tr>
            <th>ノード数</th>
            <td>{{ $taxon->number ? $taxon->number->inline() : null }}</td>
        </tr>
        <tr>
            <th>ソース</th>
            <td>{{ $taxon->source }}</td>
        </tr>
        <tr>
            <th>EOL</th>
            <td>
                <a target="_blank" href="https://eol.org/pages/{{ $taxon->EOLid }}">https://eol.org/pages/{{ $taxon->EOLid }}</a>
            </td>
        </tr>
        <tr>
            <th>外部URL</th>
            <td>
                <a target="_blank" href="{{ $taxon->furtherInformationURL }}">{{ $taxon->furtherInformationURL }}</a>
            </td>
        </tr>
    </table>
</div>
@include('taxon._table', ['taxa' => $taxon->children])
<input type="hidden" name="canonical" value="{{ $taxon->canonicalName }}" />
<input type="hidden" name="title" value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
<div id="japanese"></div>
<div id="english"></div>
@endsection
