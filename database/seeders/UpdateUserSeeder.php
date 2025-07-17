<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advisers = [
            [ 'id' => 2, 'first_name' => 'Noah', 'middle_name' => 'T.', 'last_name' => 'Baloria', 'email' => 'nbaloria@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'n23tk'],
            [ 'id' => 4, 'first_name' => 'Rea Frechie', 'middle_name' => 'C.', 'last_name' => 'Cuadrazal', 'email' => 'rcuadrazal@crc.pshs.edu.ph', 'gender' => 'female', 'password' => '5azbf'],
            [ 'id' => 5, 'first_name' => 'Jestoni', 'middle_name' => '', 'last_name' => 'Villareiz', 'email' => 'jvillareiz@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'mzbwi'],
            [ 'id' => 6, 'first_name' => 'Nestor "Bong" Jr.', 'middle_name' => 'B.', 'last_name' => 'Neme\u00f1o', 'email' => 'nbnemeno@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'qsl1n'],
            [ 'id' => 7, 'first_name' => 'Deborah Gwen', 'middle_name' => 'G.', 'last_name' => 'Llido', 'email' => 'dllido@crc.pshs.edu.ph', 'gender' => 'female', 'password' => '8n2ax'],
            [ 'id' => 8, 'first_name' => 'Nerry', 'middle_name' => 'C.', 'last_name' => 'Nu\u00f1ez', 'email' => 'nnunez@crc.pshs.edu.ph', 'gender' => 'male', 'password' => '39tpy'],
            [ 'id' => 9, 'first_name' => 'Ma. Riza', 'middle_name' => 'F.', 'last_name' => 'Valencia', 'email' => 'rvalencia@crc.pshs.edu.ph', 'gender' => 'female', 'password' => 'byar7'],
            [ 'id' => 10, 'first_name' => 'Glenn', 'middle_name' => 'M.', 'last_name' => 'Sionosa', 'email' => 'gsionosa@crc.pshs.edu.ph', 'gender' => 'male', 'password' => '0uxmf'],
            [ 'id' => 11, 'first_name' => 'Chardy', 'middle_name' => 'C.', 'last_name' => 'Fernando', 'email' => 'ccfernando@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'h13ja'],
            [ 'id' => 12, 'first_name' => 'Loida Mae', 'middle_name' => 'U.', 'last_name' => 'Payao', 'email' => 'laungab@crc.pshs.edu.ph', 'gender' => 'female', 'password' => 'kge3q'],
            [ 'id' => 13, 'first_name' => 'Alvin', 'middle_name' => 'C.', 'last_name' => 'Fernando', 'email' => 'afernando@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'wxirf'],
            [ 'id' => 14, 'first_name' => 'Lyndon', 'middle_name' => 'R.', 'last_name' => 'Bermoy', 'email' => 'lbermoy@crc.pshs.edu.ph', 'gender' => 'male', 'password' => 'u7l6e'],
            [ 'id' => 15, 'first_name' => 'Louren', 'middle_name' => 'P.', 'last_name' => 'Fulay', 'email' => 'lfulay@crc.pshs.edu.ph', 'gender' => 'female', 'password' => 'pfwco'],
            [ 'id' => 16, 'first_name' => 'Hannah Elizabeth', 'middle_name' => 'P.', 'last_name' => 'Garrido', 'email' => 'hgarrido@crc.pshs.edu.ph', 'gender' => 'female', 'password' => 't5mdu'],
            [ 'id' => 20, 'first_name' => 'Gilbert', 'middle_name' => '', 'last_name' => 'Alerta', 'email' => 'galerta@crc.pshs.edu.ph', 'gender' => 'male', 'password' => '8qnwd'],
        ];

        foreach ($advisers as $adviser) {
            $user = User::find($adviser['id']);

            if ($user) {
                $fullName = trim($adviser['first_name'] . ' ' . $adviser['middle_name'] . ' ' . $adviser['last_name']);
                $user->name = $fullName;
                $user->email = $adviser['email'];
                $user->password = bcrypt($adviser['password']);
                $user->save();

                if ($user->teacher) {
                    $user->teacher->update([
                        'first_name' => $adviser['first_name'],
                        'middle_name' => $adviser['middle_name'],
                        'gender' => $adviser['gender'],
                        'last_name' => $adviser['last_name'],
                    ]);
                }
            }
        }
    }
}
