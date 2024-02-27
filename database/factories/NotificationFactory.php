<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => '1',
            'type' => 'notification_type',
            'notifiable_type' => 'user',
            'notifiable_id' => User::factory()->create()->id,
            'data' => 'data',
        ];
    }
}
