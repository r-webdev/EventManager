<?php
namespace Mossengine\Core\v1\Middleware;

use Mossengine\Core\v1\Models\Account;
use Mossengine\Core\v1\Traits\SlimContainerTrait;

/**
 * Class AccountRequiredMiddleware
 * @package Mossengine\Core\v1\Middleware
 */
class AccountRequiredMiddleware
{

    use SlimContainerTrait;

    /**
     * AccountRequiredMiddleware constructor.
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
        if (!($this->slimAccount() instanceof Account)) {
            // No auth provided
            return returnWithJSON(
                $response,
                false,
                [
                    'auth' => [
                        'Missing or invalid credentials provided'
                    ]
                ],
                [],
                401
            );
        }

        // Proceed with the request
        $response = $next($request, $response);

        // Opportunity to perform after request work.
        return $response;
    }
}