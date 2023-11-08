<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany as HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $fillable = [
        'title',
        'description',
        'image',
        'visibility',
        'author_id',
        'section_id',
        'created_by',
    ];

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'project_sections', 'project_id', 'section_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function authors(): HasMany
    {
        return $this->hasMany(Author::class, 'project_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'project_id');
    }

    public function rating(): HasMany
    {
        return $this->hasMany(Rating::class, 'project_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
