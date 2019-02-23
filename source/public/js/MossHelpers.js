function MossHelpers() {
    var parent = this;

    // Hiders and Showers
    this.hide = {
        any: function () {
            $('.hideWhenAny').hide();
            return parent;
        },
        when: function(stringWhen) {
            parent.hide.any();
            if (void 0 !== stringWhen) {
                $('.hideWhen' + stringWhen).hide();
            }
            return parent;
        },
        whenNot: function(stringWhenNot) {
            parent.hide.any();
            if (void 0 !== stringWhenNot) {
                $('.hideWhenNot' + stringWhenNot).hide();
            }
            return parent;
        }
    };
    this.show = {
        when: function(stringWhen) {
            parent.hide.any();
            if (void 0 !== stringWhen) {
                $('.showWhen' + stringWhen).show();
            }
            return parent;
        },
        whenNot: function(stringWhenNot) {
            parent.hide.any();
            if (void 0 !== stringWhenNot) {
                $('.showWhenNot' + stringWhenNot).show();
            }
            return parent;
        }
    };

    // Ensurers
    this.ensure = {
        isObject: function(object) {
            // Ensure we have an object
            return void 0 !== object && typeof object === 'object' ? object : {};
        },
        isFunction: function(object) {
            // Ensure we have a function
            return void 0 !== object && typeof object === 'function'
                ? object
                : function() {
                    return null;
                };
        },
        isTrue: function(boolean) {
            // Ensure we have a function
            return void 0 !== boolean && true === boolean;
        },
        isTrueOrVoid: function(boolean) {
            // Ensure we have a function
            return void 0 === boolean || true === boolean;
        },
        isFalse: function(boolean) {
            // Ensure we have a function
            return void 0 !== boolean && true === boolean;
        },
        isFalseOrVoid: function(boolean) {
            // Ensure we have a function
            return void 0 === boolean || true === boolean;
        }
    };

    // Checkers
    this.check = {
        isObject: function(object) {
            // Check we have an object
            return void 0 !== object && typeof object === 'object';
        },
        isFunction: function(object) {
            // Check we have a function
            return void 0 !== object  && typeof object === 'function';
        }
    };

    // Cleanders
    this.clean = {
        all: function(objectSettings) {
            return parent.clean.callback(parent.clean.settings(objectSettings));
        },
        settings: function(objectSettings) {
            // Ensure we have a objectSettings object
            return void 0 !== objectSettings && typeof objectSettings === 'object' ? objectSettings : {};
        },
        callback: function(objectSettings) {
            // Ensure we have a objectSettings.callback object
            objectSettings.callback = void 0 !== objectSettings && void 0 !== objectSettings.callback && typeof objectSettings.callback === 'object' ? objectSettings.callback : {}
            return objectSettings;
        }
    };

    // Validations
    this.validate = {
        email: function(email) {
            let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        password: function(password) {
            return (password.length > 7);
        }
    };
}