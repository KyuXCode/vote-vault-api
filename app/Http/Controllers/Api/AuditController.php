<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class AuditController extends Controller
{
    /**
     * Public Tests Sampling Procedure
     */
    public function publicTests(): JsonResponse
    {
        $seedNumber = str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
            str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
            random_int(1, 99);

        $validSystemBases = ['BMD', 'OPSCAN', 'DRE', 'VVPAT'];

        $results = DB::table('inventory_units as iu')
            ->join('components as c', 'iu.component_id', '=', 'c.id')
            ->join('certifications as cert', 'c.certification_id', '=', 'cert.id')
            ->join('contracts as con', 'cert.id', '=', 'con.certification_id')
            ->join('expenses as exp', 'exp.contract_id', '=', 'con.id')
            ->join('counties as co', 'exp.county_id', '=', 'co.id')
            ->leftJoin('dispositions as d', 'iu.id', '=', 'd.inventory_unit_id')
            ->whereNull('d.id')
            ->where('iu.usage', '!=', 'Inactive')
            ->whereDate('con.end_date', '>=', now())
            ->select(
                'iu.id as inventory_id',
                'iu.serial_number',
                'iu.condition',
                'iu.usage',
                'c.name as component_name',
                'c.type as component_type',
                'cert.system_base',
                'co.name as county_name',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY co.id, cert.system_base ORDER BY RANDOM()) as row_num')
            )
            ->get()
            ->groupBy('county_name')
            ->map(function ($group) use ($validSystemBases) {
                $result = collect();
                foreach ($validSystemBases as $base) {
                    $systemBaseGroup = $group->where('system_base', $base);
                    $sampleSize = (int)ceil($systemBaseGroup->count() * 0.05);
                    $result = $result->merge($systemBaseGroup->take($sampleSize));
                }
                return $result->map(function ($item) {
                    $item->temporary_guid = Str::uuid();
                    return $item;
                });
            });

        return response()->json([
            'seed_number' => $seedNumber,
            'results' => $results,
        ]);
    }

    /**
     * Random Audits Sampling Procedure
     */
    public function randomAudits(): JsonResponse
    {
        $seedNumber = str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
            str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
            random_int(1, 99);

        $samplePercentage = 0.0001; // 0.01%

        $results = DB::table('inventory_units as iu')
            ->join('components as c', 'iu.component_id', '=', 'c.id')
            ->join('certifications as cert', 'c.certification_id', '=', 'cert.id')
            ->join('contracts as con', 'cert.id', '=', 'con.certification_id')
            ->join('expenses as exp', 'exp.contract_id', '=', 'con.id')
            ->join('counties as co', 'exp.county_id', '=', 'co.id')
            ->leftJoin('dispositions as d', 'iu.id', '=', 'd.inventory_unit_id')
            ->whereNull('d.id')
            ->whereDate('con.end_date', '>=', now())
            ->whereIn('cert.system_type', ['VS', 'EPB'])
            ->select(
                'iu.id as inventory_id',
                'iu.serial_number',
                'iu.condition',
                'iu.usage',
                'c.name as component_name',
                'co.name as county_name',
                DB::raw('COUNT(iu.id) OVER (PARTITION BY c.name) as total_count'),
                DB::raw('ROW_NUMBER() OVER (PARTITION BY c.name ORDER BY RANDOM()) as row_num')
            )
            ->get()
            ->groupBy('component_name')
            ->map(function ($group) use ($samplePercentage) {
                $totalCount = $group->first()->total_count;
                $sampleSize = max(1, (int)ceil($totalCount * $samplePercentage));
                return $group->take($sampleSize);
            })
            ->flatten(1)
            ->sortBy('county_name');

        return response()->json([
            'seed_number' => $seedNumber,
            'results' => $results->values(),
        ]);
    }
}
