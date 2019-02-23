<!DOCTYPE html>
<html lang="en">
<head>
    @section('sectionHeaderPrefix')
    @show

    <meta charset="utf-8">
    <base href="/">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="images/logo-75x75-white.png?v={{ config('app.version') }}" />
    <link rel="apple-touch-icon-precomposed" href="images/logo-75x75-white.png?v={{ config('app.version') }}" />
    <link rel="icon" type="image/x-icon" href="images/logo-75x75-white.png?v={{ config('app.version') }}" />

    <title>
        @section('sectionTitlePrefix')
        @show
        @section('sectionTitleContent')
            {{ config('app.name.long') }}
        @show
        @section('sectionTitleSuffix')
        @show
    </title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- moment.js -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.14/moment-timezone-with-data.min.js"></script>

    <!-- BootBox -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

    <!-- BULMA -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">

    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>
    @section('sectionHeaderSuffix')
    @show
</head>
<body>

<section class="hero is-fullheight is-light">
    <div class="hero-head">
        @section('sectionBodyPrefix')
        @show
    </div>
    <div class="hero-body" style="align-items: unset !important;">
        @section('sectionBodyContentPrefix')
        @show

        @section('sectionBodyContent')
        @show

        @section('sectionBodyContentSuffix')
        @show
    </div>
    <div class="hero-foot">
        @section('sectionBodySuffix')
        @show
    </div>
</section>

</body>
</html>