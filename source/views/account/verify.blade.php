@extends('core.full')

@section('sectionTitleContent')
    @parent - Account - Verify Email
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">

                <article id="articleResults" class="message" style="display: none;">
                    <div class="message-header">
                        <p>Account - Verify Email</p>
                    </div>
                    <div class="message-body has-text-left">
                    </div>
                </article>

                <article id="articleForm" class="message is-dark">
                    <div class="message-header">
                        <p>Account - Verify Email</p>
                    </div>
                    <div class="message-body has-text-left">
                        <div class="field">
                            <label class="label">Account ID</label>
                            <div class="control">
                                <input id="inputUuid" class="input" type="text" placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX" value="{{ $uuid }}">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Verify Token</label>
                            <div class="control">
                                <input id="inputToken" class="input" type="text" placeholder="XXXXXXXXXXXXXXXXXXXX..." value="{{ $token }}">
                            </div>
                        </div>
                        <div class="field has-text-right">
                            <button id="buttonSubmit" class="button is-dark">Submit Verification</button>
                        </div>
                    </div>
                </article>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Bind the input changes
            $('#inputUuid').on('keyup', function() {
                let inputUuid = $(this);
                inputUuid.removeClass('is-danger').siblings().remove();
                if (void 0 === inputUuid || inputUuid.val().length !== 36) {
                    inputUuid.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid Account ID Length, must be 36 characters in length')
                    );
                }
            });
            $('#inputToken').on('keyup', function() {
                let inputToken = $(this);
                inputToken.removeClass('is-danger').siblings().remove();
                if (void 0 === inputToken || inputToken.val().length < 36) {
                    inputToken.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid Verify Token Length, must be at least 36 characters in length or greater')
                    );
                }
            });

            // Bind the submit button to send the ajax request to the api endpoints
            $('#buttonSubmit').on('click', function() {
                let buttonSubmit, inputUuid, inputToken;

                buttonSubmit = $(this);
                inputUuid = $('#inputUuid');
                inputToken = $('#inputToken');

                // Lock down the inputs
                buttonSubmit.attr('disabled', 'disabled');
                inputUuid.attr('disabled', 'disabled');
                inputToken.attr('disabled', 'disabled');

                if (
                    inputUuid.val().length === 36
                    && inputToken.val().length >= 36
                ) {
                    $.ajax({
                        url: '{{ localAddress() }}/api/v1/account/verify/' + inputUuid.val() + '/' + inputToken.val(),
                        method: 'GET',
                        parent: {
                            buttonSubmit: buttonSubmit,
                            inputUuid: inputUuid,
                            inputToken: inputToken
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
                        this.parent.inputUuid.removeAttr('disabled');
                        this.parent.inputToken.removeAttr('disabled');
                    });
                } else {
                    // Generate errors by triggering the keychange
                    inputUuid.trigger('keyup');
                    inputToken.trigger('keyup');

                    // Lock down the form
                    buttonSubmit.removeAttr('disabled');
                    inputUuid.removeAttr('disabled');
                    inputToken.removeAttr('disabled');
                }
            });
        });
    </script>
@endsection