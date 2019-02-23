@extends('core.full')

@section('sectionTitleContent')
    @parent - Account - Confirm Email
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">

                <article id="articleResults" class="message" style="display: none;">
                    <div class="message-header">
                        <p>Account - Request Confirmation Email</p>
                    </div>
                    <div class="message-body has-text-left">
                    </div>
                </article>

                <article id="articleForm" class="message is-dark">
                    <div class="message-header">
                        <p>Account - Request Confirmation Email</p>
                    </div>
                    <div class="message-body has-text-left">
                        <div class="field">
                            <label class="label">Account Email</label>
                            <div class="control">
                                <input id="inputEmail" class="input" type="text" placeholder="someone@website.domain.over.here" value="{{ $email }}">
                            </div>
                        </div>
                        <div class="field has-text-right">
                            <button id="buttonSubmit" class="button is-dark">Request Confirmation Email</button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </div>
    <script>
        function validateEmail(email) {
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
        $(document).ready(function() {
            // Bind the input changes
            $('#inputEmail').on('keyup', function() {
                let inputEmail = $(this);
                inputEmail.removeClass('is-danger').siblings().remove();
                if (void 0 === inputEmail || !validateEmail(inputEmail.val())) {
                    inputEmail.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid Account Email, must be a valid email address')
                    );
                }
            });

            // Bind the submit button to send the ajax request to the api endpoints
            $('#buttonSubmit').on('click', function() {
                let buttonSubmit, inputEmail;

                buttonSubmit = $(this);
                inputEmail = $('#inputEmail');

                // Lock down the inputs
                buttonSubmit.attr('disabled', 'disabled');
                inputEmail.attr('disabled', 'disabled');

                if (
                    validateEmail(inputEmail.val())
                ) {
                    $.ajax({
                        url: '{{ localAddress() }}/api/v1/account/confirm/' + inputEmail.val(),
                        method: 'GET',
                        parent: {
                            buttonSubmit: buttonSubmit,
                            inputEmail: inputEmail
                        }
                    }).done(function(objectResponse) {
                        $('#articleForm').hide();

                        let articleResults = $('#articleResults');
                        let resultsBody = articleResults.find('.message-body');
                        resultsBody.empty();

                        if (void 0 !== objectResponse.reasons) {
                            $.each(objectResponse.reasons, function (keyReason, arrayReasons) {
                                $.each(arrayReasons, function(index, stringReason) {
                                    resultsBody.append(
                                        $('<p />')
                                            .text(stringReason)
                                    );
                                });
                            });
                        }

                        articleResults
                            .addClass('is-dark')
                            .show()
                            .find('.message-header')
                            .empty()
                            .append(
                                $('<p />')
                                    .text('Success')
                            );
                    }).fail(function(response) {
                        let objectResponse = response.responseJSON;

                        let articleResults = $('#articleResults');
                        let resultsBody = articleResults.find('.message-body');
                        resultsBody.empty();

                        if (void 0 !== objectResponse.reasons) {
                            $.each(objectResponse.reasons, function (keyReason, arrayReasons) {
                                resultsBody.append(
                                    $('<h2 class="subtitle" style="margin: 10px 0px 0px 0px; color: #000;" />')
                                        .text(keyReason)
                                );
                                $.each(arrayReasons, function(index, stringReason) {
                                    resultsBody.append(
                                        $('<p />')
                                            .text(stringReason)
                                    );
                                });
                            });
                        }

                        articleResults
                            .addClass('is-danger')
                            .show()
                            .find('.message-header')
                            .empty()
                            .append(
                                $('<p />')
                                    .text('Error')
                            );

                        // unlock the inputs
                        this.parent.buttonSubmit.removeAttr('disabled');
                        this.parent.inputEmail.removeAttr('disabled');
                    });
                } else {
                    // Generate errors by triggering the keychange
                    inputEmail.trigger('keyup');

                    // Lock down the form
                    buttonSubmit.removeAttr('disabled');
                    inputEmail.removeAttr('disabled');
                }
            });
        });
    </script>
@endsection