<?php
namespace Mossengine\Web\v1\Controllers;

/**
 * Class DocumentController
 * @package Mossengine\Web\v2\Controllers
 */
class DocumentController extends BaseController
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
     * Terms Of Use
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getTermsOfUse($request, $response, $args) {
        return $this->blade($response, 'document.terms-of-use');
    }

    /**
     * Community Guidelines
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getCommunityGuidelines($request, $response, $args) {
        return $this->blade($response, 'document.community-guidelines');
    }

    /**
     * Privacy Policy
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function getPrivacyPolicy($request, $response, $args) {
        return $this->blade($response, 'document.privacy-policy');
    }
}