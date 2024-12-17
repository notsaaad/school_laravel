<?php

namespace App\Imports;

use App\Models\product;
use App\Models\stage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductsImport implements ToCollection
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


                $stage = stage::where('name', $row[2])->first();

                if ($stage == null) {
                    throw new \Exception("المرحلة في الصف" .   " " . ($index + 2) .    " " . "غير مضافة علي السيستم");
                }

                // try {
                    product::create([
                        'name' => $row[0],
                        'gender' => match ($row[1]) {
                            "ذكر" => "boy",
                            "أنثى" => "girl",
                            "ذكر او أنثي" => "both",
                        },

                        'stage_id' => $stage->id,

                        'price' => $row[3],
                        'sell_price' => $row[4],
                        'stock' => $row[5],


                    ]);
                // } catch (\Throwable $th) {
                //     throw new \Exception("هناك خطأ في السطر رقم " . ($index + 2));
                // }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // Rethrow the exception to be caught by the controller
            throw new \Exception($th->getMessage());
        }
    }
}
