@extends('layouts.guest')

@section('title', $page->title ?? ('Products Detail - ' . ($setting->company_name ?? config('app.name'))))

@section('content')
<section class="section-padding">
    <div class="container">
        <h1>{{ $page->title ?? 'Products Detail' }}</h1>
        <p>{{ $page->description ?? 'Content for Products Detail goes here.' }}</p>
    </div>
</section>
@endsection
