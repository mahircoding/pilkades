<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class ElectionPeriodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Pilkades 2026',
            'election_date' => now()->addMonths(2),
            'status' => \App\Models\ElectionPeriod::STATUS_DRAFT,
            'is_active' => false,
        ];
    }
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => \App\Models\ElectionPeriod::STATUS_ACTIVE,
            'is_active' => true,
        ]);
    }
}
