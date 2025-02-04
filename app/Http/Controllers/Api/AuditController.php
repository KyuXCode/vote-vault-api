<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Random\RandomException;

class AuditController extends Controller
{
    /**
     * Public Tests Sampling Procedure
     */
    public function publicTests(): JsonResponse
    {
        $seedNumber = request('seed_number', null);

        if (is_null($seedNumber)) {
            $seedNumber = random_int(1, PHP_INT_MAX);
//            $seedNumber = (int)(str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
//                str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
//                random_int(1, 99));
        } else {
            $seedNumber = (int)$seedNumber;
        }

        // Set PHP's random seed
        mt_srand($seedNumber);

        $validSystemBases = ['BMD', 'OPSCAN', 'DRE', 'VVPAT'];

        $results = DB::table('inventory_units as iu')
            ->leftJoin('dispositions as d', 'iu.id', '=', 'd.inventory_unit_id')
            ->join('components as c', 'iu.component_id', '=', 'c.id')
            ->join('certifications as cert', 'c.certification_id', '=', 'cert.id')
            ->join('contracts as con', 'cert.id', '=', 'con.certification_id')
            ->join('expenses as exp', 'exp.contract_id', '=', 'con.id')
            ->join('counties as co', 'exp.county_id', '=', 'co.id')
            ->whereNull('d.id')
            ->where('iu.usage', '!=', 'Inactive')
            ->where('con.end_date', '>=', now())
            ->whereIn('cert.system_type', ['VS', 'EPB'])

            ->select(
                'iu.id as inventory_id',
                'iu.serial_number',
                'iu.condition',
                'iu.usage',
                'c.name as component_name',
                'c.type as component_type',
                'cert.system_base',
                'co.name as county_name',
                DB::raw("ROW_NUMBER() OVER (PARTITION BY co.id, cert.system_base ORDER BY RAND($seedNumber)) as row_num")

            )
            ->groupBy(
                'iu.id', 'iu.serial_number', 'iu.condition', 'iu.usage',
                'c.name', 'c.type', 'cert.system_base', 'co.name'
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
                return $result;

//                return $result->map(function ($item) {
//                    $item->temporary_guid = Str::uuid();
//                    return $item;
//                });
            })
            ->flatten(1)
            ->each(function ($item) {
                $item->temporary_guid = (string) Str::uuid();
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
        $seedNumber = request('seed_number', null);

        if (is_null($seedNumber)) {
            $seedNumber = random_int(1, PHP_INT_MAX);
//            $seedNumber = (int)(str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
//                str_pad(random_int(1, 999999999), 9, '0', STR_PAD_LEFT) .
//                random_int(1, 99));
        } else {
            $seedNumber = (int)$seedNumber;
        }

        // Set PHP's random seed
        //mt_srand($seedNumber);

        $samplePercentage = 0.0001; // 0.01%

        $results = DB::table('inventory_units as iu')
            ->leftJoin('dispositions as d', 'iu.id', '=', 'd.inventory_unit_id')
            ->join('components as c', 'iu.component_id', '=', 'c.id')
            ->join('certifications as cert', 'c.certification_id', '=', 'cert.id')
            ->join('contracts as con', 'cert.id', '=', 'con.certification_id')
            ->join('expenses as exp', 'exp.contract_id', '=', 'con.id')
            ->join('counties as co', 'exp.county_id', '=', 'co.id')

            ->whereNull('d.id')
            ->where('con.end_date', '>=', now())
            ->whereIn('cert.system_type', ['VS', 'EPB'])
            ->select(
                'iu.id as inventory_id',
                'iu.serial_number',
                'iu.condition',
                'iu.usage',
                'c.name as component_name',
                'c.type as component_type',
                'cert.system_base',
                'co.name as county_name',
                DB::raw('COUNT(iu.id) OVER (PARTITION BY c.name) as total_count'),
                DB::raw("ROW_NUMBER() OVER (PARTITION BY c.name ORDER BY RAND($seedNumber)) as row_num")
            )


            ->groupBy(
                'iu.id', 'iu.serial_number', 'iu.condition', 'iu.usage',
                'c.name', 'c.type', 'cert.system_base', 'co.name'
            )
            ->get()
            ->groupBy('component_name')
            ->map(function ($group) use ($samplePercentage) {
                $totalCount = $group->first()->total_count;
                $sampleSize = max(1, min(10, (int)ceil($totalCount * $samplePercentage)));
                return $group->take($sampleSize);
            })
            ->flatten(1)
            ->sortBy('county_name')
            ->each(function ($item) {
                $item->temporary_guid = (string) Str::uuid();
            });
        return response()->json([
            'seed_number' => $seedNumber,
            'results' => $results->values(),
        ]);
    }
}
