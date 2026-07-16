@extends('admin.layouts.app')
@section('title', 'Modifier l\'œuvre - ABBEV')
@section('header', 'Modifier l\'œuvre')
@section('content')
<div class="mb-6"><a href="{{ route('oeuvres.index') }}" class="inline-flex items-center text-primary-400 hover:text-primary-300"><i class="fas fa-arrow-left mr-2"></i> Retour aux œuvres</a></div>
<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-8 max-w-3xl">
    <form action="{{ route('oeuvres.update', $oeuvre) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('oeuvres._form')
    </form>
</div>
@endsection
