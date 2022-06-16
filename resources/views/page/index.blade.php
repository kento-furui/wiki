@extends('layout')

@section('content')
    <div class="row">
        @foreach ($taxa as $taxon)
            <div class="col-4">
                <a href="/page/{{ $taxon->taxonID }}">
                    {{ $taxon->canonicalName }}
                    @if ($taxon->image)
                        <img src="{{ $taxon->image->eolThumbnailURL }}">
                    @endif
                </a>
            </div>
        @endforeach
    </div>
@endsection
