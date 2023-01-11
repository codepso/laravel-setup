<?php

namespace Codepso\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $action
 * @property string $description
 * @property string $model
 * @property integer $model_id
 * @property string $created_at
 * @property string $updated_at
 * @method static Log create($attr)
 */
class Log extends Model
{
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'action', 'description', 'model', 'model_id', 'created_at', 'updated_at'];
}
