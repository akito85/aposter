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
                ['rank_class','nip'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'rank_class');
        $edu = $this->getAggregateEducation($start, $end, $whereTrxID);
        $pas = $this->omniCount(
            $this->omniQuery(
                ['graduate_status','nip'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'graduate_status');
        $org = $this->omniCount(
            $this->omniQuery(
                ['organization_name','nip'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'organization_name');
        $pri = $this->omniCount(
            $this->omniQuery(
                ['main_unit','nip'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'main_unit');
        $sec = $this->omniCount(
            $this->omniQuery(
                ['eselon2','nip'],
                [
                    $whereTrxID,
                ],
                ['trx_start_date', [$start, $end]],
                ['nip']),
            'eselon2');


        $data = [
            'training' => $trxID,
            'gender' => json_encode($sex),
            'age' => json_encode($age),
            'rc' => json_encode($cls),
            'education' => json_encode($edu),
            'echelon' => json_encode($ech),
            'pass' => json_encode($pas),
            'trainingList' => $trx,
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
            ' < 20' => $tier1,
            '20 - 29' => $tier2,
            '30 - 39' => $tier3,
            '40 - 49' => $tier4,
            '50 - 59' => $tier5,
            '> 60' => $tier6
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
                $e['SMA'][] = $edu;
            } elseif(preg_match($p2, $edu)) {
                if($edu != 'D IV' || $edu != 'DIV' || $edu != 'D 4' || $edu != 'D4') {
                    $e['D I'][] = $edu;
                }
            } elseif(preg_match($p3, $edu)) {
                $e['D II'][] = $edu;
            } elseif(preg_match($p4, $edu)) {
                $e['D III'][] = $edu;
            } elseif(preg_match($p5, $edu)) {
                $e['D IV'][] = $edu;
            } elseif(preg_match($p6, $edu)) {
                $e['S 1'][] = $edu;
            } elseif(preg_match($p7, $edu)) {
                $e['S 2'][] = $edu;
            } elseif(preg_match($p8, $edu)) {
                $e['S 3'][] = $edu;
            } else {
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
        $p3 = '/kabid|kepala bidag|kabag|kepala bagian/i';
        $p4 = '/kasubbid|kepala subbidang|kasubbag|kepala subbagian|kasi|kepala seksi|kepala subdirektorat|kasubdir|kasubdit|kepala subdir|kepala subdit/i';
        $p5 = '/^pelaksana|penata|penyaji|pengatur|^pengolah/i';
        $p6 = '/muda|pertama|madya|utama|widyaiswara|fungsional/i';

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
            } elseif(preg_match($p6, $position)) {
                $e['Fungsional'][] = $position;
            } else {
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
        $query = DB::table('trx_results')
                    ->select($selectField)
                    ->where($whereField)
                    ->whereBetween($betweenField[0],$betweenField[1])
                    ->distinct($distinctField)
                    ->get();

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
