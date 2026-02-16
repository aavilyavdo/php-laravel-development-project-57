<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Text extends Model
{
    use SoftDeletes; // Для мягкого удаления

    /**
     * Таблица БД
     */
    protected $table = 'texts';

    /**
     * Поля, доступные для массового заполнения
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'views',
        'author_id',
        'category_id',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'cover_image',
    ];

    /**
     * Поля, которые должны быть скрыты при сериализации
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Атрибуты, которые должны быть приведены к определенным типам
     */
    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'views' => 'integer',
        'is_published' => 'boolean',
    ];

    /**
     * Значения по умолчанию
     */
    protected $attributes = [
        'status' => 'draft',
        'views' => 0,
    ];
}
