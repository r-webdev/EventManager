function MossToken(objectProperties) {
    // Define a parent var for accessing this inside other functions
    var parent = this;

    // Ensure the objectProperties are always an object
    objectProperties = void 0 !== objectProperties && typeof objectProperties === 'object' ? objectProperties : {};

    // Define attributes storage
    this.attributes = {
        api: void 0 !== objectProperties.api ? objectProperties.api : null,
        account: (
            void 0 !== objectProperties.cookies
            && void 0 !== objectProperties.cookies.load
            && true === objectProperties.cookies.load
                ? JSON.parse(void 0 !== Cookies.get('account') ? Cookies.get('account') : null)
                : null
        ),
        token: {
            refresh: (
                void 0 !== objectProperties.cookies
                && void 0 !== objectProperties.cookies.load
                && true === objectProperties.cookies.load
                ? JSON.parse(void 0 !== Cookies.get('token.refresh') ? Cookies.get('token.refresh') : null)
                    : null
            ),
            auth: (
                void 0 !== objectProperties.cookies
                && void 0 !== objectProperties.cookies.load
                && true === objectProperties.cookies.load
                    ? JSON.parse(void 0 !== Cookies.get('token.auth') ? Cookies.get('token.auth') : null)
                    : null
            )
        },
    };

    // Define helper functions
    this.helpers = void 0 !== objectProperties.helpers ? objectProperties.helpers : null;

    // Define cookie functions
    this.cookies = {
        clear: function(objectSettings) {
            // Ensure we have a objectSettings object
            objectSettings = parent.helpers.ensure.isObject(objectSettings);

            // update in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.account)) {
                parent.attributes.account = null;
                Cookies.remove('account');
            }

            // update in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.refresh)) {
                parent.attributes.token.refresh = null;
                Cookies.remove('token.refresh');
            }

            // update in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.auth)) {
                parent.attributes.token.auth = null;
                Cookies.remove('token.auth');
            }
        },
        load: function(objectSettings) {
            // Ensure we have a objectSettings object
            objectSettings = parent.helpers.ensure.isObject(objectSettings);

            // load in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.account)) {
                parent.attributes.account = JSON.parse(void 0 !== Cookies.get('account') ? Cookies.get('account') : null);
            }

            // load in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.refresh)) {
                parent.attributes.token.refresh = JSON.parse(void 0 !== Cookies.get('token.refresh') ? Cookies.get('token.refresh') : null);
            }

            // load in the refresh token if requested
            if (parent.helpers.ensure.isTrueOrVoid(objectSettings.auth)) {
                parent.attributes.token.auth = JSON.parse(void 0 !== Cookies.get('token.auth') ? Cookies.get('token.auth') : null);
            }
        },
        update: function(objectSettings) {
            // Ensure we have a objectSettings object
            objectSettings = parent.helpers.ensure.isObject(objectSettings);

            // update in the account if requested
            if (
                (
                    void 0 !== parent.attributes.account
                    && void 0 !== parent.attributes.account.uuid
                    && parent.attributes.account.uuid.length > 0
                )
                && (
                    void 0 !== parent.attributes.token.refresh
                    && void 0 !== parent.attributes.token.refresh.expired_at
                    && moment.utc(parent.attributes.token.refresh.expired_at).isAfter(moment.utc())
                    && void 0 !== parent.attributes.token.refresh.token
                    && parent.attributes.token.refresh.token.length > 0
                )
            ) {
                // update in the account if requested
                if (parent.helpers.ensure.isTrueOrVoid(objectSettings.account)) {
                    Cookies.set('account', parent.attributes.account, {expires: moment.utc(parent.attributes.token.refresh.expired_at).toDate()});
                }

                // update in the refresh token if requested
                if (parent.helpers.ensure.isTrueOrVoid(objectSettings.refresh)) {
                    Cookies.set('token.refresh', parent.attributes.token.refresh, {expires: moment.utc(parent.attributes.token.refresh.expired_at).toDate()});
                }

                // update in the refresh token if requested
                if (
                    parent.helpers.ensure.isTrueOrVoid(objectSettings.auth)
                    && (
                        void 0 !== parent.attributes.token.auth
                        && void 0 !== parent.attributes.token.auth.expired_at
                        && moment.utc(parent.attributes.token.auth.expired_at).isAfter(moment.utc())
                        && void 0 !== parent.attributes.token.auth.token
                        && parent.attributes.token.auth.token.length > 0
                    )
                ) {
                    Cookies.set('token.auth', parent.attributes.token.auth, {expires: moment.utc(parent.attributes.token.auth.expired_at).toDate()});
                }
            }
        }
    };

    // Create a token with credentials
    this.create = function(objectSettings) {
        // Ensure we have a objectSettings object
        objectSettings = parent.helpers.ensure.isObject(objectSettings);

        // Ensure we have an objectSettings callbacks object
        objectSettings.callback = parent.helpers.ensure.isObject(objectSettings.callback);

        // Check for before callback
        if (parent.helpers.check.isFunction(objectSettings.callback.before)) {
            objectSettings.callback.before(objectSettings);
        }

        if (
            void 0 !== parent.attributes.api
            && void 0 !== parent.attributes.api.attributes
            && void 0 !== objectSettings.credentials
            && void 0 !== objectSettings.credentials.email
            && parent.helpers.validate.email(objectSettings.credentials.email)
            && void 0 !== objectSettings.credentials.password
            && parent.helpers.validate.password(objectSettings.credentials.password)
        ) {
            $.ajax({
                url: parent.attributes.api.address() + '/token',
                method: 'POST',
                headers: {
                    'Authorization': 'Basic ' + btoa(objectSettings.credentials.email + ':' + objectSettings.credentials.password)
                },
                data: {
                    name: (void 0 !== objectSettings.name ? objectSettings.name : 'web-token-browser')
                }
            }).done(function(objectResponse) {
                // Update the tokens
                parent.attributes.account = objectResponse.results.account;
                parent.attributes.token.refresh = objectResponse.results.refresh;
                parent.attributes.token.auth = objectResponse.results.auth;

                // Refresh the cookies
                parent.cookies.update();

                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                    objectSettings.callback.done(objectSettings, objectResponse);
                }
            }).fail(function(objectResponse) {
                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                    objectSettings.callback.fail(objectSettings, objectResponse);
                }
            }).always(function() {
                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.always)) {
                    objectSettings.callback.always(objectSettings);
                }
            });
        } else {
            // Check for invalid callback
            if (parent.helpers.check.isFunction(objectSettings.callback.invalid)) {
                objectSettings.callback.invalid(objectSettings, {
                    api: (
                        void 0 !== parent.attributes.api
                        && void 0 !== parent.attributes.api.attributes
                    ),
                    email: (
                        void 0 !== objectSettings.credentials
                        && void 0 !== objectSettings.credentials.email
                        && parent.helpers.validate.email(objectSettings.credentials.email)
                    ),
                    password: (
                        void 0 !== objectSettings.credentials
                        && void 0 !== objectSettings.credentials.password
                        && parent.helpers.validate.password(objectSettings.credentials.password)
                    )
                });
            }
        }
    };

    // Refresh an auth token using the refresh token
    this.refresh = function(objectSettings) {
        // Ensure we have a objectSettings object
        objectSettings = parent.helpers.ensure.isObject(objectSettings);

        // Ensure we have an objectSettings callbacks object
        objectSettings.callback = parent.helpers.ensure.isObject(objectSettings.callback);

        // Check for before callback
        if (parent.helpers.check.isFunction(objectSettings.callback.before)) {
            objectSettings.callback.before(objectSettings);
        }

        if (
            void 0 !== parent.attributes.api
            && void 0 !== parent.attributes.api.attributes
            && void 0 !== parent.attributes.token.refresh
            && void 0 !== parent.attributes.token.refresh.expired_at
            && void 0 !== parent.attributes.token.refresh.token
        ) {
            $.ajax({
                url: parent.attributes.api.address() + '/token/' + parent.attributes.token.refresh.token,
                method: 'GET'
            }).done(function(objectResponse) {
                // Update the tokens
                parent.attributes.token.refresh = objectResponse.results.refresh;
                parent.attributes.token.auth = objectResponse.results.auth;

                // Refresh the cookies
                parent.cookies.update();

                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                    objectSettings.callback.done(objectSettings, objectResponse);
                }
            }).fail(function(objectResponse) {
                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                    objectSettings.callback.fail(objectSettings, objectResponse);
                }
            }).always(function() {
                // Check for done callback
                if (parent.helpers.check.isFunction(objectSettings.callback.always)) {
                    objectSettings.callback.always(objectSettings);
                }
            });
        } else {
            // Check for invalid callback
            if (parent.helpers.check.isFunction(objectSettings.callback.invalid)) {
                objectSettings.callback.invalid(objectSettings, {
                    api: (
                        void 0 !== parent.attributes.api
                        && void 0 !== parent.attributes.api.attributes
                    ),
                    refresh: (
                        void 0 !== parent.attributes.token.refresh
                        && void 0 !== parent.attributes.token.refresh.expired_at
                        && void 0 !== parent.attributes.token.refresh.token
                    )
                });
            }
        }
    };

    // Delete an auth token
    this.delete = {
        refresh: function(objectSettings) {
            // Ensure we have a objectSettings object
            objectSettings = parent.helpers.ensure.isObject(objectSettings);

            // Ensure we have an objectSettings callbacks object
            objectSettings.callback = parent.helpers.ensure.isObject(objectSettings.callback);

            // Check for before callback
            if (parent.helpers.check.isFunction(objectSettings.callback.before)) {
                objectSettings.callback.before(objectSettings);
            }

            if (
                void 0 !== parent.attributes.api
                && void 0 !== parent.attributes.api.attributes
                && void 0 !== parent.attributes.token.refresh
                && void 0 !== parent.attributes.token.refresh.expired_at
                && void 0 !== parent.attributes.token.refresh.token
            ) {
                $.ajax({
                    url: parent.attributes.api.address() + '/token/' + parent.attributes.token.refresh.token,
                    method: 'DELETE'
                }).done(function () {
                    // Update the tokens
                    parent.attributes.token.refresh = null;
                    parent.attributes.token.auth = null;

                    // Clear the cookies but not the refresh
                    parent.cookies.clear();

                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                        objectSettings.callback.done(objectSettings);
                    }
                }).fail(function (objectResponse) {
                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                        objectSettings.callback.fail(objectSettings, objectResponse);
                    }
                }).always(function () {
                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.always)) {
                        objectSettings.callback.always(objectSettings);
                    }
                });
            } else {
                // Check for invalid callback
                if (parent.helpers.check.isFunction(objectSettings.callback.invalid)) {
                    objectSettings.callback.invalid(objectSettings, {
                        api: (
                            void 0 !== parent.attributes.api
                            && void 0 !== parent.attributes.api.attributes
                        ),
                        auth: (
                            void 0 !== parent.attributes.token.auth
                            && void 0 !== parent.attributes.token.auth.expired_at
                            && void 0 !== parent.attributes.token.auth.token
                        )
                    });
                }
            }
        },
        auth: function(objectSettings) {
            // Ensure we have a objectSettings object
            objectSettings = parent.helpers.ensure.isObject(objectSettings);

            // Ensure we have an objectSettings callbacks object
            objectSettings.callback = parent.helpers.ensure.isObject(objectSettings.callback);

            // Check for before callback
            if (parent.helpers.check.isFunction(objectSettings.callback.before)) {
                objectSettings.callback.before(objectSettings);
            }

            if (
                void 0 !== parent.attributes.api
                && void 0 !== parent.attributes.api.attributes
                && void 0 !== parent.attributes.token.auth
                && void 0 !== parent.attributes.token.auth.expired_at
                && void 0 !== parent.attributes.token.auth.token
            ) {
                $.ajax({
                    url: parent.attributes.api.address() + '/token/' + parent.attributes.token.auth.token,
                    method: 'DELETE'
                }).done(function () {
                    // Update the tokens
                    parent.attributes.token.auth = null;

                    // Clear the cookies but not the refresh
                    parent.cookies.clear({
                        refresh: false
                    });

                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                        objectSettings.callback.done(objectSettings);
                    }
                }).fail(function (objectResponse) {
                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                        objectSettings.callback.fail(objectSettings, objectResponse);
                    }
                }).always(function () {
                    // Check for done callback
                    if (parent.helpers.check.isFunction(objectSettings.callback.always)) {
                        objectSettings.callback.always(objectSettings);
                    }
                });
            } else {
                // Check for invalid callback
                if (parent.helpers.check.isFunction(objectSettings.callback.invalid)) {
                    objectSettings.callback.invalid(objectSettings, {
                        api: (
                            void 0 !== parent.attributes.api
                            && void 0 !== parent.attributes.api.attributes
                        ),
                        auth: (
                            void 0 !== parent.attributes.token.auth
                            && void 0 !== parent.attributes.token.auth.expired_at
                            && void 0 !== parent.attributes.token.auth.token
                        )
                    });
                }
            }
        }
    };

    this.validate = function(objectSettings) {
        // Ensure we have a objectSettings object
        objectSettings = parent.helpers.ensure.isObject(objectSettings);

        // Ensure we have an objectSettings callbacks object
        objectSettings.callback = parent.helpers.ensure.isObject(objectSettings.callback);

        // Check for auth and refresh and not expired
        if (
            null !== parent.attributes.account
            && void 0 !== parent.attributes.account.uuid
            && parent.attributes.account.uuid.length > 0
            && null !== parent.attributes.token.refresh
            && void 0 !== parent.attributes.token.refresh.expired_at
            && moment.utc(parent.attributes.token.refresh.expired_at).isAfter(moment.utc())
            && null !== parent.attributes.token.auth
            && void 0 !== parent.attributes.token.auth.expired_at
            && moment.utc(parent.attributes.token.auth.expired_at).isAfter(moment.utc())
        ) {
            // done validation
            if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                objectSettings.callback.done();
            }
        } else if (
            null !== parent.attributes.account
            && void 0 !== parent.attributes.account.uuid
            && parent.attributes.account.uuid.length > 0
            && null !== parent.attributes.token.refresh
            && void 0 !== parent.attributes.token.refresh.expired_at
            && moment.utc(parent.attributes.token.refresh.expired_at).isAfter(moment.utc())
            && null === parent.attributes.token.auth
        ) {
            parent.refresh({
                callback: {
                    done: function () {
                        if (parent.helpers.check.isFunction(objectSettings.callback.done)) {
                            objectSettings.callback.done();
                        }
                    },
                    fail: function () {
                        if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                            objectSettings.callback.fail();
                        }
                    }
                }
            });
        } else {
            // Force a logout
            parent.cookies.clear();

            // failed validation
            if (parent.helpers.check.isFunction(objectSettings.callback.fail)) {
                objectSettings.callback.fail();
            }
        }
    }
}