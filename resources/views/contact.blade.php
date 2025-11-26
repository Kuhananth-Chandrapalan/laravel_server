@extends('layout')

@section('title', 'Contact')

@section('content')
<div class="content-section">
    <h1>Contact Us</h1>
    <p>Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
</div>

<div class="content-section">
    <form action="#" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required>
        </div>
        
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" required></textarea>
        </div>
        
        <button type="submit" class="btn">Send Message</button>
    </form>
</div>

<div class="content-section">
    <h2>Contact Information</h2>
    <p><strong>Email:</strong> contact@samplewebsite.com</p>
    <p><strong>Phone:</strong> +1 (555) 123-4567</p>
    <p><strong>Address:</strong> 123 Sample Street, City, State 12345</p>
</div>

<div style="text-align: center; margin-top: 2rem;">
    <a href="{{ route('home') }}" class="btn">Back to Home</a>
    <a href="{{ route('about') }}" class="btn btn-secondary" style="margin-left: 1rem;">Learn More</a>
</div>
@endsection

