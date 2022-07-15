@extends('layout')
@section('title', $taxon->canonicalName)
@section('content')
    @include('page.common', ['app' => 'page'])
    <table class="table">
        <tr>
            <td>
                @if ($taxon->number && !empty($taxon->number->json))
                    @foreach (json_decode($taxon->number->json) as $key => $val)
                        <div class="ranks">{{ number_format($val) }} {{ $key }}</div>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>
                @if ($taxon->number && !empty($taxon->number->status))
                    @foreach (json_decode($taxon->number->status) as $key => $val)
                        @if (!empty($val))
                            <span class="{{ $key }}">{{ $key }} {{ number_format($val) }}</span>
                        @endif
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td>
                @if ($taxon->number && !empty($taxon->number->node))
                    @foreach (json_decode($taxon->number->node) as $key => $val)
                        @if (!empty($val))
                            <div class="ranks">{{ $key }} {{ number_format($val) }}</div>
                        @endif
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
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
                        <a class="btn btn-primary"
                            href="/page/{{ $taxon->parentNameUsageID }}">{{ $taxon->parentNameUsageID }}</a>
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