<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TrxResultsModel;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $start = null, $end = null, $trxID = null)
    {
        $trx = $this->omniQuery(
            ['trx_id', 'trx_name', 'trx_start_date'],
            [
                // ['trx_id', '=', $trxID],
            ],
            ['trx_start_date', [$start, $end]],
            ['trx_id']
        );

        if($trxID <> 'ALL')
        {
            $whereTrxID = ['trx_id', '=', $trxID];
        }
        else
        {
            $whereTrxID = ['trx_id', '<>', ''];
        }

        $age = $this->getAggregateAge($start, $end, $whereTrxID);
        $ech = $this->getAggregateEchelon($start, $end, $whereTrxID);
        $sex = $this->omniCount(
            $this->omniQuery(
                ['gender','nip', 'trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'gender');
        $cls = $this->omniCount(
            $this->omniQuery(
                ['rank_class','nip', 'trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'rank_class');
        $edu = $this->getAggregateEducation($start, $end, $whereTrxID);
        $pas = $this->omniCount(
            $this->omniQuery(
                ['graduate_status','nip','trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'graduate_status');
        $org = $this->omniCount(
            $this->omniQuery(
                ['organization_name','nip', 'trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'organization_name');
        $pri = $this->omniCount(
            $this->omniQuery(
                ['main_unit','nip','trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                []),
            'main_unit');
        $sec = $this->omniCount(
            $this->omniQuery(
                ['eselon2','nip', 'trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['eselon2']),
            'eselon2');
        $des = $this->omniCount(
            $this->omniQuery(
                ['position_desc','nip', 'trx_id'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'position_desc');

        $trxn = $this->omniQuery(
            ['trx_id', 'trx_name'],
            [
                $whereTrxID,
            ],
            ['trx_start_date', [$start, $end]],
            ['trx_id']
        );

        if(!empty($trxn[0]->trx_name))
        {
            $q = trim(strtolower($trxn[0]->trx_name));
            $date = date_create($start);
            $evagara = DB::table('trx_results_evagara')
                        ->select('payload')
                        ->whereRAW('LOWER(trx_name) LIKE ?', ["%{$q}%"])
                        ->where('trx_date', 'like', '%' . date_format($date,"y") . '%')
                        ->get();

            if(!empty($evagara[0]))
            {
                $evagara = json_encode($evagara[0]->payload);
            }
            else
            {
                $evagara = json_encode([0]);
            }
        }
        else
        {
            $evagara = json_encode([0]);
        }

        $mainUnitList = $this->omniCount(DB::table('trx_results')
            ->select(['trx_id','main_unit', 'nip'])
            ->whereRAW('LOWER(main_unit) LIKE ?', ["%{$trxID}%"])
            ->whereBetween('trx_start_date', [$start, $end])
            //->distinct('trx_id')
            ->get(), 'main_unit');

        $data = [
            'evagara' => $evagara,
            'training' => $trxID,
            'gender' => json_encode($sex),
            'age' => json_encode($age),
            'rc' => json_encode($cls),
            'education' => json_encode($edu),
            'echelon' => json_encode($ech),
            'pass' => json_encode($pas),
            'positionDesc' => json_encode($des),
            'trainingList' => $trx,
            'mainUnitList' => json_encode($mainUnitList),
            'organizations' => $org,
            'main_unit' => json_encode($pri),
            'sub_unit' => json_encode($sec),
            'start' => $start,
            'end' => $end
        ];

        return view('pages.dashboard', $data);
    }

    private function getAggregateAge($start, $end, $trxID)
    {
        // resulting row of objects
        $query = $this->omniQuery(
            ['trx_id','trx_end_date','nip'],
            [
                $trxID,
            ],
            ['trx_start_date', [$start, $end]],
            ['nip']
        );

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
            } elseif ($age > 60) {
                $ages['l6'][] = $age;
            } else {
                $ages['l7'][] = $age;
            }
        }

        $tier1 = !empty($ages['l1']) ? collect($ages['l1'])->count() : 0;
        $tier2 = !empty($ages['l2']) ? collect($ages['l2'])->count() : 0;
        $tier3 = !empty($ages['l3']) ? collect($ages['l3'])->count() : 0;
        $tier4 = !empty($ages['l4']) ? collect($ages['l4'])->count() : 0;
        $tier5 = !empty($ages['l5']) ? collect($ages['l5'])->count() : 0;
        $tier6 = !empty($ages['l6']) ? collect($ages['l6'])->count() : 0;
        $tier7 = !empty($ages['l7']) ? collect($ages['l7'])->count() : 0;

        $tier = [
            ' < 20' => $tier1,
            '20 - 29' => $tier2,
            '30 - 39' => $tier3,
            '40 - 49' => $tier4,
            '50 - 59' => $tier5,
            '> 60' => $tier6,
            'Tidak Diisi' => $tier7
        ];

        return $tier;
    }

    private function getAggregateEducation($start, $end, $trxID)
    {
        // resulting row of objects
        $query = $this->omniQuery(
            ['trx_id','education_level','nip'],
            [
                $trxID,
            ],
            ['trx_start_date', [$start, $end]],
            ['nip']
        );

        // group education
        foreach($query as $row) {
            $edu = $row->education_level;

            $p1 = '/^SMA|^SMU|^SMK|^STM|^SLTA|^SLTU|^SMIP|^SMEA/i';
            $p2 = '/^DI|^D1|^D I|^D 1/i';
            $p3 = '/^DII|^D2|^D II|^D 2/i';
            $p4 = '/^DIII|^D3|^D III|^D 3/i';
            $p5 = '/^DIV|^D4|^D IV|^D 4/i';
            $p6 = '/^SI|^S1|^S I|^S 1/i';
            $p7 = '/^SII|^S2|^S II|^S 2/i';
            $p8 = '/^SIII|^S3|^S III|^S 3/i';

            if(preg_match($p1, $edu)) {
                if($edu == 'SMA' or $edu == 'SMU' or $edu == 'STM' or $edu == 'SMK' or $edu == 'SMIP' or $edu == 'SMEA' or $edu == 'SLTA' or $edu == 'SLTU' or $edu == 'SMAK') {
                    $e[' SMA'][] = $edu;
                }
            }
            if(preg_match($p2, $edu)) {
                if($edu == 'D I' or $edu == 'DI' or $edu == 'D1' or $edu == 'D 1') {
                    $e['D I'][] = $edu;
                }
            }
            if(preg_match($p3, $edu)) {
                if($edu == 'D II' or $edu == 'DII' or $edu == 'D2' or $edu == 'D 2') {
                    $e['D II'][] = $edu;
                }
            }
            if(preg_match($p4, $edu)) {
                $e['D III'][] = $edu;
            }
            if(preg_match($p5, $edu)) {
                $e['D IV'][] = $edu;
            }
            if(preg_match($p6, $edu)) {
                $e['S 1'][] = $edu;
            }
            if(preg_match($p7, $edu)) {
                $e['S 2'][] = $edu;
            }
            if(preg_match($p8, $edu)) {
                $e['S 3'][] = $edu;
            }
            if(empty($edu) or $edu == 'Tidak Diisi') {
                $e['Tidak Diisi'][] = $edu;
            }
        }

        // count array
        $c[' SMA'] = !empty($e[' SMA']) ? collect($e[' SMA'])->count() : 0;
        $c['D I'] = !empty($e['D I']) ? collect($e['D I'])->count() : 0;
        $c['D II'] = !empty($e['D II']) ? collect($e['D II'])->count() : 0;
        $c['D III'] = !empty($e['D III']) ? collect($e['D III'])->count() : 0;
        $c['D IV'] = !empty($e['D IV']) ? collect($e['D IV'])->count() : 0;
        $c['S 1'] = !empty($e['S 1']) ? collect($e['S 1'])->count() : 0;
        $c['S 2'] = !empty($e['S 2']) ? collect($e['S 2'])->count() : 0;
        $c['S 3'] = !empty($e['S 3']) ? collect($e['S 3'])->count() : 0;
        $c['Tidak Diisi'] = !empty($e['Tidak Diisi']) ? collect($e['Tidak Diisi'])->count() : 0;

        return $c;
    }

    private function getAggregateEchelon($start, $end, $trxID)
    {
        // resulting row of objects
        $query = $this->omniQuery(
            ['trx_id','position_desc','nip'],
            [
                $trxID,
            ],
            ['trx_start_date', [$start, $end]],
            ['nip']
        );

        // pattern echelons
        $p1 = '/dirjen|direkotrat jenderal|sekdir|sekretaris direktorat|kaban|kepala badan/i';
        $p2 = '/kepala wilayah|kepala pusat/i';
        $p3 = '/kabid|kepala bidang|kabag|kepala bagian/i';
        $p4 = '/^pembina|kasubbid|kepala subbidang|kasubbag|kepala subbagian|kasi|kepala seksi|kepala subdirektorat|kasubdir|kasubdit|kepala subdir|kepala subdit/i';
        $p5 = '/^pelaksana|penata|penyaji|pengatur|^pengolah/i';
        $p6 = '/auditor muda|auditor madya|auditor pertama|widyaiswara|analis|juru sita|jabatan fungsional|jf|analis|akpd/i';

        // group echelon
        foreach($query as $row) {
            $position = $row->position_desc;

            if(preg_match($p1, $position)) {
                $e['Eselon I'][] = $position;
            }
            if(preg_match($p2, $position)) {
                $e['Eselon II'][] = $position;
            }
            if(preg_match($p3, $position)) {
                $e['Eselon III'][] = $position;
            }
            if(preg_match($p4, $position)) {
                $e['Eselon IV'][] = $position;
            }
            if(preg_match($p5, $position)) {
                $e['Pelaksana'][] = $position;
            }
            if(preg_match($p6, $position)) {
                $e['Fungsional'][] = $position;
            }
            if(empty($position) or $position == 'Tidak Diisi') {
                $e['Tidak Diisi'][] = $position;
            }
        }

        // count array
        $c['Eselon I'] = !empty($e['Eselon I']) ? collect($e['Eselon I'])->count() : 0;
        $c['Eselon II'] = !empty($e['Eselon II']) ? collect($e['Eselon II'])->count() : 0;
        $c['Eselon III'] = !empty($e['Eselon III']) ? collect($e['Eselon III'])->count() : 0;
        $c['Eselon IV'] = !empty($e['Eselon IV']) ? collect($e['Eselon IV'])->count() : 0;
        $c['Pelaksana'] = !empty($e['Pelaksana']) ? collect($e['Pelaksana'])->count() : 0;
        $c['Fungsional'] = !empty($e['Fungsional']) ? collect($e['Fungsional'])->count() : 0;
        $c['Tidak Diisi'] = !empty($e['Tidak Diisi']) ? collect($e['Tidak Diisi'])->count() : 0;

        return $c;
    }

    private function omniQuery($selectField = [], $whereField = [], $betweenField = [], $distinctField = [])
    {
        // resulting row of objects
        if(empty($distinctField))
        {
            $query = DB::table('trx_results')
                        ->select($selectField)
                        ->where($whereField)
                        ->whereBetween($betweenField[0],$betweenField[1])
                        ->get();
        }
        else
        {
            $query = DB::table('trx_results')
                        ->select($selectField)
                        ->where($whereField)
                        ->whereBetween($betweenField[0],$betweenField[1])
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
