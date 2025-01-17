<?php

namespace App;

use App\Models\variant;
use App\Exceptions\PackageValidationException;

trait packageTriat
{
    function checkPackage($package, $variantIds)
    {
        /*********************************************************************************************************************
         * التاكد من المنتجات
         *********************************************************************************************************************/
        $packageProducts  = $package->student_products;
        $productVariants = [];
        foreach ($packageProducts as $product) {
            $productVariants = array_merge($productVariants, $product->variants->pluck('id')->toArray());
        }

        foreach ($variantIds as $variantId) {
            if (!in_array($variantId, $productVariants)) {
                throw new PackageValidationException("Please choose the package correctly");
            }
        }

        /*********************************************************************************************************************
         * التحقق من عدم تكرار اختيار متغير لنفس المنتج
         *********************************************************************************************************************/

        $selectedProducts = variant::whereIn('id', $variantIds)->pluck('product_id')->toArray();
        $uniqueProducts = array_unique($selectedProducts);

        if (count($selectedProducts) !== count($uniqueProducts)) {
            throw new PackageValidationException('Multiple Size Selected For The Same Product');
        }
    }
}
