@extends('core.empty')

@section('sectionBodyContent')
    @parent

    <style>
        @font-face {
            font-family:"Amiga4Ever";
            src: url("fonts/amiga4ever.ttf") /* TTF file for CSS3 browsers */
        }

        h1 {
            font-family:'Amiga4Ever', sans-serif;
            font-size: 36px;
            color: #ff0000;
        }
        p {
            font-family:'Amiga4Ever', sans-serif;
            font-size: 12px;
            color: #ff0000;
        }

        .Amiga4EverFont {
            font-family:'Amiga4Ever', sans-serif;
            font-size: 10px;
            color: #ff0000;
        }

        body {
            background-color: #000000 !important;
        }

        .hero {
            background-color: #000000 !important;
        }

        a:hover {
            text-decoration: none;
        }
    </style>

    <div class="container has-text-centered">
        <h1>
            {{ config('app.name.long') }}
        </h1>
        <p>
            500 - Unknown Error
        </p>
        <img src="images/guru_meditation_error.gif" style="margin: 100px 10px 100px 10px;" />
        <p>
            Ok, so this time it looks like we caused the error in the Guru's meditation... but since we are never at fault, you clearly failed to correctly use our system!
        </p>
    </div>

@endsection

@section('sectionBodySuffix')
@endsection

