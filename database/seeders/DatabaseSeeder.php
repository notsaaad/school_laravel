<?php

namespace Database\Seeders;


use App\Models\role;
use App\Models\setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // role::create([
        //     'name' => 'كل الصلاحيات',
        //     "permissions" => '["users","roles","home"]'
        // ]);

        User::factory()->create([
            'name' => 'Eslam ahmed',
            'email' => 'admin@gmail.com',
            'password' => Hash::make("admin@gmail.comadmin@gmail.com"),
            "active" => "1",
            "role" => "admin",
            "role_id" => 1,
        ]);







        // setting::insert([
        //     [
        //         "key" => "website_ar_name",
        //         "value" => "مدرستي"
        //     ],
        //     [
        //         "key" => "website_en_name",
        //         "value" => "My School"
        //     ],
        //     [
        //         "key" => "logo",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "meta_description_ar",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "meta_description_en",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "facebook",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "instagram",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "twitter",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "tiktok",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "youtube",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "linkedin",
        //         "value" => ""
        //     ],
        //     [
        //         "key" => "service_expenses",
        //         "value" => "1.25"
        //     ],


        // ]);
    }
}
