<?php
namespace Mossengine\Core\v1\Middleware;

use Mossengine\Core\v1\Traits\SlimContainerTrait;

/**
 * Class CoreMiddleware
 * @package Mossengine\Core\v1\Middleware
 */
class CoreMiddleware
{

    use SlimContainerTrait;

    /**
     * DefaultMiddleware constructor.
     * @param $slim
     */
    public function __construct($slim) {

    }

    /**
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function __invoke($request, $response, $next) {
        // Opportunity to perform pre request checks.

        // Proceed with the request
        $response = $next($request, $response);

        // Opportunity to perform after request work.
        return $response;
    }
}