<?php
namespace Mossengine\Core\v1\Objects;

/**
 * Class Returnable
 * @package Mossengine\Core\v1\Objects
 */
class Returnable
{
    /**
     * @var array
     */
    private $bag = null;

    /**
     * Returnable constructor.
     * @param array $bag
     */
    public function __construct(Array $bag = [
        'exceptions' => [],
        'errors' => [],
        'reasons' => [],
        'data' => []
    ]) {
        // Instantiate the Returnable with either defaults or inserted other bag structures
        $this->bag($bag);
    }

    /**
     * @param array $arrayParameters
     * @return array|mixed
     * @throws \Exception
     */
    public function exceptions($arrayParameters = []) {
        array_set($arrayParameters, 'key', 'exceptions' . (array_has($arrayParameters, 'key') ? '.' . array_get($arrayParameters, 'key') : null));

        // Validate the exception as being an exception model
        if (array_has($arrayParameters, 'value') && (!array_get($arrayParameters, 'value') instanceof \Exception)) {
            throw new \Exception('exceptions value needs to be an instance of \Exception');
        }

        return $this->action($arrayParameters);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasExceptions() {
        return !empty($this->exceptions([
            'default' => null
        ]));
    }

    /**
     * @param array $arrayParameters
     * @return array|mixed
     */
    public function errors($arrayParameters = []) {
        array_set($arrayParameters, 'key', 'errors' . (array_has($arrayParameters, 'key') ? '.' . array_get($arrayParameters, 'key') : null));
        return $this->action($arrayParameters);
    }

    /**
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors([
            'default' => null
        ]));
    }

    /**
     * @param array $arrayParameters
     * @return array|mixed
     */
    public function reasons($arrayParameters = []) {
        array_set($arrayParameters, 'key', 'reasons' . (array_has($arrayParameters, 'key') ? '.' . array_get($arrayParameters, 'key') : null));
        return $this->action($arrayParameters);
    }

    /**
     * @return bool
     */
    public function hasReasons() {
        return !empty($this->reasons([
            'default' => null
        ]));
    }

    /**
     * @param array $arrayParameters
     * @return array|mixed
     */
    public function data($arrayParameters = []) {
        array_set($arrayParameters, 'key', 'data' . (array_has($arrayParameters, 'key') ? '.' . array_get($arrayParameters, 'key') : null));
        return $this->action($arrayParameters);
    }

    /**
     * @return bool
     */
    public function hasData() {
        return !empty($this->data([
            'default' => null
        ]));
    }

    /**
     * @param array|null $bag
     * @return array|mixed
     */
    public function bag(Array $bag = null) {
        return $this->bag = (!empty($bag) ? $bag : $this->action());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasFailed() {
        return ($this->hasExceptions() || $this->hasErrors());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function hasSucceeded() {
        return (!$this->hasExceptions() && !$this->hasErrors());
    }

    /**
     * This function is use to get, set, append, merge, reset and replace keys within the returnable data array
     *
     * @param array $arrayParameters
     * @return array|mixed
     */
    private function action($arrayParameters = []) {
        // This key defines for either setting when a value is provided or getting when no value
        $stringKey = array_get($arrayParameters, 'key', null);

        // This mode defines if we are performing some kind of setting [reset, key, append, merge]
        $stringMode = array_get($arrayParameters, 'mode', null);

        // Check if we have a mode
        if (!empty($stringMode)) {
            // Define a value for setting, not needed for reset but null if for set, append or merge
            $mixedValue = array_get($arrayParameters, 'value', null);

            // Switch on which mode to apply
            switch ($stringMode) {
                case 'reset':
                    // Reset the returnable data back to defaults
                    $this->bag = [
                        'exceptions' => [],
                        'errors' => [],
                        'data' => []
                    ];
                    break;
                case 'set':
                    // Set the value at the key on the return able data
                    array_set($this->bag, $stringKey, $mixedValue);
                    break;
                case 'append':
                    // Append the value to the returnable data where the key exists else create a new array there and then append
                    $arrayTemp   = array_get($this->bag, $stringKey, []);
                    $arrayTemp[] = $mixedValue;
                    array_set($this->bag, $stringKey, $arrayTemp);
                    break;
                case 'merge':
                    // Merge the returnable data at a specific key with the value or data
                    array_set($this->bag, $stringKey, array_merge(array_get($this->bag, $stringKey, []), (is_array($mixedValue) ? $mixedValue : [$mixedValue])));
                    break;
            }

            return $this;
        }

        // Return the returnable data at either the key or the whole array while excluding exceptions with not in debug mode
        // Additionall if keys are provided then reduce the entire returnable array to an array of the found keys.
        return (
        !empty($stringKey) || !empty(array_get($arrayParameters, 'keys', null))
            ? (
        empty($stringKey)
            ? array_only($this->bag, array_get($arrayParameters, 'keys', null))
            : array_get($this->bag, $stringKey, array_get($arrayParameters, 'default', null))
        )
            : (
        config('debug.enabled', false)
            ? $this->bag
            : array_merge($this->bag, ['exceptions' => []])
        )
        );
    }
}