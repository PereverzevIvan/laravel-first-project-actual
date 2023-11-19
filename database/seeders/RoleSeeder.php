<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(
            [
                'name'      =>  'Ivan',
                'email'     =>  'peregh320@gmail.com',
                'password'  =>  Hash::make('12345678'),
                'role'   =>  'moderator'
            ]
        );
        User::create(
            [
                'name'      =>  'Chelik',
                'email'     =>  'example@mail.ru',
                'password'  =>  Hash::make('12345678'),
                'role'   =>  'reader'
            ]
        );
    }
}
