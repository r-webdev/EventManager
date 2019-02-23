<?php
namespace Mossengine\Core\v1\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait MetaDataTrait
 * @package Mossengine\Core\v1\Traits
 */
trait MetaDataTrait
{
    /**
     * @param $arrayParameters
     * @return mixed
     *
     * This function gets a key based on a dot path from within the meta json parsed column on the model.
     *
     * old parameter inputs were : $stringPath, $variableDefault = null, $stringModelColumn = 'meta'
     *
     * parameters:
     * column:  the name of the column / property on the model using this trait. [default is 'meta']
     * path:    the full path to the value within a json structure that you wish to get, dot separation will drill down
     *          into the meta data's keys or associated arrays within arrays. [default is null for root level]
     * default: when no value is stored at the path, this value will be returned. [default is null]
     * return:  this can be either 'objects' or 'arrays' and it tells the function to return a payload or either objects
     *          within arrays where applicable or arrays within arrays where applicable. [default is 'arrays']
     */
    public function getMetaData($arrayParameters = []) {
        // Get the parameters from the parameters array
        $stringColumn    = (isset($arrayParameters['column']) && !empty($arrayParameters['column']) ? $arrayParameters['column'] : 'meta');
        $stringPath      = (isset($arrayParameters['path']) && !empty($arrayParameters['path']) ? $arrayParameters['path'] : null);
        $variableDefault = (isset($arrayParameters['default']) ? $arrayParameters['default'] : null);
        $typeReturn      = (isset($arrayParameters['return']) && !empty($arrayParameters['return']) ? $arrayParameters['return'] : 'arrays');

        // Decode the meta data
        $arrayMetaData = json_decode((
        is_subclass_of($this, Model::class)
            ? array_get($this->attributes, $stringColumn, '[]')
            : $this->{$stringColumn}
        ), true);

        // Ensure the meta data is an array
        $arrayMetaData = (null !== $arrayMetaData && is_array($arrayMetaData) ? $arrayMetaData : []);

        // get the value at the path
        $mixedPathValue = array_get($arrayMetaData, $stringPath, $variableDefault);

        // Check the return as either objects or arrays ( being all arrays even when objects )
        if ('objects' === $typeReturn) {
            return json_decode(json_encode($mixedPathValue));
        } else {
            return $mixedPathValue;
        }
    }

    /**
     * @param $arrayParameters
     * @return $this
     *
     * This function sets a value against a key based on a dot path within the meta json parsed column on the model.
     *
     * old parameter inputs were : $stringPath, $variableValue = null, $stringModelColumn = 'meta'
     *
     * parameters:
     * column:  the name of the column / property on the model using this trait. [default is 'meta']
     * path:    the full path to the value within a json structure that you wish to set, dot separation will drill down
     *          into the meta data's keys or associated arrays within arrays. [default is null for root level]
     * value:   the mixed value you are wanting to store at the defined path. [default is null]
     */
    public function setMetaData($arrayParameters) {
        // Get the parameters from the parameters array
        $stringColumn  = (isset($arrayParameters['column']) && !empty($arrayParameters['column']) ? $arrayParameters['column'] : 'meta');
        $stringPath    = (isset($arrayParameters['path']) && !empty($arrayParameters['path']) ? $arrayParameters['path'] : null);
        $variableValue = (isset($arrayParameters['value']) && !empty($arrayParameters['value']) ? $arrayParameters['value'] : null);


        // Decode the meta data
        $arrayMetaData = json_decode((
        is_subclass_of($this, Model::class)
            ? array_get($this->attributes, $stringColumn, '[]')
            : $this->{$stringColumn}
        ), true);

        // Ensure the meta data is an array
        $arrayMetaData = (null !== $arrayMetaData && is_array($arrayMetaData) ? $arrayMetaData : []);

        // Return the key after setting the value
        array_set($arrayMetaData, $stringPath, $variableValue);
        if (is_subclass_of($this, Model::class)) {
            array_set($this->attributes, $stringColumn, json_encode($arrayMetaData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
        } else {
            $this->{$stringColumn} = json_encode($arrayMetaData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }
        return $this;
    }

    /**
     * @param $arrayParameters
     * @return $this
     *
     * This function unsets any values for the key based dot path within the meta json parsed column on the model.
     *
     * old parameter inputs were : $stringPath, $stringModelColumn = 'meta'
     *
     * parameters:
     * column:  the name of the column / property on the model using this trait. [default is 'meta']
     * path:    the full path to the value within a json structure that you wish to unset, dot separation will drill down
     *          into the meta data's keys or associated arrays within arrays. [default is null for root level]
     */
    public function unsetMetaData($arrayParameters) {
        // Get the parameters from the parameters array
        $stringPath   = (isset($arrayParameters['path']) && !empty($arrayParameters['path']) ? $arrayParameters['path'] : null);
        $stringColumn = (isset($arrayParameters['column']) && !empty($arrayParameters['column']) ? $arrayParameters['column'] : 'meta');

        // Decode the meta data
        $arrayMetaData = json_decode((
        is_subclass_of($this, Model::class)
            ? array_get($this->attributes, $stringColumn, '[]')
            : $this->{$stringColumn}
        ), true);

        // Ensure the meta data is an array
        $arrayMetaData = (null !== $arrayMetaData && is_array($arrayMetaData) ? $arrayMetaData : []);

        // Return the key after setting the value
        array_forget($arrayMetaData, $stringPath);
        if (is_subclass_of($this, Model::class)) {
            array_set($this->attributes, $stringColumn, json_encode($arrayMetaData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));
        } else {
            $this->{$stringColumn} = json_encode($arrayMetaData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }
        return $this;
    }
}
