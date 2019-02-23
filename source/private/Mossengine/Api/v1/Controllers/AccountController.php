<?php
namespace Mossengine\Api\v1\Controllers;

use Mossengine\Core\v1\Components\AccountComponent;

/**
 * Class AccountController
 * @package Mossengine\Api\v1\Controllers
 */
class AccountController extends BaseController
{

    /**
     * DefaultController constructor.
     * @param $container
     */
    public function __construct($container) {
        // Call the BaseControllers __construct()
        parent::__construct($container, [
            'components' => [
                'account' => new AccountComponent() // tell the base controller we want this component
            ]
        ]);
    }
}