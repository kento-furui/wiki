@extends('layout')
@section('title', $taxon->canonicalName)
@section('content')
<a href="/">index</a>
<a href="/taxon/rand">rand</a>
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
                <img src="{{ $taxon->image ? $taxon->image->eolThumbnailURL : null }}" id="thumb">
            </td>
        </tr>
        <tr>
            <th>メンテナンス</th>
            <td>
                <a id="sum" href="/taxon/sumall/{{ $taxon->taxonID }}" class="btn btn-success">Sum</a>
                <a id="rec" href="/taxon/recurse/{{ $taxon->taxonID }}" class="btn btn-warning">Recurse</a>
                <a id="par" href="/taxon/{{ $taxon->parent ? $taxon->parent->taxonID : $taxon->taxonID }}"
                    class="btn btn-secondary">Parent</a>
                <a id="rep" href="/taxon/represent/{{ $taxon->taxonID }}" class="btn btn-primary"
                    onclick="return confirm('Represent?')">Represent</a>
                <a id="rep" href="/taxon/extinct/{{ $taxon->taxonID }}" class="btn btn-danger"
                    onclick="return confirm('Extinct?')">Extinct</a>
                @if ($taxon->taxonRank == 'species')
                <select name="edit_status" style="display:inline-block; vertical-align: middle; width:200px"
                    class="form-control" id="{{ $taxon->taxonID }}">
                    <option value="">--</option>
                    <option value="EX" @if ($taxon->iucn && $taxon->iucn->status == 'EX')selected @endif>EX - 絶滅</option>
                    <option value="EW" @if ($taxon->iucn && $taxon->iucn->status == 'EW')selected @endif>EW - 野生絶滅</option>
                    <option value="CR" @if ($taxon->iucn && $taxon->iucn->status == 'CR')selected @endif>CR - 絶滅寸前</option>
                    <option value="EN" @if ($taxon->iucn && $taxon->iucn->status == 'EN')selected @endif>EN - 絶滅危惧</option>
                    <option value="VU" @if ($taxon->iucn && $taxon->iucn->status == 'VU')selected @endif>VU - 危急</option>
                    <option value="CD" @if ($taxon->iucn && $taxon->iucn->status == 'CD')selected @endif>CD - 保全対策依存</option>
                    <option value="NT" @if ($taxon->iucn && $taxon->iucn->status == 'NT')selected @endif>NT - 準絶滅危惧</option>
                    <option value="LC" @if ($taxon->iucn && $taxon->iucn->status == 'LC')selected @endif>LC - 低危険種</option>
                    <option value="DD" @if ($taxon->iucn && $taxon->iucn->status == 'DD')selected @endif>DD - データ不足</option>
                </select>
                @endif
            </td>
        </tr>
        <tr>
            <th>ノード数</th>
            <td>{{ $taxon->number ? $taxon->number->nodes() : null }}</td>
        </tr>
        <tr>
            <th>データ数</th>
            <td>{{ $taxon->number ? $taxon->number->names() : null }}</td>
        </tr>
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
                <input type="text" name="edit_jp" class="form-control" id="{{ $taxon->EOLid }}"
                    value="{{ $taxon->eol ? $taxon->eol->jp : null }}" />
            </td>
        </tr>
        <tr>
            <th>英名</th>
            <td>
                <input type="text" name="edit_en" class="form-control" id="{{ $taxon->EOLid }}"
                    value="{{ $taxon->eol ? $taxon->eol->en : null }}" />
            </td>
        </tr>
        <tr>
            <th>保全状況</th>
            <td>
                @if ($taxon->iucn)
                    <div class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</div>
                @elseif (!empty($status))
                    @foreach($status as $key => $val)
                        <div class="{{ $key }}">{{$key}} {{ $val }}</div>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <th>ソース</th>
            <td>{{ $taxon->source }}</td>
        </tr>
        <tr>
            <th>EOL</th>
            <td>
                <a target="_blank" href="https://eol.org/pages/{{ $taxon->EOLid }}">https://eol.org/pages/{{
                    $taxon->EOLid }}</a>
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