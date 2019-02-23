<?php
namespace Mossengine\Web\v1\Controllers;

/**
 * Class AppController
 * @package Mossengine\Web\v1\Controllers
 */
class AppController extends BaseController
{

    /**
     * DocumentController constructor.
     * @param $container
     */
    public function __construct($container) {
        // Call the BaseControllers __construct()
        parent::__construct($container, [
            'blade' => true
        ]);
    }
    /**
     * Single Page App
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getApp($request, $response, $args) {
        $stringFeature = (
            in_array(
                ($stringFeature = array_get($args, 'feature', 'login')),
                [
                    'login',
                    'dashboard'
                ]
            )
                ? $stringFeature
                : 'login'
        );

        return $this->blade($response, 'app.1.' . $stringFeature, [
            'inputs' => array_merge(
                (array)$request->getParsedBody(),
                (array)$request->getParams(),
                (array)$args,
                [
                    'feature' => $stringFeature
                ]
            )
        ]);
    }
}