@extends('layout')

@section('title', 'Home')

@section('content')
<div class="hero">
    <h1>Welcome to Our Sample Website</h1>
    <p>This is a beautiful sample website built with Laravel for testing server hosting</p>
    <a href="{{ route('about') }}" class="btn">Learn More</a>
    <a href="{{ route('contact') }}" class="btn btn-secondary" style="margin-left: 1rem;">Contact Us</a>
</div>

<div class="content-section">
    <h2>About This Project</h2>
    <p>This is a sample Laravel website created to test hosting on a local server. It includes multiple pages, a responsive design, and demonstrates basic Laravel routing and views.</p>
</div>

<div class="grid">
    <div class="card">
        <h3>🚀 Fast & Reliable</h3>
        <p>Built with Laravel framework for optimal performance and reliability.</p>
    </div>
    <div class="card">
        <h3>📱 Responsive Design</h3>
        <p>Works seamlessly on desktop, tablet, and mobile devices.</p>
    </div>
    <div class="card">
        <h3>🔧 Easy to Customize</h3>
        <p>Clean code structure makes it easy to modify and extend.</p>
    </div>
</div>

<div class="content-section" style="margin-top: 2rem;">
    <h2>Features</h2>
    <ul style="list-style-position: inside; line-height: 2;">
        <li>Multiple pages (Home, About, Contact)</li>
        <li>Clean and modern UI design</li>
        <li>Responsive navigation menu</li>
        <li>Contact form ready for integration</li>
        <li>Database connection configured</li>
        <li>Ready for server deployment</li>
    </ul>
</div>
@endsection

