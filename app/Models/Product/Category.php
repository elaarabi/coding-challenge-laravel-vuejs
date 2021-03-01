<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 * @property mixed id
 * @package App\Models
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent'];
    /**
     * @var mixed
     */

    /**
     * @return BelongsTo
     */
    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent');
    }

    /**
     * Get Children
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent');
    }
}
