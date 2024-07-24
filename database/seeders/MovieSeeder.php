<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch and insert data from OMDb API
        $start_id = 1270001;
        $key = "f40f2cb3";
        $url = "http://www.omdbapi.com/?apikey=$key&i=tt$start_id";

        $response = Http::get($url);
        $response = json_decode($response);

        if ($response->Response == "True") {
            DB::table('movies')->insert([
                'title' => $response->Title,
                'desc' => $response->Plot,
                'realease' => $response->Released,
                'runtime' => $response->Runtime,
                'quality' => 'hd', // Example value, adjust as necessary
                'type' => 'movie', // Example value, adjust as necessary
                'age' => 18, // Example value, adjust as necessary
                'cover' => 'nothumb.png', // Default value
                'back_photo' => 'nothumb_back.png', // Default value
                'source_link' => 'https://example.com', // Example value, adjust as necessary
                'user_id' => 1, // Example user ID, ensure this user exists
            ]);
        }

        // Generate additional data with Faker and insert into the database
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            DB::table('movies')->insert([
                'title' => $faker->sentence(3), // Generates a random title
                'desc' => $faker->paragraph, // Generates a random description
                'realease' => $faker->date, // Generates a random release date
                'runtime' => $faker->numberBetween(60, 180) . ' min', // Generates a random runtime
                'quality' => $faker->randomElement(['hd', 'fullhd', '2k', '4k']),
                'type' => $faker->randomElement(['movie', 'tvshow']),
                'age' => $faker->numberBetween(1, 18),
                'cover' => 'nothumb.png', // Default value
                'back_photo' => 'nothumb_back.png', // Default value
                'source_link' => $faker->url,
                'user_id' => 1, // Example user ID, ensure this user exists
            ]);
        }
    }
}
