@extends('app.1.core')

@section('sectionTitleContent')
    @parent - App : Dashboard
@endsection

@section('sectionBodyContent')
    @parent
    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">
                <h1 class="title is-1">dashboard</h1>
            </div>
        </div>
        <div class="columns">
            <div class="column is-full has-text-center">
                <a class="button is-large is-white is-loading"></a>
                <h5 class="subtitle is-5">loading</h5>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            classMossToken.validate({
                callback: {
                    done: function() {
                        // Bind the lgout button
                        $('#buttonLogout').on('click', function() {
                            classMossToken.delete.refresh({
                                callback: {
                                    done: function() {
                                        document.location.href = '/app/login';
                                    },
                                    fail: function(objectSettings, objectResponse) {
                                        var objectResponseJSON = objectResponse.responseJSON;

                                        if (void 0 !== objectResponseJSON.reasons) {
                                            notification({
                                                type: 'danger',
                                                title: 'Error',
                                                content: $.map(objectResponseJSON.reasons, function(arrayReasons, keyReason) {
                                                    return {
                                                        heading: keyReason,
                                                        items: arrayReasons
                                                    };
                                                })
                                            });
                                        }
                                    }
                                }
                            })
                        });

                        classMossHelpers.hide.when('Auth').show.when('Auth');
                    },
                    fail: function() {
                        document.location.href = '/app/login';
                    }
                }
            });
        });
    </script>
@endsection