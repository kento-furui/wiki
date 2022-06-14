@extends('page')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'media'])
    <div class="row">
        <div class="col-12" id="table" style="overflow: hidden">
            <table class="table" style="color: antiquewhite;">
                <tr>
                    <td>
                        {{ $taxon->taxonRank }}
                        <h5>{{ $taxon->canonicalName }}</h5>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        @foreach ($taxon->images as $image)
            <div class="col-3">
                <a href="{{ $image->eolMediaURL }}" data-lightbox="image" data-title="{{ $image->title }}">
                    <img src="{{ $image->eolMediaURL }}" width="100%">
                </a>
                <p>{!! $image->description !!}</p>
            </div>
        @endforeach
    </div>
    @include('page.children')
@endsection