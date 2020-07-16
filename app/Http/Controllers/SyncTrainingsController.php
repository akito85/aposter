<?php

namespace App\Http\Controllers;

use App\Models\TrainingsModel;
use App\Models\ProvidersModel;
use App\Helpers\StandardDateTimeHelper;
use Illuminate\Http\Request;

class SyncTrainingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $orgs = ProvidersModel::select('organization_id')->get();

        foreach($orgs as $i) {
            $cmd = 'python bin/syntrx.py "' . $i->organization_id . '" 2>&1';

            try {
                $exe = shell_exec($cmd);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            $json = json_decode($exe, true);

            $sdt = new StandardDateTimeHelper();

            if($json[0][0] != "Tidak ada data yang ditemukan.") {
                foreach($json as $item) {
                    TrainingsModel::updateOrCreate(
                        [
                            'org_id' => $i->organization_id,
                            'training_name' => $item[1],
                        ],
                        [
                            'org_id' => $i->organization_id,
                            'training_name' => $item[1],
                            'start_date' => $sdt->standardized($item[2]),
                            'end_date' => $sdt->standardized($item[3])
                        ]
                    );
                }
            }
        }
    }
}
