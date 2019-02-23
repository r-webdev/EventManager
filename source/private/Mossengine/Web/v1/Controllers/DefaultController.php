<?php
namespace Mossengine\Web\v1\Controllers;

/**
 * Class DefaultController
 * @package Mossengine\Web\v1\Controllers
 */
class DefaultController extends BaseController
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
    public function getDefault($request, $response, $args) {
        return $this->blade($response, 'default.index', [
            'collectionGromments' => null
        ]);
    }

    /**
     * Error 400
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function get400($request, $response, $args) {
        return $this->blade($response, 'error.400', []);
    }

    /**
     * Error 404
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function get404($request, $response, $args) {
        return $this->blade($response, 'error.404', []);
    }

    /**
     * Error 500
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function get500($request, $response, $args) {
        return $this->blade($response, 'error.500', []);
    }
}