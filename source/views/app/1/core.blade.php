@extends('core.base')

@section('sectionHeaderSuffix')
    @parent

    <!-- JS-COOKIE -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.2/js.cookie.min.js"></script>

    <!-- Moss Libraries -->
    <script src="/js/MossHelpers.js?v={{ config('app.version') }}"></script>
    <script src="/js/MossApi.js?v={{ config('app.version') }}"></script>
    <script src="/js/MossToken.js?v={{ config('app.version') }}"></script>
@endsection

@section('sectionBodyPrefix')
    @parent

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <strong>
                    <img src="images/logo-75x75-white.png" style="vertical-align: middle; margin-top: -2px;"/> {{ config('app.name.long') }}
                </strong>
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarPrimary">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div id="navbarPrimary" class="navbar-menu">
            <div class="hideWhenAny showWhenNotAuth navbar-end" style="display: none;">
                <div class="navbar-item">
                    <a href="/account/register" class="navbar-item">
                        <strong>Sign up</strong>
                    </a>
                    <a href="/app/login" class="navbar-item">
                        Log in
                    </a>
                </div>
            </div>
            <div class="hideWhenAny showWhenAuth navbar-end" style="display: none;">
                <div class="navbar-item">
                    <a href="/app/account" class="navbar-item">
                        Account
                    </a>
                    <a id="buttonLogout" class="navbar-item">
                        Log out
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <script>
        $(document).ready(function() {
            $('.burger').each(function() {
                $(this).on('click', function () {
                    $(this).toggleClass('is-active');
                    $('#' + $(this).data('target')).toggleClass('is-active');
                });
            });
        });
    </script>
    <div class="columns">
        <div class="column is-offset-one-quarter is-half">

            <article id="articleNotification" class="message" style="display: none;">
                <div class="message-header">
                </div>
                <div class="message-body has-text-left">
                </div>
            </article>

            <script>
                function notification(objectSettings) {
                    // Ensure settings is an object
                    objectSettings = void 0 !== objectSettings && typeof objectSettings === 'object' ? objectSettings : {};

                    // Get the notifiacation
                    var articleNotification = $('#articleNotification');

                    // Hide the notification.
                    articleNotification.hide();

                    // Get the header and body
                    var resultsHeader = articleNotification.find('.message-header');
                    var resultsBody = articleNotification.find('.message-body');

                    // Reset Notifcation
                    articleNotification.removeClass('is-danger is-info is-success is-dark is-light');

                    // Empty header and body
                    resultsHeader.empty();
                    resultsBody.empty();

                    // Do we have a defined header title
                    if (void 0 !== objectSettings.title && objectSettings.title.length > 0) {
                        resultsHeader
                            .append(
                                $('<p />')
                                    .text(objectSettings.title)
                            )
                            .show();
                    } else {
                        resultsHeader.hide();
                    }

                    // Do we have a type
                    if (void 0 !== objectSettings.type && objectSettings.type.length > 0) {
                        switch (objectSettings.type) {
                            case 'danger':
                                articleNotification.addClass('is-danger');
                                break;
                            case 'success':
                                articleNotification.addClass('is-success');
                                break;
                            default:
                                articleNotification.addClass('is-dark');
                        }
                    }

                    // Do we have content
                    if (void 0 !== objectSettings.content) {
                        $.each(objectSettings.content, function (indexContent, objectContent) {
                            // Do we have a heading
                            if (void 0 !== objectContent.heading && objectContent.heading.length > 0) {
                                resultsBody.append(
                                    $('<h2 class="subtitle" style="margin: 10px 0px 0px 0px; color: #000;" />')
                                        .text(objectContent.heading)
                                );
                            }

                            // Do we have content items
                            if (void 0 !== objectContent.items) {
                                $.each(objectContent.items, function(indexItem, stringItem) {
                                    resultsBody.append(
                                        $('<p />')
                                            .text(stringItem)
                                    );
                                });
                            }
                        });
                        resultsBody.show();
                    } else {
                        resultsBody.hide();
                    }

                    // Show the notification
                    articleNotification.show();
                }
            </script>

        </div>
    </div>
@endsection

@section('sectionBodyContent')
    @parent

    <script>
        var classMossHelpers, classMossApi, classMossToken, objectInputs;

        objectInputs = JSON.parse('{!! json_encode($inputs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}');

        $(document).ready(function() {
            // Instantiate the Moss Libraries
            classMossHelpers = new MossHelpers();
            classMossApi = new MossApi({
                helpers: classMossHelpers,
                url: '{{ localAddress() }}',
                uri: '/api/v1'
            });
            classMossToken = new MossToken({
                helpers: classMossHelpers,
                api: classMossApi,
                cookies: {
                    load: true
                }
            });
        });
    </script>

@endsection

@section('sectionBodySuffix')
    @parent

    @include('core.foot')
@endsection