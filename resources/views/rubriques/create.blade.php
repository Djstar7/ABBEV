@extends('admin.layouts.app')
@section('title', 'Nouvelle rubrique - ABBEV')
@section('header', 'Nouvelle rubrique')
@section('content')
<div class="mb-6"><a href="{{ route('rubriques.index') }}" class="inline-flex items-center text-primary-400 hover:text-primary-300"><i class="fas fa-arrow-left mr-2"></i> Retour aux rubriques</a></div>
<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-8 max-w-3xl">
    <form action="{{ route('rubriques.store') }}" method="POST" enctype="multipart/form-data">
        @include('rubriques._form')
    </form>
</div>
@endsection
