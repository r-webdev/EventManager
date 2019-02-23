<?php
namespace Mossengine\Web\v1\Controllers;

use Philo\Blade\Blade;

/**
 * Class BaseController
 * @package Mossengine\Web\v1\Controllers
 */
class BaseController extends \Mossengine\Core\v1\Controllers\CoreController
{

    /**
     * @var null|Blade
     */
    protected $objectBlade = null;

    /**
     * CoreController constructor.
     * @param $container
     * @param null $arraySettings
     */
    public function __construct($container, $arraySettings = null) {
        // Call the BaseControllers __construct()
        parent::__construct($container, $arraySettings);

        // Check for a settings array
        if (is_array($arraySettings) && count($arraySettings) > 0) {

            // Check for the sessions setting and start the session for the controller.
            // This may be redundant since the bootstrap starts the session also... we may need to keep this and get rid of the bootstrap one later...
            if (array_key_exists('session', $arraySettings) && true === $arraySettings['session']) {
                session_start();
            }

            // Check for the blade setting and instantiate the Blade class with its views and cache directories from the global settings array
            if (array_key_exists('blade', $arraySettings) && true === $arraySettings['blade']) {
                $this->objectBlade = new Blade(config('blade.paths.views'), config('blade.paths.cache'));
            }
        }
    }

    /**
     * This function returns a response for a articular blade with an array of variables for the blade to reference.
     *
     * @param $response
     * @param $stringBladeRoute
     * @param array $arrayWith
     * @return mixed
     */
    protected function blade($response, $stringBladeRoute, $arrayWith = []) {
        return $response->write(
            $this->objectBlade->view()
                ->make($stringBladeRoute)
                ->with($arrayWith)
                ->render()
        );
    }
}