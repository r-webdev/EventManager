<?php
namespace Mossengine\Core\v1\Models;

use Carbon\Carbon;

/**
 * Class Token
 * @package Mossengine\Core\v1\Models
 */
class Token extends Core
{

    /**
     * Default boot method to inherit
     */
    protected static function boot() {
        // Handle any parent boot stuff
        parent::boot();

        // Perform creating logic
        static::creating(function ($model) {
            // Always need a token
            $model->attributes['token'] = self::generateRandomToken();

            // Set the expiration for the token depending on whether it is a refresh token or an auth token
            if ($model->attributes['type'] === 'refresh') {
                $model->attributes['expired_at'] = Carbon::now()->addHours(config('defaults.token.refresh.expiration', 24))->toDateTimeString();
            } else {
                $model->attributes['expired_at'] = Carbon::now()->addHours(config('defaults.token.auth.expiration', 1))->toDateTimeString();
            }
        });

    }

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'token',
        'type',
        'account_uuid',
        'refresh_token_uuid',
        'meta'
    ];

    /**
     * Hide these fields from output
     */
    protected $hidden = [
        'token',
        'meta'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expired_at',
        'deleted_at'
    ];

    /**
     * This method immediately invalidates the token it is called on
     *
     * @param bool $boolSaveNow
     * @return bool|$this
     */
    public function expireTokenNow($boolSaveNow = false) {
        $this->expired_at = Carbon::now();
        if ('refresh' === $this->type) {
            $this->authTokens()->update(['expired_at' => Carbon::now()]);
        }
        if ($boolSaveNow) {
            return $this->save();
        }
        return $this;
    }

    /**
     * This method immediately soft deletes the token it is called on
     *
     * @param bool $boolSaveNow
     * @return bool|$this
     */
    public function deleteTokenNow($boolSaveNow = false) {
        $this->deleted_at = Carbon::now();
        if ('refresh' === $this->type) {
            $this->authTokens()->update(['deleted_at' => Carbon::now()]);
        }
        if ($boolSaveNow) {
            return $this->save();
        }
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authTokens() {
        return $this->hasMany(Token::class, 'refresh_token_uuid', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function refreshToken() {
        return $this->belongsTo(Token::class, 'refresh_token_uuid', 'uuid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account() {
        return $this->belongsTo(Account::class, 'account_uuid', 'uuid');
    }

    /**
     * This method returns a boolean indicating whether or not the current token should be considered expired
     * tokens with a null expiration date will never expire.
     *
     * @return boolean
     */
    public function isExpired() {
        return (!empty($this->expired_at)) && ($this->expired_at < Carbon::now());
    }

    /**
     * @return bool
     */
    public function isRefreshToken() {
        return $this->type === 'refresh';
    }

    /**
     * This method will invalidate current descendant auth tokens, and generate a new one
     * When this happens, the Refresh token will be given another 24 hours to live.
     *
     * returns false on failure or denied
     * returns the new token on success
     *
     * @return bool|Token
     */
    public function refreshAuthToken() {
        try {
            if (!$this->isRefreshToken()) {
                return false; // Only refresh tokens should be using this method
            }

            // We are going to start by finding the latest descendant token
            $modelLatestToken = self::where('refresh_token_uuid', $this->uuid)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'DESC')
                ->first();

            // Check that the found token is the right one and should be removed
            if (
                $modelLatestToken instanceof Token &&
                $modelLatestToken->expired_at->gt(Carbon::now())
            ) {
                $modelLatestToken->expireTokenNow();
                $modelLatestToken->deleteTokenNow(true);
            }

            // Now that the last token has been killed, we make a new one.
            $modelNewToken = Token::create([
                'token' => self::generateRandomToken(),
                'type' => 'auth',
                'name' => $this->name,
                'account_uuid' => $this->account_uuid
            ]);
            $modelNewToken->refreshToken()->associate($this);
            $modelNewToken->save();

            // This token lives to refresh another day
            $this->expired_at = Carbon::now()->addHours(config('defaults.token.refresh.expiration', 24))->toDateTimeString();
            $this->save();

            // Return the new token to the requester
            return $modelNewToken;
        } catch (\Exception $e) {
            return false;
        }
    }
}