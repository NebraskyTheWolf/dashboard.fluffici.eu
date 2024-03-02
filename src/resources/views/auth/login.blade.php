@extends('auth')
@section('title', 'Přihlaste se do svého účtu')

@section('content')
    <h1 class="h4 text-white mb-4">Přihlaste se do svého účtu</h1>

    <form class="m-t-md"
          role="form"
          method="POST"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="false"
          data-action="form#submit"
          action="{{ route('login.auth') }}">
        @csrf

        @includeWhen($isLockUser,'auth.lockme')
        @includeWhen(!$isLockUser,'auth.signin')
    </form>
@endsection
