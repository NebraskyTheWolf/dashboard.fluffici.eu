@include("app")

@section('title', '404')
@section('description', __("Požadovali jste stránku, která neexistuje."))

@section('content')

    <div class="container py-md-4">
        <h1 class="h2 mb-3">
            {{ __("Omlouváme se, ale na této stránce nemáme nic k zobrazení.") }}
        </h1>


        <p>{{ __("Toto by mohlo být z následujících důvodů:") }}</p>
        <ul>
            <li>{{ __("Položka, kterou hledáte, byla smazána") }}</li>
            <li>{{ __("Ke které nemáte přístup") }}</li>
            <li>{{ __("Klikli jste na nesprávný odkaz") }}</li>
        </ul>

        <p class="mb-0">{{ __("Pokud si myslíte, že byste měli mít přístup k této stránce, požádejte osobu, která spravuje projekt (nebo účet), aby vás k němu přidala.") }}</p>
    </div>

@endsection
