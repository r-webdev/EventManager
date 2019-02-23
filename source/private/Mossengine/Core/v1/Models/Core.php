<?php
namespace Mossengine\Core\v1\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Core
 * @package Mossengine\Core\v1\Models
 */
class Core extends Model
{
    use \Mossengine\Core\v1\Traits\MetaDataTrait;

    /**
     * Default boot method to inherit
     */
    protected static function boot() {
        // Handle any parent boot stuff
        parent::boot();

        // Perform creating logic
        static::creating(function ($model) {

            // When creating a new instance of a class extended from this model
            // Generate a new uuid and assign it to the model
            if ('uuid' === $model->primaryKey) {
                $model->attributes['uuid'] = \Ramsey\Uuid\Uuid::uuid4();
            }
        });
    }

    /**
     * Hide these fields from output
     */
    protected $hidden = [
        'meta'
    ];

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    protected $softDeletes = true;

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @param $array
     */
    public function setMetaAttribute($array) {
        $this->setMetaData([
            'value' => $array
        ]);
    }

    /**
     * @return mixed
     */
    public function getMetaAttribute() {
        return $this->getMetaData();
    }

    /**
     * This method returns a 'random' base16 string of the requested length.
     * Should be somewhat collision resistant, fine for use with expiring tokens
     *
     * defaults to 64 character token
     *
     * @param int $length
     * @return string
     */
    public static function generateRandomToken($length = 64) {
        return bin2hex(openssl_random_pseudo_bytes($length / 2));
    }
}