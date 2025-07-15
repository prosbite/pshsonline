<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teacher;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advisers = [
            [
                'last_name' => 'Baloria',
                'middle_name' => 'T.',
                'first_name' => 'Noah',
                'gender' => 'male',
                'email' => 'noahbaloria@alp.com',
            ],
            [
                'last_name' => 'English',
                'middle_name' => 'Teacher',
                'first_name' => 'New',
                'gender' => 'male',
                'email' => 'newenglish@alp.com',
            ],
            [
                'last_name' => 'Cuadrazal',
                'middle_name' => 'C.',
                'first_name' => 'Rea Frechie',
                'gender' => 'female',
                'email' => 'reafrechiecuadrazal@alp.com',
            ],
            [
                'last_name' => 'Abamonga',
                'middle_name' => 'C.',
                'first_name' => 'Maricris',
                'gender' => 'female',
                'email' => 'maricrisabamonga@alp.com',
            ],
            [
                'last_name' => 'Nemeño',
                'middle_name' => 'B.',
                'first_name' => 'Nestor Bong Jr.',
                'gender' => 'male',
                'email' => 'nestorbongjrnemeno@alp.com',
            ],
            [
                'last_name' => 'Llido',
                'middle_name' => 'G.',
                'first_name' => 'Deborah Gwen',
                'gender' => 'female',
                'email' => 'deborahgwenllido@alp.com',
            ],
            [
                'last_name' => 'Nuñez',
                'middle_name' => 'C.',
                'first_name' => 'Nerry',
                'gender' => 'male',
                'email' => 'nerrynunez@alp.com',
            ],
            [
                'last_name' => 'Valencia',
                'middle_name' => 'F.',
                'first_name' => 'Ma.Riza',
                'gender' => 'female',
                'email' => 'ma.rizavalencia@alp.com',
            ],
            [
                'last_name' => 'Sionosa',
                'middle_name' => 'M.',
                'first_name' => 'Glenn',
                'gender' => 'male',
                'email' => 'glennsionosa@alp.com',
            ],
            [
                'last_name' => 'Fernando',
                'middle_name' => 'C.',
                'first_name' => 'Chardy',
                'gender' => 'male',
                'email' => 'chardyfernando@alp.com',
            ],
            [
                'last_name' => 'Payao',
                'middle_name' => 'U.',
                'first_name' => 'Loida Mae',
                'gender' => 'female',
                'email' => 'loidamaepayao@alp.com',
            ],
            [
                'last_name' => 'Fernando',
                'middle_name' => 'C.',
                'first_name' => 'Alvin',
                'gender' => 'male',
                'email' => 'alvinfernando@alp.com',
            ],
            [
                'last_name' => 'Bermoy',
                'middle_name' => 'R.',
                'first_name' => 'Lyndon',
                'gender' => 'male',
                'email' => 'lyndonbermoy@alp.com',
            ],
            [
                'last_name' => 'Fulay',
                'middle_name' => 'P.',
                'first_name' => 'Louren',
                'gender' => 'female',
                'email' => 'lourenfulay@alp.com',
            ],
            [
                'last_name' => 'Garrido',
                'middle_name' => 'P.',
                'first_name' => 'Hannah Elizabeth',
                'gender' => 'female',
                'email' => 'hannahelizabethgarrido@alp.com',
            ],
            [
                'last_name' => 'Club',
                'middle_name' => 'A.',
                'first_name' => 'Football',
                'gender' => 'male',
                'email' => 'footballclub@alp.com',
            ],
            [
                'last_name' => 'Club',
                'middle_name' => 'A.',
                'first_name' => 'Frisbee',
                'gender' => 'male',
                'email' => 'frisbeeclub@alp.com',
            ],
        ];

        \App\Models\User::factory()->create([
            'name' => 'Gret',
            'email' => 'gret@admin.com',
            'password' => 'admin',
            'role' => 'admin',
            'status' => 'active',
        ]);

        foreach ($advisers as $adviser) {
            $user = \App\Models\User::factory()->create([
                'name' => $adviser['first_name'] . ' ' . $adviser['middle_name'] . ' ' . $adviser['last_name'],
                'email' => $adviser['email'],
                'password' => '12345678',
                'role' => 'club adviser',
            ]);
            Teacher::factory()->create([
                'last_name' => $adviser['last_name'],
                'first_name' => $adviser['first_name'],
                'middle_name' => $adviser['middle_name'],
                'gender' => $adviser['gender'],
                'email' => $adviser['email'],
                'user_id' => $user->id,
            ]);
        }
    }
}
