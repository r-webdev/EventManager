@extends('core.full')

@section('sectionBodyContent')
    @parent
    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">
                <a href="/">
                    <img src="images/logo-250x250-white.png" style="width: 100%; max-width: 180px;" />
                    <h1 class="title is-1">{{ config('app.name.long') }}</h1>
                </a>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full has-text-center">
                <h4 class="subtitle is-4">coming soon...</h4>
            </div>
        </div>
    </div>

@endsection