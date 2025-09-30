<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcelRequest;
use App\Services\ExcelService;

class ExcelController extends Controller
{
    private ExcelService $service;

    public function __construct(ExcelService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ExcelRequest $request)
    {
        $start = microtime(true);

       [$sameProducts,$memory] = $this->service->proceedExcel($request->file('file'));

        $end = microtime(true);

        $executionTime = $end - $start;

        return redirect()->route('import.form')->with([
            'success' => 'File has been imported!',
            'skipped_count' => $sameProducts,
            'execution_time' => round($executionTime, 2),
            'memory' => $memory,
        ]);
    }
}
