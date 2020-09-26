<?php

namespace Database\Factories;

use App\Models\{
    Organization,
    OrganizationSetting
};

use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'org_name'                => $this->faker->company,
            'active'                  => true,
            'organization_setting_id' => OrganizationSetting::factory()
        ];
    }
}
