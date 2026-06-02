@extends('admin.layouts.app')

@section('title', 'Modifier la séance - ABBEV')
@section('header', 'Modifier la séance')

@section('content')
<div class="mb-6">
    <a href="{{ route('screenings.index') }}" class="inline-flex items-center text-primary-400 hover:text-primary-300 transition">
        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
    </a>
</div>

<div class="bg-dark-100 rounded-xl shadow-lg border border-dark-200 p-8">
    <form action="{{ route('screenings.update', $screening) }}" method="POST">
        @csrf
        @method('PUT')

        @include('screenings._form', ['screening' => $screening, 'movies' => $movies])

        <div class="flex gap-4 mt-8">
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg transition flex-1">
                <i class="fas fa-check mr-2"></i> Enregistrer
            </button>
            <a href="{{ route('screenings.index') }}" class="bg-dark-200 hover:bg-dark-300 text-white px-6 py-3 rounded-lg transition text-center">
                <i class="fas fa-times mr-2"></i> Annuler
            </a>
        </div>
    </form>
</div>
@endsection
