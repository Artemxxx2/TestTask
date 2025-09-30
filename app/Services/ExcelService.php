<?php

namespace App\Services;

use App\Models\Product;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ExcelService
{
    private Registry $manufacturerRegistry;
    private Registry $productRegistry;
    private Registry $categoriesRegistry;
    private Registry $subCategoriesRegistry;

    public function __construct()
    {
        $this->manufacturerRegistry = new Registry();
        $this->productRegistry = new Registry();
        $this->categoriesRegistry = new Registry();
        $this->subCategoriesRegistry = new Registry();
    }

    public function proceedExcel($file) {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->getRealPath());

        $reader->setShouldFormatDates(false);

        $entries = 1;
        $batchSize = 1000;
        $batch = [];
        $sameProducts = 0;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if($entries === 1) {
                    $entries += 1;
                    continue;
                }

                $cells = $row->toArray();

                $this->registryHelper($this->manufacturerRegistry,3,$cells,'App\\Models\\Manufacturer');
                $this->registryHelper($this->categoriesRegistry,1,$cells,'App\\Models\\Category');
                $this->registryHelper($this->subCategoriesRegistry,2,$cells,'App\\Models\\SubCategory');


                if (!is_null($cells[5]) && !$this->productRegistry->has($cells[5])) {
                    $this->productRegistry->set($cells[5],$cells[5]);

                    $batch[] = [
                        'manufacturer_id' => $this->manufacturerRegistry->get($cells[3]),
                        'category_id' => $this->categoriesRegistry->get($cells[1]),
                        'sub_category_id' => $this->subCategoriesRegistry->get($cells[2]),
                        'name' => $cells[4],
                        'model_code' => $cells[5],
                        'description' => $cells[6],
                        'retail_price' => $cells[7],
                        'warranty' => $cells[8],
                        'availability' => $cells[9],
                    ];
                }
                else {
                    $sameProducts += 1;
                }


                if (count($batch) >= $batchSize) {
                    Product::insert($batch);
                    $batch = [];
                }

                $entries += 1;

            }
        }

        // я не робив саме для цього файлу оптимізацію по меморі,бо мені написав меморі юседж 36мб,що не є багато

        Product::insert($batch);
        $memory = memory_get_peak_usage() / 1024 / 1024 . ' MB';

        $reader->close();

        return [$sameProducts,$memory];
    }


    private function registryHelper(Registry &$registry,$index,$cells,$namespace) {

        if (!$registry->has($cells[$index]) && !is_null($cells[$index])) {
            $manufacturer = $namespace::create([
                'name' => $cells[$index]
            ]);
            $registry->set($cells[$index],$manufacturer->id);
        }

    }
}
