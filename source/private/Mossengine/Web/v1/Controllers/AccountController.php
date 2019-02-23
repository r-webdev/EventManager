<?php
namespace Mossengine\Web\v1\Controllers;

/**
 * Class AccountController
 * @package Mossengine\Web\v1\Controllers
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
            'blade' => true
        ]);
    }

    /**
     * Default
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getRegister($request, $response, $args) {
        return $this->blade($response, 'account.register', []);
    }

    /**
     * Default
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getConfirm($request, $response, $args) {
        return $this->blade($response, 'account.confirm', [
            'email' => array_get($args, 'email', null),
        ]);
    }

    /**
     * Default
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getVerify($request, $response, $args) {
        return $this->blade($response, 'account.verify', [
            'uuid' => array_get($args, 'uuid', null),
            'token' => array_get($args, 'token', null)
        ]);
    }

    /**
     * Default
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getForgot($request, $response, $args) {
        return $this->blade($response, 'account.forgot', [
            'email' => array_get($args, 'email', null),
        ]);
    }

    /**
     * Default
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getReset($request, $response, $args) {
        return $this->blade($response, 'account.reset', [
            'uuid' => array_get($args, 'uuid', null),
            'token' => array_get($args, 'token', null)
        ]);
    }
}