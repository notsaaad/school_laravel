<?php

namespace App\Imports;

use App\Models\stage;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $rows->shift(); // Skip the header row

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                if (empty(array_filter($row->toArray()))) {
                    continue; // Skip empty rows
                }

                $studentCode = $row[1]; // Assuming the student code is in column 2

                if (User::where('code', $studentCode)->exists()) {
                    throw new \Exception("كود الطالب : " . $studentCode . " مكرر في الصف رقم " . ($index + 2));
                }


                $stage = stage::where('name', $row[3])->first();

                if ($stage == null) {
                    throw new \Exception("المرحلة في الصف" .   " " . ($index + 2) .    " " . "غير مضافة علي السيستم");
                }

                try {
                    User::create([
                        'name' => $row[0],
                        'code' => $row[1],
                        'gender' => match ($row[2]) {
                            "ذكر" => "boy",
                            "أنثى" => "girl",
                        },

                        'stage_id' => $stage ->id,

                        'email' => $row[4],
                        'mobile' => $row[5],

                        'own_package' => match ($row[6]) {
                            "نعم" => "yes",
                            "لا" => "no",
                            default => "no",
                        },
                        "password" => bcrypt("12345678"),
                        "active" => "1"
                    ]);
                } catch (\Throwable $th) {
                    throw new \Exception("هناك خطأ في السطر رقم " . ($index + 2));
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // Rethrow the exception to be caught by the controller
            throw new \Exception($th->getMessage());
        }
    }
}
