<?php

namespace Database\Factories;

use App\Models\{
    OrganizationSetting,
    User
};

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrganizationSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'point_of_contact_id' => 1
        ];
    }
}
