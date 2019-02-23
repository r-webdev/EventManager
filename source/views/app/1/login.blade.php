@extends('app.1.core')

@section('sectionTitleContent')
    @parent - App : Login
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">

                <article id="articleForm" class="message is-dark" style="display: none;">
                    <div class="message-header">
                        <p>Account - Login</p>
                    </div>
                    <div class="message-body has-text-left">
                        <div class="field">
                            <label class="label">Account Email</label>
                            <div class="control">
                                <input id="inputEmail" class="input" type="text" placeholder="someone@website.domain.over.here">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Password</label>
                            <div class="control">
                                <input id="inputPassword" class="input" type="password" placeholder="**********">
                            </div>
                        </div>
                        <div class="field has-text-right">
                            <button id="buttonSubmit" class="button is-dark">Login</button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            classMossToken.validate({
                callback: {
                    done: function() {
                        document.location.href = '/app/dashboard';
                    },
                    fail: function() {
                        classMossToken.cookies.clear();

                        // Bind the input changes
                        $('#inputEmail').on('keyup', function() {
                            var inputEmail = $(this);
                            inputEmail.removeClass('is-danger').siblings().remove();
                            if (void 0 === inputEmail || !classMossToken.helpers.validate.email(inputEmail.val())) {
                                inputEmail.addClass('is-danger').parent().append(
                                    $('<p class="has-text-danger"/>')
                                        .text('Invalid Account Email, must be a valid email address')
                                );
                            }
                        });
                        $('#inputPassword').on('keyup keypress', function(e) {
                            if (13 === e.which) {
                                $('#buttonSubmit').click();
                            } else {
                                var inputPassword = $(this);
                                inputPassword.removeClass('is-danger').siblings().remove();
                                if (void 0 === inputPassword || !classMossToken.helpers.validate.password(inputPassword.val())) {
                                    inputPassword.addClass('is-danger').parent().append(
                                        $('<p class="has-text-danger"/>')
                                            .text('Invalid Password Length, must be at least 8 characters in length or greater')
                                    );
                                }
                            }
                        });

                        // Bind the submit button to send the ajax request to the api endpoints
                        $('#buttonSubmit').on('click', function() {
                            var buttonSubmit, inputEmail, inputPassword;

                            buttonSubmit = $(this);
                            inputEmail = $('#inputEmail');
                            inputPassword = $('#inputPassword');

                            // Lock down the inputs
                            buttonSubmit.attr('disabled', 'disabled');
                            inputEmail.attr('disabled', 'disabled');
                            inputPassword.attr('disabled', 'disabled');

                            if (
                                classMossToken.helpers.validate.email(inputEmail.val())
                                && classMossToken.helpers.validate.password(inputPassword.val())
                            ) {
                                classMossToken.create({
                                    credentials: {
                                        email: inputEmail.val(),
                                        password: inputPassword.val()
                                    },
                                    objects: {
                                        buttonSubmit: buttonSubmit,
                                        inputEmail: inputEmail,
                                        inputPassword: inputPassword
                                    },
                                    callback: {
                                        done: function(objectSettings, objectResponse) {
                                            $('#articleForm').hide();

                                            if (void 0 !== objectResponse.reasons) {
                                                notification({
                                                    type: 'success',
                                                    title: 'Authenticated',
                                                    content: $.map(objectResponse.reasons, function(arrayReasons) {
                                                        return {
                                                            items: arrayReasons
                                                        };
                                                    })
                                                });
                                            }

                                            setTimeout(function() {
                                                // Redirect to index with refresh and auth token
                                                document.location.href = '/app/dashboard';
                                            }, 2500)
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

                                            // unlock the inputs
                                            objectSettings.objects.buttonSubmit.removeAttr('disabled');
                                            objectSettings.objects.inputEmail.removeAttr('disabled');
                                            objectSettings.objects.inputPassword.removeAttr('disabled');
                                        },
                                        invalid: function(objectSettings, objectReasons) {
                                            // Generate errors by triggering the keychange
                                            objectSettings.objects.inputEmail.trigger('keyup');
                                            objectSettings.objects.inputPassword.trigger('keyup');

                                            // Lock down the form
                                            objectSettings.objects.buttonSubmit.removeAttr('disabled');
                                            objectSettings.objects.inputEmail.removeAttr('disabled');
                                            objectSettings.objects.inputPassword.removeAttr('disabled');
                                        }
                                    }
                                });
                            } else {
                                console.error('invalid inputs');

                                // Generate errors by triggering the keychange
                                inputEmail.trigger('keyup');
                                inputPassword.trigger('keyup');

                                // Lock down the form
                                buttonSubmit.removeAttr('disabled');
                                inputEmail.removeAttr('disabled');
                                inputPassword.removeAttr('disabled');
                            }
                        });

                        classMossHelpers.hide.whenNot('Auth').show.whenNot('Auth');

                        $('#articleForm').show();
                    }
                }
            });
        });
    </script>
@endsection