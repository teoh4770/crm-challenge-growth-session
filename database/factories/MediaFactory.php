<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'file_name' => $this->faker->word() . '.pdf',
            'mime_type' => $this->faker->mimeType(),
            'path' => $this->faker->filePath(),
            'disk' => 'local',
            'file_hash' => $this->faker->sha256(),
            'collection' => null,
            'size' => $this->faker->numberBetween(1000, 10000),
        ];
    }
}
