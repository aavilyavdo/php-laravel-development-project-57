<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Text;
use App\Models\User;
use Carbon\Carbon;

class TextSeeder extends Seeder
{
    public function run()
    {
        // Очистка таблицы перед заполнением (опционально)
        Text::truncate();

        // Получаем всех пользователей
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('Сначала создайте пользователей!');
            return;
        }

        // Создаем тексты для каждого пользователя
        foreach ($users as $user) {
            Text::create([
                'user_id' => $user->id,
                'title' => 'Первый текст пользователя ' . $user->name,
                'slug' => 'pervyy-tekst-polzovatelya-' . $user->id,
                'content' => 'Это содержание первого текста пользователя. Здесь может быть любой текст, который вы хотите добавить в базу данных для тестирования.',
                'excerpt' => 'Краткое описание первого текста',
                'status' => 'published',
                'views' => rand(0, 1000),
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
