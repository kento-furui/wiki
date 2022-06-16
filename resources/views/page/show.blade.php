@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
@include('page.common', ['app' => 'page'])
<div class="row">
    <div class="col-6" id="table" style="overflow: hidden">
        <table class="table" style="color: antiquewhite;">
            <tr>
                <th style="width:1%">Status</th>
                <td>
                    @if ($taxon->iucn)
                        <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
                    @else
                        @foreach ($status as $key => $val)
                            <span class="{{ $key }}">{{ $key }} {{ $val }}</span>
                        @endforeach
                    @endif
                </td>
            </tr>
            <tr>
                <th style="width:1%">Ranks</th>
                <td>
                    @foreach ($ranks as $key => $val)    
                        <div class="ranks">{{ $val }} {{ $key }}</div>
                    @endforeach
                </td>
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
                <td>{{ $taxon->furtherInformationURL }} </td>
            </tr>
            <tr>
                <th>Usage</th>
                <td>{{ $taxon->acceptedNameUsageID }} </td>
            </tr>
            <tr>
                <th>Parent</th>
                <td><a href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $taxon->taxonomicStatus }} </td>
            </tr>

            <tr>
                <th>Dataset</th>
                <td>{{ $taxon->datasetID }} </td>
            </tr>
            <tr>
                <th>EOL</th>
                <td>{{ $taxon->EOLid }} </td>
            </tr>
            <tr>
                <th>Annotation</th>
                <td>{{ $taxon->EOLidAnnotations }} </td>
            </tr>

        </table>
    </div>
    <div class="col-6" id="image">
        <img src="{{ $taxon->image ? $taxon->image->eolMediaURL : '/img/noimage.png' }}" width="100%" id="preferred">
        <h4>{{ $taxon->image ? $taxon->image->title : null }}</h4>
        <h5>{!! $taxon->image ? $taxon->image->description : null !!}</h5>
    </div>
</div>
@include('page.children', ['app' => 'page'])
<input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
@endsection