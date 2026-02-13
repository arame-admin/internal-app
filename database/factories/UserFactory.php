<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'personal_email' => fake()->unique()->safeEmail(),
            'phone_country_code' => '+91',
            'phone_number' => fake()->phoneNumber(),
            'about_me' => fake()->sentence(),
            'what_i_love_about_job' => fake()->sentence(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'dob' => fake()->date(),
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'marriage_date' => fake()->date(),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'physically_handicapped' => fake()->boolean(),
            'nationality' => fake()->country(),
            'work_email' => fake()->unique()->safeEmail(),
            'work_number' => fake()->phoneNumber(),
            'residence_number' => fake()->phoneNumber(),
            'current_address' => fake()->address(),
            'permanent_address' => fake()->address(),
            'employee_code' => fake()->unique()->randomNumber(4),
            'date_of_joining' => fake()->date(),
            'job_title' => fake()->jobTitle(),
            'department_id' => 1,
            'designation_id' => fake()->numberBetween(1, 10),
            'bu_id' => 1,
            'location_id' => 1,
            'is_active' => fake()->boolean(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role_id' => fake()->randomElement([2, 3]),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
