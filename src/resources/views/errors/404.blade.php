@extends('dashboard')

@section('title', '404')
@section('description', 'You requested a page that doesn\'t exist.')

@section('content')

    <div class="container py-md-4">
            <h1 class="h2 mb-3">
                Sorry, we don't have anything to show you on this page
            </h1>


            <p>This could be because:</p>
            <ul>
                <li>The item you're looking for has been deleted</li>
                <li>You don't have access to it</li>
                <li>You clicked a broken link</li>
            </ul>

            <p class="mb-0">If you think you should have access to this page, ask the person who manages the project (or the account) to add you to it.</p>
    </div>

@endsection
