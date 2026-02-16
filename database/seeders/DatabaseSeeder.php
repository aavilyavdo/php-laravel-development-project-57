<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Text;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Количество создаваемых пользователей
     */
    protected int $count = 50;

    public function run(): void
    {
        // Отключаем внешние ключи для очистки
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('Начинаем создание пользователей...');

        // 1. Создаем основного администратора
        $admin = User::factory()->admin()->create([
            'email_verified_at' => now(),
        ]);
        $this->command->info('Создан администратор: ' . $admin->email);

        // 2. Создаем тестового пользователя
        $testUser = User::factory()->testUser()->create([
            'email_verified_at' => now(),
        ]);
        $this->command->info('Создан тестовый пользователь: ' . $testUser->email);

        // 3. Создаем пользователей с русскими именами
        $russianCount = (int)($this->count * 0.3); // 30% русских пользователей
        User::factory($russianCount)->russian()->create();
        $this->command->info("Создано {$russianCount} пользователей с русскими именами");

        // 4. Создаем обычных пользователей
        $regularCount = $this->count - $russianCount - 2; // минус админ и тестовый
        User::factory($regularCount)->create();
        $this->command->info("Создано {$regularCount} обычных пользователей");

        // 5. Создаем пользователей с неподтвержденным email
        $unverifiedCount = 5;
        User::factory($unverifiedCount)->unverified()->create();
        $this->command->info("Создано {$unverifiedCount} пользователей с неподтвержденным email");

        // Выводим статистику
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        
        $this->command->info('=================================');
        $this->command->info("Всего пользователей: {$totalUsers}");
        $this->command->info("С подтвержденным email: {$verifiedUsers}");
        $this->command->info("Без подтверждения: " . ($totalUsers - $verifiedUsers));
        
        // Примеры созданных пользователей
        $this->command->info('=================================');
        $this->command->info('Примеры созданных пользователей:');
        User::inRandomOrder()->limit(5)->get()->each(function($user) {
            $this->command->info(" - {$user->name} <{$user->email}>");
        });
    }
}
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
