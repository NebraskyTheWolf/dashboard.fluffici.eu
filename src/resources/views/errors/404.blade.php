@extends('dashboard')

@section('title', '404')
@section('description', 'Požádali jste o stránku, která neexistuje.')

@section('content')

    <div class="container py-md-4">
            <h1 class="h2 mb-3">
                Promiňte, na této stránce nemáme co vám ukázat
            </h1>


            <p>Toto by mohlo být, protože:</p>
            <ul>
                <li>Položka, kterou hledáte, byla odstraněna</li>
                <li>Nemáte k ní přístup</li>
                <li>Kliknuli jste na nefunkční odkaz</li>
            </ul>

            <p class="mb-0">Pokud si myslíte, že byste měli mít přístup na tuto stránku, požádejte osobu, která spravuje projekt (nebo účet), aby vás do ní přidala.</p>
    </div>

@endsection
