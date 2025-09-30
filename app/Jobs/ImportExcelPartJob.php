<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ImportExcelPartJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected string $partFile;

    public function __construct(string $partFile)
    {
        $this->partFile = $partFile;
    }

    public function handle()
    {
        $rows = json_decode(file_get_contents($this->partFile), true);

        $batchSize = 500;
        $batch = [];

        foreach ($rows as $cells) {
            $batch[] = [
                'name' => $cells[4],
                'model_code' => $cells[5],
                'description' => $cells[6],
                'retail_price' => $cells[7],
                'warranty' => $cells[8],
                'availability' => $cells[9],
            ];

            if (count($batch) >= $batchSize) {
                Product::insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Product::insert($batch);
        }

        unlink($this->partFile);
    }
}
