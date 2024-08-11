<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Divisi;

class EmployeeSeeder extends Seeder
{
    public function run()
    {

        $employees = [
            [
                'name' => 'John Doe',
                'phone' => '081234567890',
                'position' => 'Software Engineer',
                'image' => 'john_doe.jpg',
                'division_id' => 1,
            ],
            // Tambahkan data dummy lainnya
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}

