@extends('layouts.guest')

@section('title', $page->title ?? ('Products - ' . ($setting->company_name ?? config('app.name'))))

@section('content')
<section class="section-padding">
    <div class="container">
        <h1>{{ $page->title ?? 'Products' }}</h1>
        <p>{{ $page->description ?? 'Content for Products goes here.' }}</p>
    </div>
</section>
@endsection
