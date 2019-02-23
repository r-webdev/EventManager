@extends('core.base')

@section('sectionBodyPrefix')
    @parent

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            @if(true)
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
            @endif
        </div>

        <div id="navbarPrimary" class="navbar-menu">
            <div class="navbar-start">
                <a href="/schedule" class="navbar-item">
                    Schedule
                </a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Menu 1
                    </a>
                    <div class="navbar-dropdown">
                        <a href="/menu/1/sub/1" class="navbar-item">
                            Sub Menu 1.1
                        </a>
                        <a href="/menu/1/sub/2" class="navbar-item">
                            Sub Menu 1.2
                        </a>
                        <hr class="navbar-divider">
                        <a href="/menu/1/sub/3" class="navbar-item">
                            Sub Menu 1.3
                        </a>
                    </div>
                </div>
            </div>
            @if(true)
                <div class="navbar-end">
                    <div class="navbar-item">
                        <a href="/account/register" class="navbar-item">
                            <strong>Sign up</strong>
                        </a>
                        <a href="/app/login" class="navbar-item">
                            Log in
                        </a>
                    </div>
                </div>
            @endif
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

    @foreach(($arrayNotificationTypes = ['success' => (isset($arraySuccess) && is_array($arraySuccess) && !empty($arraySuccess) ? $arraySuccess : []), 'danger' => (isset($arrayErrors) && is_array($arrayErrors) && !empty($arrayErrors) ? $arrayErrors : [])]) as $stringNotificationType => $arrayNotifications)
        @if(isset($arrayNotifications) && is_array($arrayNotifications) && !empty($arrayNotifications))
            <div class="container has-text-centered">
                <div class="columns">
                    <div class="column is-offset-one-quarter is-half">
                        @foreach($arrayNotifications as $stringNotification)
                            <div class="notification is-{{ $stringNotificationType }} has-text-left">
                                <button class="delete"></button>
                                {{ $stringError }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    @if(!empty($arrayNotificationTypes['success']) || !empty($arrayNotificationTypes['danger']))
        <script>
            $('.notification > button').on('click', function() {
                $(this).parent().remove();
            });
        </script>
    @endif
@endsection

@section('sectionBodyContent')
    @parent
@endsection

@section('sectionBodySuffix')
    @parent

    @include('core.foot')
@endsection