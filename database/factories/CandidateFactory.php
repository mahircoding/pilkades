<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class CandidateFactory extends Factory
{
    public function definition(): array
    {
        $nik = $this->faker->numerify('################');
        $nikService = app(\App\Services\NikService::class);
        return array_merge($nikService->buildFields($nik), [
            'election_period_id' => \App\Models\ElectionPeriod::factory(),
            'ballot_number' => $this->faker->numberBetween(1, 5),
            'name' => $this->faker->name,
            'birth_place' => $this->faker->city,
            'birth_date' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'address' => $this->faker->address,
            'verification_status' => \App\Models\Candidate::STATUS_SUBMITTED,
        ]);
    }
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => \App\Models\Candidate::STATUS_VERIFIED,
            'verified_by' => \App\Models\User::factory(),
            'verified_at' => now(),
        ]);
    }
}
