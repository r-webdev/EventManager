<?php
namespace Mossengine\Core\v1\Components;

use Mossengine\Core\v1\Objects\Returnable;

/**
 * Class CoreComponent
 * @package Mossengine\Core\v1\Components
 */
class CoreComponent
{
    use \Mossengine\Core\v1\Traits\SlimContainerTrait;

    /**
     * @var null|Returnable
     */
    private $returnable = null;

    /**
     * CoreComponent constructor.
     * @param Returnable|null $returnable
     */
    public function __construct(Returnable $returnable = null) {

        $this->returnable($returnable instanceof Returnable ? $returnable : new Returnable());
    }

    /**
     * @param Returnable|null $returnable
     * @return null|Returnable
     */
    public function returnable(Returnable $returnable = null) {
        return $this->returnable = (!empty($returnable) ? $returnable : $this->returnable);
    }

    /**
     * This function will return an array with keys for each supported crud action, if false/!array then action not
     * supported else if array then the array is each key supported for parameters and also an array of validation rules
     *
     * @return array
     */
    public function schema()
    {
        return [];
    }
}