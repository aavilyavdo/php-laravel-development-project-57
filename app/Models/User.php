<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
      /**
     * Получить все тексты пользователя
     * Связь один-ко-многим
     */
    public function texts()
    {
        return $this->hasMany(Text::class);
    }

    /**
     * Получить только опубликованные тексты пользователя
     */
    public function publishedTexts()
    {
        return $this->hasMany(Text::class)->published();
    }

    /**
     * Тексты, которые пользователь добавил в избранное
     * Связь многие-ко-многим
     */
    public function favoriteTexts()
    {
        return $this->belongsToMany(Text::class, 'favorites', 'user_id', 'text_id')
                    ->withTimestamps()
                    ->withPivot('created_at');
    }

    /**
     * Проверка, является ли пользователь автором текста
     */
    public function isAuthorOf(Text $text): bool
    {
        return $this->id === $text->user_id;
    }
}
}
