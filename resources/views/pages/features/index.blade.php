@extends('layouts.guest')

@section('title', $page->title ?? ('Features - ' . ($setting->company_name ?? config('app.name'))))

@section('content')
<section class="section-padding">
    <div class="container">
        <h1>{{ $page->title ?? 'Features' }}</h1>
        <p>{{ $page->description ?? 'Content for Features goes here.' }}</p>
    </div>
</section>
@endsection
