<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sample Website') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #e5e7eb;
            background: #000000;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        header {
            background: #111111;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
            border-bottom: 1px solid #333333;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        .nav-links a {
            text-decoration: none;
            color: #e5e7eb;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-links a:hover {
            color: #ffffff;
        }
        main {
            min-height: calc(100vh - 200px);
            padding: 3rem 0;
        }
        footer {
            background: #1f2937;
            color: #fff;
            text-align: center;
            padding: 2rem 0;
            margin-top: 4rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #ffffff;
            color: #000000;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background 0.3s, color 0.3s;
        }
        .btn:hover {
            background: #e5e7eb;
            color: #000000;
        }
        .btn-secondary {
            background: #333333;
            color: #ffffff;
        }
        .btn-secondary:hover {
            background: #4a4a4a;
            color: #ffffff;
        }
        .hero {
            text-align: center;
            padding: 4rem 0;
            background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
            color: #fff;
            border-radius: 1rem;
            margin-bottom: 3rem;
            border: 1px solid #333333;
        }
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .content-section {
            background: #111111;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
            margin-bottom: 2rem;
            border: 1px solid #333333;
            color: #e5e7eb;
        }
        .content-section h2 {
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .content-section h1 {
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .card {
            background: #111111;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.5);
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            border: 1px solid #333333;
            color: #e5e7eb;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(255,255,255,0.1);
            border-color: #555555;
        }
        .card h3 {
            color: #ffffff;
            margin-bottom: 1rem;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #e5e7eb;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #333333;
            border-radius: 0.5rem;
            font-family: inherit;
            background: #1a1a1a;
            color: #e5e7eb;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #555555;
            background: #222222;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        ul {
            color: #e5e7eb;
        }
        p {
            color: #e5e7eb;
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <a href="{{ route('home') }}" class="logo">SampleSite</a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} Sample Website. Built with Laravel.</p>
        </div>
    </footer>
</body>
</html>

