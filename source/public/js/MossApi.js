function MossApi(objectProperties) {
    // Define a parent var for accessing this inside other functions
    var parent = this;

    // Ensure the objectProperties are always an object
    objectProperties = void 0 !== objectProperties && typeof objectProperties === 'object' ? objectProperties : {};

    // Define attributes storage
    this.attributes = {
        url: void 0 !== objectProperties.url ? objectProperties.url : null,
        uri: void 0 !== objectProperties.uri ? objectProperties.uri : '/api/v1'
    };

    // Define helper functions
    this.helpers = void 0 !== objectProperties.helpers ? objectProperties.helpers : null;

    // This function will return the url or set the url if one is defined
    this.url = function(objectSettings) {
        // Ensure we have a objectSettings object
        objectSettings = parent.helpers.clean.settings(objectSettings);

        return parent.attributes.url = void 0 !== objectSettings.url ? objectSettings.url : parent.attributes.url;
    };

    // This function will return the url or set the url if one is defined
    this.uri = function(objectSettings) {
        // Ensure we have a objectSettings object
        objectSettings = parent.helpers.clean.settings(objectSettings);

        return parent.attributes.uri = void 0 !== objectSettings.uri ? objectSettings.uri : parent.attributes.uri;
    };

    // This function will return the url + uri concatenated ( not for setting )
    this.address = function() {
        return parent.url() + parent.uri();
    };
}