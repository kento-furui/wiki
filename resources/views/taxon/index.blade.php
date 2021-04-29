@extends('layout')

@section('title', 'Index')

@section('content')
{{ $taxa->links() }}
@include('taxon._table', ['taxa' => $taxa])
{{ $taxa->links() }}
@endsection