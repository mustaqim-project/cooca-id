@extends('layouts.guest')

@section('title', $page->title ?? ('Docs - ' . ($setting->company_name ?? config('app.name'))))

@section('content')
<section class="section-padding">
    <div class="container">
        <h1>{{ $page->title ?? 'Docs' }}</h1>
        <p>{{ $page->description ?? 'Content for Docs goes here.' }}</p>
    </div>
</section>
@endsection
