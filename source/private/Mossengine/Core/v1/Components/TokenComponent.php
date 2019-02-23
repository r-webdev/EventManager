<?php
namespace Mossengine\Core\v1\Components;

use Carbon\Carbon;
use Mossengine\Core\v1\Models\Token;
use Mossengine\Core\v1\Objects\Returnable;
use Mossengine\Poignant\Poignant;

/**
 * Class TokenComponent
 * @package Mossengine\Core\v1\Components
 */
class TokenComponent extends CoreComponent
{
    /**
     * This function will return an array with keys for each supported crud action, if false/!array then action not
     * supported else if array then the array is each key supported for parameters and also an array of validation rules
     *
     * @return array
     */
    public function schema()
    {
        return [
            'schema' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/token'
                ],
                'input' => [
                    'validation' => []
                ]
            ],
            'select' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/tokens'
                ],
                'input' => [
                    'validation' => $this->select()
                ]
            ],
            'create' => [
                'http' => [
                    'method' => 'POST',
                    'uri' => '/token',
                    'status' => [
                        'success' => 201
                    ]
                ],
                'input' => [
                    'validation' => $this->create()
                ]
            ],
            'refresh' => [
                'http' => [
                    'method' => 'GET',
                    'uri' => '/token/{refresh_token}'
                ],
                'input' => [
                    'validation' => $this->refresh()
                ]
            ],
            'delete' => [
                'http' => [
                    'method' => 'DELETE',
                    'uri' => '/token/{any_token}',
                    'status' => [
                        'success' => 204
                    ]
                ],
                'input' => [
                    'validation' => $this->delete()
                ]
            ]
        ];
    }

    /**
     * This is the read function to be call when wanting to get a single record for the model type
     *
     * @param array $arrayParameters
     * @return array|Returnable|null
     */
    public function select(Array $arrayParameters = [])
    {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withType(function($condition) {
                    return $condition->in(['auth', 'refresh']);
                })
                ->withName()
                ->withSortBy(function($condition) {
                    return $condition->in(['name']);
                })
                ->withSortDirection(function($condition) {
                    return $condition->in(['asc', 'desc']);
                })
                ->withTake(function($condition) {
                    return $condition->numeric();
                })
                ->withSkip(function($condition) {
                    return $condition->numeric();
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->select())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $queryToken = Token::select()
                        ->whereNull('deleted_at')
                        ->where('account_uuid', $this->slimAccount()->uuid);

                    if (array_has($arrayParameters, 'type')) {
                        $queryToken->where('type', array_get($arrayParameters, 'type'));
                    }

                    if (array_has($arrayParameters, 'name')) {
                        $queryToken->where('name', 'like', '%' . array_get($arrayParameters, 'name') . '%');
                    }

                    $collectionTokens = $queryToken
                        ->orderBy(array_get($arrayParameters, 'sortby', 'name'), array_get($arrayParameters, 'sortdirection', 'asc'))
                        ->skip(array_get($arrayParameters, 'skip', config('defaults.eloquent.skip', 0)))->take(array_get($arrayParameters, 'take', config('defaults.eloquent.take', 30)))
                        ->get();

                    if (!$collectionTokens->isEmpty()) {
                        $this->returnable()->reasons([
                            'mode' => 'append',
                            'key' => 'token',
                            'value' => 'successfully selected records from database'
                        ])->data([
                            'mode' => 'set',
                            'value' => array_map(
                                function($modelToken) {
                                    return [
                                        'uuid' => $modelToken->uuid,
                                        'name' => $modelToken->name,
                                        'type' => $modelToken->type,
                                        'created_at' => $modelToken->created_at->toDateTimeString(),
                                        'updated_at' => $modelToken->updated_at->toDateTimeString(),
                                        'expired_at' => $modelToken->expired_at->toDateTimeString()
                                    ];
                                },
                                $collectionTokens->all()
                            )
                        ]);
                    } else {
                        $this->returnable()->reasons([
                            'mode' => 'append',
                            'key' => 'token',
                            'value' => 'no records from database'
                        ]);
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->errors([
                'mode' => 'append',
                'key' => 'token',
                'value' => 'failed to select records'
            ])->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable();
    }

    /**
     * This is the create function to be called to create a record for the model type
     *
     * @param array $arrayParameters
     * @return array|Returnable|null
     */
    public function create(Array $arrayParameters = [])
    {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
                ->withName(function($condition) {
                    return $condition->required()
                        ->length('>', 7);
                })
                ->bag();
        }

        try {
            // Allow parameters to set the returnable object else component returnable is used.
            $this->returnable(array_get($arrayParameters, 'returnable', null));

            /** @noinspection PhpParamsInspection */
            Poignant::create($this->create())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelTokenRefresh = new Token([
                        'type' => 'refresh',
                        'name' => array_get($arrayParameters, 'name'),
                        'account_uuid' => $this->slimAccount()->uuid,
                    ]);

                    try {
                        if (!$modelTokenRefresh->save()) {
                            $this->returnable()->errors([
                                'mode' => 'append',
                                'key' => 'token',
                                'value' => 'unable authenticate with database'
                            ]);
                        } else {

                            $modelTokenAuth = new Token([
                                'type' => 'auth',
                                'name' => array_get($arrayParameters, 'name'),
                                'account_uuid' => $modelTokenRefresh->account_uuid,
                                'refresh_token_uuid' => $modelTokenRefresh->uuid
                            ]);


                            try {
                                if (!$modelTokenAuth->save()) {
                                    $this->returnable()->errors([
                                        'mode' => 'append',
                                        'key' => 'token',
                                        'value' => 'unable to authenticate'
                                    ]);
                                } else {

                                    $this->returnable()->reasons([
                                        'mode' => 'append',
                                        'key' => 'token',
                                        'value' => 'successfully authenticated'
                                    ])->data([
                                        'mode' => 'set',
                                        'value' => [
                                            'account' => [
                                                'uuid' => $this->slimAccount()->uuid,
                                                'email' => $this->slimAccount()->email,
                                                'type' => $this->slimAccount()->type,
                                                'created_at' => $this->slimAccount()->created_at,
                                                'updated_at' => $this->slimAccount()->updated_at
                                            ],
                                            'auth' => [
                                                'uuid' => $modelTokenAuth->uuid,
                                                'type' => $modelTokenAuth->type,
                                                'name' => $modelTokenAuth->name,
                                                'refresh_token_uuid' => $modelTokenAuth->refresh_token_uuid,
                                                'token' => $modelTokenAuth->token,
                                                'created_at' => $modelTokenAuth->created_at->toDateTimeString(),
                                                'updated_at' => $modelTokenAuth->updated_at->toDateTimeString(),
                                                'expired_at' => $modelTokenAuth->expired_at->toDateTimeString()
                                            ],
                                            'refresh' => [
                                                'uuid' => $modelTokenRefresh->uuid,
                                                'type' => $modelTokenRefresh->type,
                                                'name' => $modelTokenRefresh->name,
                                                'token' => $modelTokenRefresh->token,
                                                'created_at' => $modelTokenRefresh->created_at->toDateTimeString(),
                                                'updated_at' => $modelTokenRefresh->updated_at->toDateTimeString(),
                                                'expired_at' => $modelTokenRefresh->expired_at->toDateTimeString()
                                            ]
                                        ]
                                    ]);
                                }
                            } catch (\Exception $e) {
                                $this->returnable()->errors([
                                    'mode' => 'append',
                                    'key' => 'token',
                                    'value' => 'failed to authenticate'
                                ])->exceptions([
                                    'mode' => 'append',
                                    'value' => $e
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        $this->returnable()->errors([
                            'mode' => 'append',
                            'key' => 'token',
                            'value' => 'failed authentication'
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
                'key' => 'token',
                'value' => 'failed to authenticate with database'
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
    public function refresh(Array $arrayParameters = [])
    {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
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
            Poignant::create($this->refresh())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelTokenRefresh = Token::where('type', 'refresh')
                        ->whereNull('deleted_at')
                        ->whereToken(array_get($arrayParameters, 'token'))
                        ->where('expired_at', '>', Carbon::now()->toDateTimeString())
                        ->first();

                    if (!($modelTokenRefresh instanceof Token)) {
                        $this->returnable()->errors([
                            'mode' => 'append',
                            'key' => 'token',
                            'value' => 'unable to locate record in database'
                        ]);
                    } else {
                        $modelTokenAuth = $modelTokenRefresh->refreshAuthToken();

                        if (!($modelTokenAuth instanceof Token)) {
                            $this->returnable()->errors([
                                'mode' => 'append',
                                'key' => 'token',
                                'value' => 'unable to verify new record for database'
                            ]);
                        } else {
                            try {
                                if (!$modelTokenAuth->save()) {
                                    $this->returnable()->errors([
                                        'mode' => 'append',
                                        'key' => 'token',
                                        'value' => 'unable to save new record to database'
                                    ]);
                                } else {
                                    $this->returnable()->reasons([
                                        'mode' => 'append',
                                        'key' => 'auth',
                                        'value' => 'successfully saved new record to database'
                                    ])->data([
                                        'mode' => 'set',
                                        'value' => [
                                            'auth' => [
                                                'uuid' => $modelTokenAuth->uuid,
                                                'type' => $modelTokenAuth->type,
                                                'name' => $modelTokenAuth->name,
                                                'refresh_token_uuid' => $modelTokenAuth->refresh_token_uuid,
                                                'token' => $modelTokenAuth->token,
                                                'created_at' => $modelTokenAuth->created_at->toDateTimeString(),
                                                'updated_at' => $modelTokenAuth->updated_at->toDateTimeString(),
                                                'expired_at' => $modelTokenAuth->expired_at->toDateTimeString()
                                            ],
                                            'refresh' => [
                                                'uuid' => $modelTokenRefresh->uuid,
                                                'type' => $modelTokenRefresh->type,
                                                'name' => $modelTokenRefresh->name,
                                                'token' => $modelTokenRefresh->token,
                                                'created_at' => $modelTokenRefresh->created_at->toDateTimeString(),
                                                'updated_at' => $modelTokenRefresh->updated_at->toDateTimeString(),
                                                'expired_at' => $modelTokenRefresh->expired_at->toDateTimeString()
                                            ]
                                        ]
                                    ]);
                                }
                            } catch (\Exception $e) {
                                $this->returnable()->errors([
                                    'mode' => 'append',
                                    'key' => 'token',
                                    'value' => 'failed to save new record to database'
                                ])->exceptions([
                                    'mode' => 'append',
                                    'value' => $e
                                ]);
                            }
                        }
                    }
                    return true;
                });
        } catch (\Exception $e) {
            $this->returnable()->errors([
                'mode' => 'append',
                'key' => 'token',
                'value' => 'failed to update record'
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
    public function delete(Array $arrayParameters = [])
    {
        // Check for parameters, no parameters means schema check
        if (empty($arrayParameters)) {
            return Poignant::create()
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
            Poignant::create($this->refresh())
                ->onFail($arrayParameters, function($mixedValidation) {
                    $this->returnable()->errors([
                        'mode' => 'set',
                        'value' => $mixedValidation
                    ]);
                    return false;
                })
                ->onPass($arrayParameters, function($mixedValidation) use ($arrayParameters) {
                    $modelToken = Token::whereToken(array_get($arrayParameters, 'token'))
                        ->whereNull('deleted_at')
                        ->first();

                    if (!($modelToken instanceof Token)) {
                        $this->returnable()->errors([
                            'mode' => 'append',
                            'key' => 'token',
                            'value' => 'unable to locate record in database'
                        ]);
                    } else {
                        try {
                            $modelToken->expireTokenNow();

                            if (!$modelToken->deleteTokenNow(true)) {
                                $this->returnable()->errors([
                                    'mode' => 'append',
                                    'key' => 'token',
                                    'value' => 'unable to delete record from database'
                                ]);
                            }
                        } catch (\Exception $e) {
                            $this->returnable()->errors([
                                'mode' => 'append',
                                'key' => 'token',
                                'value' => 'failed to delete record from database'
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
                'key' => 'token',
                'value' => 'failed to delete record'
            ])->exceptions([
                'mode' => 'append',
                'value' => $e
            ]);
        }

        // return the returnable
        return $this->returnable();
    }
}