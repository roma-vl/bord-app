<?php

namespace App\Models\Adverts;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed $type
 * @property array $variants
 */
class Attribute extends Model
{
    public const string TYPE_STRING = 'string';

    public const string TYPE_INTEGER = 'integer';

    public const string TYPE_FLOAT = 'float';

    public const string TYPE_BOOLEAN = 'boolean';

    protected $table = 'advert_attributes';

    public $timestamps = false;

    protected $fillable = ['name', 'type', 'is_required', 'default', 'variants', 'sort'];

    protected $casts = [
        'variants' => 'array',
    ];

    public static function typesList(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_INTEGER,
            self::TYPE_FLOAT,
            self::TYPE_BOOLEAN,
        ];
    }

    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }

    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }

    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    public function isBoolean(): bool
    {
        return $this->type === self::TYPE_BOOLEAN;
    }

    public function isSelect(): bool
    {
        return count($this->variants) > 0;
    }
}
