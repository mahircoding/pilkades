<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
class DesaProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Desa Suka Maju',
            'district' => 'Kecamatan Makmur',
            'regency' => 'Kabupaten Sejahtera',
            'province' => 'Provinsi Gemilang',
            'postal_code' => '12345',
            'address' => 'Jl. Balai Desa No 1',
            'head_name' => 'Bapak Kepala',
        ];
    }
}
