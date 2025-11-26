@extends('layout')

@section('title', 'About')

@section('content')
<div class="content-section">
    <h1>About Us</h1>
    <p>This is a sample website created to demonstrate Laravel hosting capabilities on a local server setup.</p>
</div>

<div class="content-section">
    <h2>Project Information</h2>
    <p><strong>Framework:</strong> Laravel {{ app()->version() }}</p>
    <p><strong>Database:</strong> MySQL (servertest)</p>
    <p><strong>Purpose:</strong> Testing and demonstration</p>
    <p><strong>Status:</strong> Ready for deployment</p>
</div>

<div class="content-section">
    <h2>What This Website Includes</h2>
    <ul style="list-style-position: inside; line-height: 2;">
        <li>Homepage with hero section and feature cards</li>
        <li>About page with project information</li>
        <li>Contact page with a functional form</li>
        <li>Responsive navigation menu</li>
        <li>Modern, clean design</li>
        <li>Database integration ready</li>
    </ul>
</div>

<div class="content-section">
    <h2>Technical Details</h2>
    <p>This Laravel application is configured with:</p>
    <ul style="list-style-position: inside; line-height: 2;">
        <li>MySQL database connection</li>
        <li>Migrations for database tables</li>
        <li>Blade templating engine</li>
        <li>Route-based navigation</li>
        <li>Environment configuration</li>
    </ul>
</div>

<div style="text-align: center; margin-top: 2rem;">
    <a href="{{ route('home') }}" class="btn">Back to Home</a>
    <a href="{{ route('contact') }}" class="btn btn-secondary" style="margin-left: 1rem;">Get in Touch</a>
</div>
@endsection

