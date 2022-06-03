@extends('layout')
@section('title', 'Index')
@section('content')
@include('taxon._table', ['taxa' => $taxa])
@endsection