@extends('core.full')

@section('sectionTitleContent')
    @parent - Account - Registration
@endsection

@section('sectionBodyContent')
    @parent

    <div class="container has-text-centered">
        <div class="columns">
            <div class="column is-offset-one-quarter is-half">

                <article id="articleResults" class="message" style="display: none;">
                    <div class="message-header">
                        <p>Account - Registration</p>
                    </div>
                    <div class="message-body has-text-left">
                    </div>
                </article>

                <article id="articleForm" class="message is-dark">
                    <div class="message-header">
                        <p>Account - Registration</p>
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
                        <div class="field">
                            <label class="label">Confirm Password</label>
                            <div class="control">
                                <input id="inputConfirm" class="input" type="password" placeholder="**********">
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Legal Agreement</label>
                            <div class="control">
                                <label class="checkbox">
                                    <input id="inputTermsConditionsPrivacyPolicy" type="checkbox" value="accepted">
                                    <span>I accept the <a href="/documents/terms-of-use">terms of use</a> as well as the <a href="/documents/privacy-policy">privacy policy</a>.</span>
                                </label>
                            </div>
                        </div>
                        <div class="field has-text-right">
                            <button id="buttonSubmit" class="button is-dark">Submit Registration</button>
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
            $('#inputPassword').on('keyup', function() {
                let inputPassword = $(this);
                inputPassword.removeClass('is-danger').siblings().remove();
                if (void 0 === inputPassword || inputPassword.val().length < 8) {
                    inputPassword.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid New Password Length, must be at least 8 characters in length or greater')
                    );
                }
            });
            $('#inputConfirm').on('keyup', function() {
                let inputConfirm = $(this);
                inputConfirm.removeClass('is-danger').siblings().remove();
                if (void 0 === inputConfirm || inputConfirm.val() !== $('#inputPassword').val()) {
                    inputConfirm.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid Confirm Password, must match the New Password')
                    );
                }
            });
            $('#inputTermsConditionsPrivacyPolicy').on('change', function() {
                let inputTermsConditionsPrivacyPolicy = $(this);
                inputTermsConditionsPrivacyPolicy.removeClass('is-danger').siblings().remove();

                inputTermsConditionsPrivacyPolicy.parent().append(
                    $('<span />')
                        .html('I accept the <a href="/documents/terms-of-use">terms of use</a> as well as the <a href="/documents/privacy-policy">privacy policy</a>.')
                );

                if (void 0 === inputTermsConditionsPrivacyPolicy || !inputTermsConditionsPrivacyPolicy.is(':checked')) {
                    inputTermsConditionsPrivacyPolicy.addClass('is-danger').parent().append(
                        $('<p class="has-text-danger"/>')
                            .text('Invalid Legal Agreement, you must accept the terms and conditions as well as the privacy policy')
                    );
                }
            });

            // Bind the submit button to send the ajax request to the api endpoints
            $('#buttonSubmit').on('click', function() {
                let buttonSubmit, inputEmail, inputPassword, inputConfirm, inputTermsConditionsPrivacyPolicy;

                buttonSubmit = $(this);
                inputEmail = $('#inputEmail');
                inputPassword = $('#inputPassword');
                inputConfirm = $('#inputConfirm');
                inputTermsConditionsPrivacyPolicy = $('#inputTermsConditionsPrivacyPolicy');

                // Lock down the inputs
                buttonSubmit.attr('disabled', 'disabled');
                inputEmail.attr('disabled', 'disabled');
                inputPassword.attr('disabled', 'disabled');
                inputConfirm.attr('disabled', 'disabled');
                inputTermsConditionsPrivacyPolicy.attr('disabled', 'disabled');

                if (
                    validateEmail(inputEmail.val())
                    && inputPassword.val().length >= 8
                    && inputPassword.val() === inputConfirm.val()
                    && inputTermsConditionsPrivacyPolicy.is(':checked')
                ) {
                    $.ajax({
                        url: '{{ localAddress() }}/api/v1/account',
                        method: 'POST',
                        data: {
                            email: inputEmail.val(),
                            password: inputPassword.val(),
                            confirm: inputConfirm.val(),
                            termsandconditions: inputTermsConditionsPrivacyPolicy.val()
                        },
                        parent: {
                            buttonSubmit: buttonSubmit,
                            inputEmail: inputEmail,
                            inputPassword: inputPassword,
                            inputConfirm: inputConfirm,
                            inputTermsConditionsPrivacyPolicy: inputTermsConditionsPrivacyPolicy
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
                        this.parent.inputPassword.removeAttr('disabled');
                        this.parent.inputConfirm.removeAttr('disabled');
                        this.parent.inputTermsConditionsPrivacyPolicy.removeAttr('disabled');
                    });
                } else {
                    // Generate errors by triggering the keychange
                    inputEmail.trigger('keyup');
                    inputPassword.trigger('keyup');
                    inputConfirm.trigger('keyup');
                    inputTermsConditionsPrivacyPolicy.trigger('change');

                    // Lock down the form
                    buttonSubmit.removeAttr('disabled');
                    inputEmail.removeAttr('disabled');
                    inputPassword.removeAttr('disabled');
                    inputConfirm.removeAttr('disabled');
                    inputTermsConditionsPrivacyPolicy.removeAttr('disabled');
                }
            });
        });
    </script>
@endsection