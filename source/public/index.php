<?php
// Use the slim app but reference it as SlimApp to avoid confusion or conflicting name spaces
use SlimFacades\App as SlimApp;

// Include the bootstrap file which sets up many of the core framework things
require_once __DIR__ . '/../private/bootstrap.php';

// Include the routes file for loading in the defined routes
require_once __DIR__ . '/../private/routes.php';

// Run the slim app
SlimApp::run();