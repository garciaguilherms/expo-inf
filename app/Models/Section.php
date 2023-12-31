<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany as HasMany;

class Section extends Model
{
    protected $table = 'sections';
    protected $fillable = [
        'title',
        'description',
        'tags',
        'created_by',
        'creator_id',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
