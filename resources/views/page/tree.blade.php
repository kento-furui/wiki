@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
    @include('page.common', ['app' => 'tree'])
    <table class="table" style="color:antiquewhite">
        @foreach (array_reverse($tree) as $key => $t)
            <tr>
                <td width="100px">{{ $t->taxonRank }} </td>
                <td width="120px">
                    <a href="/tree/{{ $t->taxonID }}">
                        <img src="{{ $t->image ? $t->image->eolThumbnailURL : '/img/noimage.png' }}" style="width: 100%">
                    </a>
                </td>
                <td>{{ $t->scientificName }}</td>
                <td>{{ $t->eol ? $t->eol->jp : null }}</td>
                <td>{{ $t->eol ? $t->eol->en : null }}</td>
            </tr>
        @endforeach
    </table>
@endsection