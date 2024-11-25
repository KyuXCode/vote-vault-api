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
            ->join('counties as co', 'iu.expense_id', '=', 'co.id')
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
            ->groupBy(['county_name', 'system_base'])
            ->map(function ($group) {
                $sampleSize = (int)ceil($group->count() * 0.05);
                return $group->take($sampleSize);
            })
            ->flatten(1)
            ->map(function ($item) {
                $item->temporary_guid = Str::uuid();
                return $item;
            })
            ->sortBy('temporary_guid');

        return response()->json([
            'seed_number' => $seedNumber,
            'results' => $results->values(),
        ]);
    }

    /**
     * Random Audits Sampling Procedure
     */
    public function randomAudits(): JsonResponse
    {
        $results = DB::table('inventory_units as iu')
            ->join('components as c', 'iu.component_id', '=', 'c.id')
            ->get();

        return response()->json($results);
    }
}
