@extends('layout')

@section('title', $taxon->canonicalName)

@section('content')
<a href="/">index</a>
<h1>
    {{ $taxon->canonicalName }}
    {{ $taxon->eol ? $taxon->eol->jp : null }}
    {{ $taxon->eol ? $taxon->eol->en : null }}
</h1>

@include('taxon._recurse', ['taxa' => $taxon->children])

@endsection
