@extends('layouts.app')

@section('head')
<title>Error 500 | {{ config('app.name') }}</title>
<meta name="description" content="">
<meta name="keywords" content="">

@endsection

@section('content')
<section class="main padding-topdown">
    <div class="container">

        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <h1 class="text-red" style="font-size:78px">Error 500</h1>
                <h2>Sorry, an internal server error has occurred. Please try again later.</h2>

                @include('partials._profile_search')
            </div>
        </div>

    </div>

</section>
@endsection

