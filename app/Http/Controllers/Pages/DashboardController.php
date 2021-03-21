<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TrxResultsModel;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $trName = NULL)
    {
        $training = $this->omniQuery(
            ['trx_id', 'trx_name', 'trx_start_date'],
            [
                ['trx_start_date', '=>', '2020-11-1'],
                ['trx_start_date', '<=', '2020-12-31']
            ]
        );

        $data = [
            'training' => '',
            'gender' => '',
            'age' => '',
            'rc' => '',
            'education' => '',
            'echelon' => '',
            'pass' => '',
            'trainingList' => $training,
            'organizations' => '',
            'main_unit' => '',
            'sub_unit' => '',
        ];

        if(!empty($trName))
        {
            $age = $this->getAggregateAge($trName);
            $echelon = $this->getAggregateEchelon($trName);
            $gender = $this->omniCount($this->omniQuery('gender',
                                                        'trx_name',
                                                        'nip',
                                                        'like',
                                                        '%'. $trName . '%'), 'gender');
            $rankClass = $this->omniCount($this->omniQuery('rank_class',
                                                            'trx_name',
                                                            'nip',
                                                            'like',
                                                            '%'. $trName . '%'), 'rank_class');
            $education = $this->omniCount($this->omniQuery('education_level',
                                                            'trx_name',
                                                            'nip',
                                                            'like',
                                                            '%'. $trName . '%'), 'education_level');
            $pass = $this->omniCount($this->omniQuery('graduate_status',
                                                        'trx_name',
                                                        'nip',
                                                        'like',
                                                        '%'. $trName . '%'), 'graduate_status');
            $organizations = $this->omniCount($this->omniQuery('organization_name',
                                                                '',
                                                                'organization_name',
                                                                '',
                                                                ''),'organization_name');
            $main_unit = $this->omniCount($this->omniQuery('main_unit,nip', 'trx_name', 'nip', 'like', '%'. $trName . '%'), 'main_unit');
            $sub_unit = $this->omniCount($this->omniQuery('eselon2,nip', 'trx_name', 'nip', 'like', '%'. $trName . '%'), 'eselon2');


            $data = [
                'training' => $trName,
                'gender' => json_encode($gender),
                'age' => json_encode($age),
                'rc' => json_encode($rankClass),
                'education' => json_encode($education),
                'echelon' => json_encode($echelon),
                'pass' => json_encode($pass),
                'trainingList' => $training,
                'organizations' => $organizations,
                'main_unit' => json_encode($main_unit),
                'sub_unit' => json_encode($sub_unit),
            ];
        }

        return view('pages.dashboard', $data);
    }

    private function getAggregateAge($trName)
    {
        // resulting row of objects
        $query = $this->omniQuery('trx_end_date,nip', 'trx_name', 'nip', 'like', '%'. $trName . '%');

        $now = time();

        foreach ($query as $row) {
            $age = date('Y', strtotime($row->trx_end_date)) - date('Y', strtotime(substr($row->nip, 0, 4)));

            if($age < 20) {
                $ages['l1'][] = $age;
            } elseif ($age >= 20 and $age < 30) {
                $ages['l2'][] = $age;
            } elseif ($age >= 30 and $age < 40) {
                $ages['l3'][] = $age;
            } elseif ($age >= 40 and $age < 50) {
                $ages['l4'][] = $age;
            } elseif ($age >= 50 and $age < 60) {
                $ages['l5'][] = $age;
            } else {
                $ages['l6'][] = $age;
            }
        }

        $tier1 = !empty($ages['l1']) ? collect($ages['l1'])->count() : 0;
        $tier2 = !empty($ages['l2']) ? collect($ages['l2'])->count() : 0;
        $tier3 = !empty($ages['l3']) ? collect($ages['l3'])->count() : 0;
        $tier4 = !empty($ages['l4']) ? collect($ages['l4'])->count() : 0;
        $tier5 = !empty($ages['l5']) ? collect($ages['l5'])->count() : 0;
        $tier6 = !empty($ages['l6']) ? collect($ages['l6'])->count() : 0;


        $tier = [
            '1 - 19' => $tier1,
            '20 - 29' => $tier2,
            '30 - 39' => $tier3,
            '40 - 49' => $tier4,
            '50 - 59' => $tier5,
            '> 60' => $tier6
        ];

        return $tier;
    }

    private function getAggregateEchelon($trName)
    {
        // resulting row of objects
        $query = $this->omniQuery('position_desc,nip', 'trx_name', 'nip', 'like', '%'. $trName . '%');

        // pattern echelons
        $p1 = '/dirjen|direkotrat jenderal|sekdir|sekretaris direktorat|kaban|kepala badan/i';
        $p2 = '/kepala wilayah|kepala pusat/i';
        $p3 = '/kabid|kepala bidag|kabag|kepala bagian/i';
        $p4 = '/kasubbid|kepala subbidang|kasubbag|kepala subbagian|kasi|kepala seksi|kepala subdirektorat|kasubdir|kasubdit|kepala subdir|kepala subdit/i';
        $p5 = '/^pelaksana|penata|penyaji|pengatur|^pengolah/i';

        // group echelon
        foreach($query as $row) {
            $position = $row->position_desc;

            if(preg_match($p1, $position)) {
                $e['Eselon I'][] = $position;
            } elseif(preg_match($p2, $position)) {
                $e['Eselon II'][] = $position;
            } elseif(preg_match($p3, $position)) {
                $e['Eselon III'][] = $position;
            } elseif(preg_match($p4, $position)) {
                $e['Eselon IV'][] = $position;
            } elseif(preg_match($p5, $position)) {
                $e['Pelaksana'][] = $position;
            } else {
                $e['Fungsional'][] = $position;
            }
        }

        // count array
        $c['Eselon I'] = !empty($e['Eselon I']) ? collect($e['Eselon I'])->count() : 0;
        $c['Eselon II'] = !empty($e['Eselon II']) ? collect($e['Eselon II'])->count() : 0;
        $c['Eselon III'] = !empty($e['Eselon III']) ? collect($e['Eselon III'])->count() : 0;
        $c['Eselon IV'] = !empty($e['Eselon IV']) ? collect($e['Eselon IV'])->count() : 0;
        $c['Pelaksana'] = !empty($e['Pelaksana']) ? collect($e['Pelaksana'])->count() : 0;
        $c['Fungsional'] = !empty($e['Fungsional']) ? collect($e['Fungsional'])->count() : 0;

        return $c;
    }

    private function omniQuery($selectField = [], $whereField = [], $distinctField = NULL)
    {
        // resulting row of objects
        if(empty($distinctField)) {
            $query = DB::table('trx_results')
                        ->select($selectField)
                        ->where($whereField)
                        ->get();
        } else {
            $query = DB::table('trx_results')
                        ->select($selectField)
                        ->where($whereField, $queryOperator, $queryString)
                        ->distinct($distinctField)
                        ->get();
        }

        return $query;
    }

    private function omniCount($queryResult = NULL, $selectField)
    {
        $data = [];

        if(!empty($queryResult)) {
            foreach ($queryResult as $row) {
                $data[] = $row->$selectField;
            }

            if(!empty(array_filter($data))) {
                $collection = collect($data);

                $counted = $collection->countBy();

                return $counted->all();
            }
        }

        return false;
    }
}
