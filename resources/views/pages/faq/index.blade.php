@extends('layouts.guest')

@section('title', $page->title ?? ('Faq - ' . ($setting->company_name ?? config('app.name'))))

@section('content')
<section class="section-padding">
    <div class="container">
        <h1>{{ $page->title ?? 'Faq' }}</h1>
        <p>{{ $page->description ?? 'Content for Faq goes here.' }}</p>
    </div>
</section>
@endsection
