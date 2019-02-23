<?php
// Define a dump die function
if (!function_exists('dd'))
{
    function dd()
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        echo '</pre>';
        die;
    }
}

if (!function_exists('dj'))
{
    function dj()
    {
        header('Content-Type: application/json');
        die(json_encode(func_get_args()));
    }
}


if (!function_exists('filterInputData'))
{
    function filterInputData($arrayParameters = []) {
        // Check if we want to return associative array or object
        $boolAssociative = ('array' === array_get($arrayParameters, 'return', 'array'));

        // Get the input data based on the detection of json and if we want associative or object
        $arrayInputData = array_get($arrayParameters, 'input.data', []);

        // Get the possible list of allow keys
        $arrayKeysAllow = array_get($arrayParameters, 'keys.allow', null);

        // If we have a valid allow list then reduce down to the allow keys
        if (!empty($arrayKeysAllow)) {
            // Create a temp return cleansed array
            $arrayInputDataCleansed = [];

            // Loop through the allow keys setting them into the cleansed array from the original uncleansed array.
            foreach ($arrayKeysAllow as $stringKey) {
                if (array_has($arrayInputData, $stringKey)) {
                    array_set($arrayInputDataCleansed, $stringKey, array_get($arrayInputData, $stringKey));
                }
            }

            // Update the input data array to be the cleansed array.
            $arrayInputData = $arrayInputDataCleansed;

            // Unset the temp cleansed array and the allow keys array
            unset($arrayInputDataCleansed, $arrayKeysAllow);
        }

        // Get the possible block keys array
        $arrayKeysBlock = array_get($arrayParameters, 'keys.block', null);

        // If we have a valid block keys array then remove any of the blocked keys from the return data
        if (is_array($arrayKeysBlock) && !empty($arrayKeysWhitelist)) {
            // Loop through the block keys removing them from the return array
            foreach ($arrayKeysBlock as $stringKey) {
                array_forget($arrayInputData, $stringKey);
            }
            unset($arrayKeysBlock);
        }

        // Return the array or object which could be null
        return ($boolAssociative && empty($arrayInputData) ? [] : $arrayInputData);
    }
}

// define a function that obtains some debuggin attributes for when debug is enabled and is requested on endpoints for returned payloads.
// all returns should contain this function as it wont return any debug unless debug mode is on, so its harmless as long as env is ensured.
if (!function_exists('getDebugArray'))
{
    function getDebugArray($arrayExceptions = [])
    {
        return (config('debug.enabled', false) ? [
            'system' => [
                'version' => config('app.version'),
                'memory' => ((memory_get_peak_usage(true) / 1024) / 1024) . ' MB',
                'execution' => round(microtime(true) - array_get($GLOBALS['config'], 'stats.microtime', microtime(true)), 2) . ' ms'
            ],
            'exceptions' => array_map(function(\Exception $exception) {
                return [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'trace' => $exception->getTrace()
                ];
            }, $arrayExceptions)
        ] : [
            'system' => [
                'version' => config('app.version')
            ]
        ]);
    }
}

// This function is global as various points of returned payloads may need to be a json response and as such we use this function where ever its possible to has a response object and want to return json.
// Could be a candidate for slim to handle this but we want to control the payload structure.
if (!function_exists('returnWithJSON'))
{
    function returnWithJSON($response, $boolSuccess = false, $arrayReasons = null, $arrayResults = null, $intStatusCode = 500, $arrayExceptions = [])
    {
        return $response->withJson([
                'success' => $boolSuccess,
                'reasons' => $arrayReasons,
                'results' => $arrayResults
            ] + getDebugArray($arrayExceptions),
            $intStatusCode);
    }
}

// Simple wrapper for the getenv that makes getting values for keys easier and without failure on non existence as well as a defult in a keys absense.
if (!function_exists('env'))
{
    function env($key, $default = null)
    {
        if (array_key_exists($key, $_ENV)) {
            return getenv($key);
        }
        return $default;
    }
}

// simple named function for getting configuration settings from the $arrayGlobal
if (!function_exists('config'))
{
    function config($key, $default = null)
    {
        return array_get($GLOBALS['config'], $key, $default);
    }
}

// Not 100% sure what this was for again... but I think its to help support getting variables from the _SERVER array without returning sensative ENV settings.
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $stringKey             = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers["$stringKey"] = $value;
            }
        }
        return $headers;
    }
}

// This function will return a construction of the host configs to deliver a hostname based on env config.
if (!function_exists('localAddress')) {
    function localAddress() {
        return config('app.host.protocol') . '://' . (!empty(config('app.host.sub')) ? config('app.host.sub') . '.' : '') . config('app.host.domain');
    }
}

// This function is important to ensure we are getting the true IP address of the visitor and not the load balancer.
// ALWAYS use this function to obtain the users IP address and if performing any restrictions based on IP then abort if 0.0.0.0 is returned.
if (!function_exists('remoteAddress')) {
    function remoteAddress() {
        try {
            return (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && !empty($_SERVER['HTTP_CF_CONNECTING_IP']) && false !== inet_pton($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
        } catch (Exception $e) {
            return '0.0.0.0';
        }
    }
}

// Data functions
if (!function_exists('dataObject')) {
    function dataObject($data) {
        return (is_string($data) ? json_decode($data) : json_decode(json_encode($data)));
    }
}
if (!function_exists('dataArray')) {
    function dataArray($data) {
        return (is_string($data) ? json_decode($data, true) : json_decode(json_encode($data), true));
    }
}