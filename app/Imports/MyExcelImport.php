<?php

namespace App\Imports;

use App\Models\persona;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class MyExcelImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection($rows)
    {
        foreach ($rows as $row) {
            persona::create([
                'name' => $row[0],
                'email' => $row[1],
                'phone' => $row[2]
            ]);
        }
    }
}