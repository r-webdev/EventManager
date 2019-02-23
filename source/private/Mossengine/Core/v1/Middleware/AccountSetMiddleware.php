<?php
namespace Mossengine\Core\v1\Middleware;

use Mossengine\Core\v1\Models\Account;
use Mossengine\Core\v1\Models\Token;
use Mossengine\Core\v1\Traits\SlimContainerTrait;

/**
 * Class AccountSetMiddleware
 * @package Mossengine\Core\v1\Middleware
 */
class AccountSetMiddleware
{

    use SlimContainerTrait;

    /**
     * AccountSetMiddleware constructor.
     * @param $slim
     */
    public function __construct($slim)
    {

    }

    /**
     * @param $request
     * @param $response
     * @param $next
     * @return mixed
     */
    public function __invoke($request, $response, $next)
    {
        try {

            // Check for and extract the Auth Header
            $authorization = $request->hasHeader('Authorization') ? $request->getHeader('Authorization')[0] : null;

            if (!empty($authorization)) {
                // Provided with some auth
                switch (explode(' ', $authorization)[0]) {
                    case 'Bearer':
                        // The other part of the string should be the token proper.
                        $tokenCandidate = explode(' ', $authorization)[1];

                        // We need to see if this is valid and belongs to a user.
                        $modelToken = Token::whereToken($tokenCandidate)
                            ->with(['account', 'refreshToken'])
                            ->whereHas('refreshToken', function($query) {
                                $query->whereNull('deleted_at');
                            }, '>', 0)
                            ->first();

                        // Did we find a valid token?
                        if ($modelToken instanceof Token && !$modelToken->isExpired() && $modelToken->account instanceof Account) {
                            $this->slimAccount($modelToken->account);
                        }
                        break;
                    case 'Basic':
                        // We have been provided with Basic credentials, apparently
                        $decodedString = base64_decode(explode(' ', $authorization)[1]);
                        $credentials   = explode(':', $decodedString);

                        // Find the user specified in the Basic Auth
                        $modelAccount = Account::whereEmail($credentials[0])
                            ->first();

                        if ($modelAccount instanceof Account && $modelAccount->validatePassword($credentials[1])) {
                            // If we passed the above check, the credentials should be good
                            $this->slimAccount($modelAccount);
                        }

                        break;
                }

                // Check if account is verified
                if (!($this->slimAccount() instanceof Account) || !$this->slimAccount()->isVerified()) {
                    $this->slimAccountUnset();
                }
            }

            // Proceed with the request
            $response = $next($request, $response);

            // Opportunity to perform after request work.
            return $response;

        } catch (\Exception $e) {
            return returnWithJSON($response, false, ['Something went wrong trying to authenticate this request', $e->getMessage()], [], 500);
        }
    }
}