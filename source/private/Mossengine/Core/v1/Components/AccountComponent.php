<?php

namespace Mossengine\Core\v1\Components;

use Mossengine\Core\v1\Helpers\EmailHelper;
use Mossengine\Core\v1\Models\Account;
use Mossengine\Core\v1\Objects\Returnable;
use Mossengine\Poignant\Poignant;
use Ramsey\Uuid\Uuid;

/**
 * Class AccountComponent
 * @package Mossengine\Core\v1\Components
 */
class AccountComponent extends CoreComponent
{
    /**
     * @var null|Returnable
     */
    private $returnable = null;

    /**
     * AccountComponent constructor.
     * @param Returnable|null $returnable
     */
    public function __construct(Returnable $returnable = null) {
        parent::__construct();

        $this->returnable($returnable instanceof Returnable ? $returnable : new Returnable());
    }

    /**
     * @param Returnable|null $returnable
     * @return null|Returnable
     */
    public function returnable(Returnable $returnable = null) {
        return $this->returnable = (!empty($returnable) ? $returnable : $this->returnable);
    }

    /**
     * This function will return an array with keys for each supported crud action, if false/!array then action not
     * supported else if array then the array is each key supported for parameters and also an array of validation rules
     *
     * @return array
     * @throws \Exception
     */
    public function schema()
    {
        return [
            'schema' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/account'
                ],
                'input' => [
                    'validation' => []
                ]
            ],
            'register' => [
                'http' => [
                    'method' => 'POST',
                    'uri' => '/account',
                    'status' => [
                        'success' => 201
                    ]
                ],
                'input' => [
                    'validation' => $this->register()
                ]
            ],
            'password' => [
                'http' => [
                    'method' => 'PUT',
                    'uri' => '/account/password'
                ],
                'input' => [
                    'validation' => $this->password()
                ]
            ],
            'confirm' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/account/confirm/{email}'
                ],
                'input' => [
                    'validation' => $this->confirm()
                ]
            ],
            'verify' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/account/verify/{uuid}/{token}'
                ],
                'input' => [
                    'validation' => $this->verify()
                ]
            ],
            'forgot' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/account/forgot/{email}'
                ],
                'input' => [
                    'validation' => $this->forgot()
                ]
            ],
            'reset' => [
                'http' => [
                    'method' => 'POST',
                    'uri' => '/account/reset/{uuid}/{token}'
                ],
                'input' => [
                    'validation' => $this->reset()
                ]
            ]
        ];
    }

    /**
     * @param array $arrayParameters
     * @return Returnable|null
     */
    public function register(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withType(function($condition) {
                    return $condition->required()
                        ->in(['user']);
                })
                ->withEmail(function($condition) {
                    return $condition->required()
                        ->email();
                })
                ->withPassword(function($condition) {
                    return $condition->required()
                        ->length('>', 7);
                })
                ->withConfirm(function($condition) {
                    return $condition->required()
                        ->compare('==', 'password');
                })
                ->withTermsAndConditions(function($condition) {
                    return $condition->required()
                        ->custom(function($value) {
                            return ('accepted' === $value ? true : 'parameter must be the value accepted else you\'re not accepting the privacy policy or terms and conditions');
                        });
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->register())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = new Account([
                        'email' => array_get($arrayParameters, 'email'),
                        'password' => array_get($arrayParameters, 'password'),
                        'verify' => Account::generateRandomToken()
                    ]);

                    try {
                        if (!$modelAccount->save()) {
                            $this->returnable()->errors([
                                'mode' => 'append',
                                'key' => 'account',
                                'value' => 'unable to save new account to database'
                            ]);
                        } else {
                            $this->returnable()->reasons([
                                'mode' => 'append',
                                'key' => 'account',
                                'value' => 'successfully registered account'
                            ])->data([
                                'mode' => 'set',
                                'value' => $modelAccount
                            ]);

                            try {
                                // Send confirmation email
                                EmailHelper::create()
                                    ->to($modelAccount->email)
                                    ->subject(config('app.name.long') . ' - Account Confirmation Email')
                                    ->text([
                                        config('app.name.long') . ' Account Confirmation Email',
                                        null,
                                        'Please browse to the following url to verify your account email',
                                        null,
                                        localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify,
                                        null,
                                        'Kind regards, ' . config('app.name.long')
                                    ])
                                    ->html([
                                        '<html>',
                                        '<head>',
                                        '<title>',
                                        config('app.name.long') . ' Account Confirmation Email',
                                        '</title>',
                                        '</head>',
                                        '<body>',
                                        '<table width="100%">',
                                        '<tr>',
                                        '<td align="left">',
                                        'Please browse to the following url to verify your account email',
                                        '<br /><br />',
                                        '<a href="' . localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify . '">',
                                        localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify,
                                        '</a>',
                                        '<br /><br />',
                                        'Kind regards,',
                                        '<br />',
                                        config('app.name.long'),
                                        '</td>',
                                        '</tr>',
                                        '</table>',
                                        '</body>',
                                        '</html>'
                                    ])
                                    ->send();
                            } catch (\Exception $e) {
                                $this->returnable()->exceptions([
                                    'mode' => 'append',
                                    'value' => $e
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        $this->returnable()->errors([
                            'mode' => 'append',
                            'key' => 'account',
                            'value' => 'failed to save new account to database'
                        ])->exceptions([
                            'mode' => 'append',
                            'value' => $e
                        ]);
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->errors([
                'mode' => 'append',
                'key' => 'account',
                'value' => 'failed to register account'
            ])->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable();
    }

    /**
     * @param array $arrayParameters
     * @return array|Returnable|null
     */
    public function password(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withUuid(function($condition) {
                    return $condition->required()
                        ->uuid();
                })
                ->withPassword(function($condition) {
                    return $condition->required()
                        ->length('>', 7);
                })
                ->withConfirm(function($condition) {
                    return $condition->required()
                        ->compare('==', 'password');
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->password())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = Account::where('uuid', array_get($arrayParameters, 'uuid'))->first();

                    if (!($modelAccount instanceof Account)) {
                        $this->returnable()->errors([
                            'mode' => 'append',
                            'key' => 'account',
                            'value' => 'error while updating password'
                        ]);
                    } else {
                        $modelAccount->fill([
                            'password' => array_get($arrayParameters, 'password')
                        ]);

                        try {
                            if (!$modelAccount->save()) {
                                $this->returnable()->errors([
                                    'mode' => 'append',
                                    'key' => 'account',
                                    'value' => 'unable to updated password'
                                ]);
                            } else {
                                $this->returnable()->reasons([
                                    'mode' => 'append',
                                    'key' => 'account',
                                    'value' => 'successfully updated password'
                                ]);
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->errors([
                                'mode' => 'append',
                                'key' => 'account',
                                'value' => 'failed to updated password'
                            ])->exceptions([
                                'mode' => 'append',
                                'value' => $e
                            ]);
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->errors([
                'mode' => 'append',
                'key' => 'account',
                'value' => 'failed updating password'
            ])->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable();
    }

    /**
     * @param array $arrayParameters
     * @return array|Returnable|null
     * @throws \Exception
     */
    public function confirm(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withEmail(function($condition) {
                    return $condition->required()
                        ->email();
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->confirm())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = Account::where('email', array_get($arrayParameters, 'email'))->first();

                    if ($modelAccount instanceof Account) {
                        $modelAccount->fill([
                            'verify' => Account::generateRandomToken()
                        ]);

                        try {
                            if ($modelAccount->save()) {
                                // Send confirmation email
                                EmailHelper::create()
                                    ->to($modelAccount->email)
                                    ->subject(config('app.name.long') . ' - Account Confirmation Email')
                                    ->text([
                                        config('app.name.long') . ' Account Confirmation Email',
                                        null,
                                        'Please browse to the following url to verify your account email',
                                        null,
                                        localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify,
                                        null,
                                        'Kind regards, ' . config('app.name.long')
                                    ])
                                    ->html([
                                        '<html>',
                                        '<head>',
                                        '<title>',
                                        config('app.name.long') . ' Account Confirmation Email',
                                        '</title>',
                                        '</head>',
                                        '<body>',
                                        '<table width="100%">',
                                        '<tr>',
                                        '<td align="left">',
                                        'Please browse to the following url to verify your account email',
                                        '<br /><br />',
                                        '<a href="' . localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify . '">',
                                        localAddress() . '/account/verify/' . $modelAccount->uuid . '/' . $modelAccount->verify,
                                        '</a>',
                                        '<br /><br />',
                                        'Kind regards,',
                                        '<br />',
                                        config('app.name.long'),
                                        '</td>',
                                        '</tr>',
                                        '</table>',
                                        '</body>',
                                        '</html>'
                                    ])
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->exceptions([
                                'mode' => 'append',
                                'value' => $e
                            ]);
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable()->reasons([
            'mode' => 'append',
            'key' => 'account',
            'value' => 'verification email sent, only if an account exists for this email'
        ]);
    }

    /**
     * @param array $arrayParameters
     * @return array|Returnable|null
     * @throws \Exception
     */
    public function verify(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withUuid(function($condition) {
                    return $condition->required()
                        ->uuid();
                })
                ->withToken(function($condition) {
                    return $condition->required()
                        ->length('=', 64);
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->verify())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = Account::where('uuid', array_get($arrayParameters, 'uuid'))
                        ->whereNotNull('verify')
                        ->where('verify', '!=', 'verified')
                        ->where('verify', array_get($arrayParameters, 'token', 'invalid-' . Account::generateRandomToken()))
                        ->first();

                    if ($modelAccount instanceof Account) {
                        $modelAccount->fill([
                            'verify' => 'verified'
                        ]);

                        try {
                            if ($modelAccount->save()) {
                                // Send confirmation email
                                EmailHelper::create()
                                    ->to($modelAccount->email)
                                    ->subject(config('app.name.long') . ' - Account Email Verified')
                                    ->text([
                                        config('app.name.long') . ' Account Email Verified',
                                        null,
                                        'Your account email has been successfully verified, you can now proceed to login.',
                                        null,
                                        'Kind regards, ' . config('app.name.long')
                                    ])
                                    ->html([
                                        '<html>',
                                        '<head>',
                                        '<title>',
                                        config('app.name.long') . ' Account Email Verified',
                                        '</title>',
                                        '</head>',
                                        '<body>',
                                        '<table width="100%">',
                                        '<tr>',
                                        '<td align="left">',
                                        'Your account email has been successfully verified, you can now proceed to login.',
                                        '<br /><br />',
                                        'Kind regards,',
                                        '<br />',
                                        config('app.name.long'),
                                        '</td>',
                                        '</tr>',
                                        '</table>',
                                        '</body>',
                                        '</html>'
                                    ])
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->exceptions([
                                'mode' => 'append',
                                'value' => $e
                            ]);
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable()->reasons([
            'mode' => 'append',
            'key' => 'account',
            'value' => 'verification token accepted, only if an account exists for this email'
        ]);
    }

    /**
     * @param array $arrayParameters
     * @return array|Returnable|null
     * @throws \Exception
     */
    public function forgot(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withEmail(function($condition) {
                    return $condition->required()
                        ->email();
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->forgot())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = Account::where('email', array_get($arrayParameters, 'email'))->first();

                    if ($modelAccount instanceof Account) {
                        $modelAccount->fill([
                            'forgot' => Account::generateRandomToken()
                        ]);

                        try {
                            if ($modelAccount->save()) {
                                // Send confirmation email
                                EmailHelper::create()
                                    ->to($modelAccount->email)
                                    ->subject(config('app.name.long') . ' - Account Forgot Password Reset')
                                    ->text([
                                        config('app.name.long') . ' Account Forgot Password Reset',
                                        null,
                                        'Please browse to the following url to reset the password for your account',
                                        null,
                                        localAddress() . '/account/reset/' . $modelAccount->uuid . '/' . $modelAccount->forgot,
                                        null,
                                        'Kind regards, ' . config('app.name.long')
                                    ])
                                    ->html([
                                        '<html>',
                                        '<head>',
                                        '<title>',
                                        config('app.name.long') . ' Account Forgot Password Reset',
                                        '</title>',
                                        '</head>',
                                        '<body>',
                                        '<table width="100%">',
                                        '<tr>',
                                        '<td align="left">',
                                        'Please browse to the following url to reset the password for your account',
                                        '<br /><br />',
                                        '<a href="' . localAddress() . '/account/reset/' . $modelAccount->uuid . '/' . $modelAccount->forgot . '">',
                                        localAddress() . '/account/reset/' . $modelAccount->uuid . '/' . $modelAccount->forgot,
                                        '</a>',
                                        '<br /><br />',
                                        'Kind regards,',
                                        '<br />',
                                        config('app.name.long'),
                                        '</td>',
                                        '</tr>',
                                        '</table>',
                                        '</body>',
                                        '</html>'
                                    ])
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->exceptions([
                                'mode' => 'append',
                                'value' => $e
                            ]);
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable()->reasons([
            'mode' => 'append',
            'key' => 'account',
            'value' => 'forgot email sent, only if an account exists for this email'
        ]);
    }

    /**
     * @param array $arrayParameters
     * @return array|Returnable|null
     * @throws \Exception
     */
    public function reset(Array $arrayParameters = []) {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withUuid(function($condition) {
                    return $condition->required()
                        ->uuid();
                })
                ->withToken(function($condition) {
                    return $condition->required()
                        ->length('=', 64);
                })
                ->withPassword(function($condition) {
                    return $condition->required()
                        ->length('>', 7);
                })
                ->withConfirm(function($condition) {
                    return $condition->required()
                        ->compare('==', 'password');
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->reset())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelAccount = Account::where('uuid', array_get($arrayParameters, 'uuid'))
                        ->whereNotNull('forgot')
                        ->where('forgot', array_get($arrayParameters, 'token', 'invalid-' . Account::generateRandomToken()))
                        ->first();

                    if ($modelAccount instanceof Account) {
                        $modelAccount->fill([
                            'forgot' => null,
                            'password' => array_get($arrayParameters, 'password', 'invalid-' . Uuid::uuid4()->toString())
                        ]);

                        try {
                            if ($modelAccount->save()) {
                                // Send confirmation email
                                EmailHelper::create()
                                    ->to($modelAccount->email)
                                    ->subject(config('app.name.long') . ' - Account Password Changed')
                                    ->text([
                                        config('app.name.long') . ' Account Password Changed',
                                        null,
                                        'Please note that you have successfully reset your password using the forgot password reset process, If you did not initiate and/or completed this process then please secure your email account immediately and reset your password again.',
                                        null,
                                        'Kind regards, ' . config('app.name.long')
                                    ])
                                    ->html([
                                        '<html>',
                                        '<head>',
                                        '<title>',
                                        config('app.name.long') . ' Account Password Changed',
                                        '</title>',
                                        '</head>',
                                        '<body>',
                                        '<table width="100%">',
                                        '<tr>',
                                        '<td align="left">',
                                        'Please note that you have successfully reset your password using the forgot password reset process, If you did not initiate and/or completed this process then please secure your email account immediately and reset your password again.',
                                        '<br /><br />',
                                        'Kind regards,',
                                        '<br />',
                                        config('app.name.long'),
                                        '</td>',
                                        '</tr>',
                                        '</table>',
                                        '</body>',
                                        '</html>'
                                    ])
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->exceptions([
                                'mode' => 'append',
                                'value' => $e
                            ]);
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable()->reasons([
            'mode' => 'append',
            'key' => 'account',
            'value' => 'We\'ve processed your password reset and if the details you have provided are correct then you can now proceed to login with your new password.'
        ]);
    }
}