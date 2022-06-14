@extends('page')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'page'])
    <div class="row">
        <div class="col-6" id="table" style="overflow: hidden">
            <table class="table" style="color: antiquewhite;">
                <tr>
                    <td>
                        {{ $taxon->taxonRank }}
                        <h5>{{ $taxon->canonicalName }}</h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        @if ($taxon->iucn)
                            <span class="{{ $taxon->iucn->status }}">{{ $taxon->iucn->status }}</span>
                        @else
                            @foreach ($status as $key => $val)
                                <div class="{{ $key }}">{{ $key }} {{ $val }}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        @foreach ($nodes as $key => $val)
                            <div class="nodes">{{ $key }} {{ $val }}</div>
                        @endforeach
                    </td>
                </tr>
            </table>
            <table class="table" style="color: antiquewhite;">
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
                    <th>Remarks</th>
                    <td>{{ $taxon->taxonRemarks }} </td>
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
                <tr>
                    <th>Landmark</th>
                    <td>{{ $taxon->Landmark }} </td>
                </tr>
            </table>
        </div>
        <div class="col-6" id="image">
            <img src="{{ $taxon->image ? $taxon->image->eolMediaURL : '/img/noimage.png' }}" width="100%" id="preferred">
            <h4>{{ $taxon->image ? $taxon->image->title : null }}</h4>
            <h5>{!! $taxon->image ? $taxon->image->description : null !!}</h5>
        </div>
    </div>
    <h2>Images <small style="font-size: 80%">{{ count($taxon->images) }}</small></h2>
    <div class="row" id="thumbnail">
        @foreach ($taxon->images as $image)
            <div class="col-1">
                <img src="{{ $image->eolThumbnailURL }}" title="{{ $image->description }}" width="100%"
                    class="thumb" id="{{ $image->identifier }}">
            </div>
        @endforeach
    </div>
    @include('page.children')
    <input type="hidden" id="EOLid" value="{{ $taxon->EOLid }}" />
@endsection