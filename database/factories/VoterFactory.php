<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class VoterFactory extends Factory
{
    public function definition(): array
    {
        $nik = $this->faker->numerify('################');
        $nikService = app(\App\Services\NikService::class);
        return array_merge($nikService->buildFields($nik), [
            'election_period_id' => \App\Models\ElectionPeriod::factory(),
            'name' => $this->faker->name,
            'gender' => $this->faker->randomElement(['L', 'P']),
            'tps_label' => 'TPS 01',
            'list_status' => \App\Models\Voter::STATUS_DPS,
        ]);
    }
    public function dpt(): static
    {
        return $this->state(fn (array $attributes) => [
            'list_status' => \App\Models\Voter::STATUS_DPT,
        ]);
    }
}
