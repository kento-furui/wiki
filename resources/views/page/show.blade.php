@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'page'])
    <div class="row">
        <div class="col-1">Status</div>
        <div class="col-11">
            @if ($taxon->number && !empty($taxon->number->status))
                @foreach (json_decode($taxon->number->status) as $key => $val)
                    <span class="{{ $key }}">{{ $key }} {{ $val }}</span>
                @endforeach
            @endif
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-1">Contains</div>
        <div class="col-11">
            @if ($taxon->number && !empty($taxon->number->json))
                @foreach (json_decode($taxon->number->json) as $key => $val)
                    <div class="ranks">{{ number_format($val) }} {{ $key }}</div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-6" id="table" style="overflow: hidden">
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
                    <td>{{ $taxon->furtherInformationURL }}</td>
                </tr>
                <tr>
                    <th>Usage</th>
                    <td>{{ $taxon->acceptedNameUsageID }}</td>
                </tr>
                <tr>
                    <th>Parent</th>
                    <td>
                        <a class="btn btn-primary" href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $taxon->taxonomicStatus }}</td>
                </tr>
                <tr>
                    <th>Dataset</th>
                    <td>{{ $taxon->datasetID }}</td>
                </tr>
                <tr>
                    <th>EOL</th>
                    <td>{{ $taxon->EOLid }}</td>
                </tr>
                <tr>
                    <th>Annotation</th>
                    <td>{{ $taxon->EOLidAnnotations }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6" id="image">
            @if ($taxon->image)
                <a href="{{ $taxon->image->eolMediaURL }}" data-lightbox="image"
                    data-title="{{ $taxon->image->title }}">
                    <img src="{{ $taxon->image->eolMediaURL }}" width="100%" id="preferred">
                </a>
                <h4>{!! $taxon->image->title !!}</h4>
            @else
                <img src="/img/noimage.png" width="100%">
            @endif
        </div>
    </div>
    @include('page.children', ['app' => 'page'])
@endsection
