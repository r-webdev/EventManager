<?php
namespace Mossengine\Api\v1\Controllers;

use Mossengine\Core\v1\Components\TokenComponent;

/**
 * Class TokenController
 * @package Mossengine\Api\v1\Controllers
 */
class TokenController extends BaseController
{

    /**
     * TokenController constructor.
     * @param $container
     */
    public function __construct($container) {
        // Call the BaseControllers __construct()
        parent::__construct($container, [
            'components' => [
                'token' => new TokenComponent() // tell the base controller we want this component
            ]
        ]);
    }
}