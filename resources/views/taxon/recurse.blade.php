@extends('layout')
@section('title', $taxon->canonicalName)
@section('content')
<a href="/taxon/{{ $taxon->taxonID }}"><< Back</a>
<h1>
    {{ $taxon->canonicalName }}
    {{ $taxon->eol ? $taxon->eol->jp : null }}
    {{ $taxon->eol ? $taxon->eol->en : null }}
</h1>
<table class="table" id="taxa">
    @include('taxon._recurse', ['taxa' => $taxon->children])
</table>
@endsection