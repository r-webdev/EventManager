<?php
namespace Mossengine\Core\v1\Models;

use Mossengine\Core\v1\Traits\MetaDataTrait;

/**
 * Class Account
 * @package Mossengine\Core\v1\Models
 */
class Account extends Core
{
    use MetaDataTrait;

    /**
     * Hide these fields from output
     */
    protected $hidden = [
        'email',
        'verify',
        'forgot',
        'password',
        'meta',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * allow these fields to be fillable
     */
    protected $fillable = [
        'email',
        'verify',
        'forgot',
        'password'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'md5email'
    ];

    /**
     * @return mixed
     */
    public function getMd5emailAttribute() {
        return md5($this->attributes['email']);
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * @param $value
     * @return bool
     */
    public function validatePassword($value) {
        return (true === password_verify($value, $this->attributes['password']));
    }

    /**
     * @return bool
     */
    public function isVerified() {
        return ('verified' === $this->verify);
    }

    /**
     * @return bool
     */
    public function isSuperUser() {
        return (
            true === $this->getMetaData([
                'path' => 'permissions.superuser',
                'default' => false
            ])
        );
    }

    /**
     * @return object
     */
    public function transform() {
        return (object) [
            'uuid' => $this->getKey(),
            'type' => $this->type,
            'md5email' => $this->md5email
        ];
    }
}