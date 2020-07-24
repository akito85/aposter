<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
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
        $age = $this->getAggregateAge($trName);
        $echelon = $this->getAggregateEchelon($trName);
        $gender = $this->omniCount(
                    $this->omniQuery('gender', 'training_name', 'nip', 'like', $trName), 'gender');
        $rankClass = $this->omniCount(
                        $this->omniQuery('rank_class', 'training_name', 'nip', 'like', $trName), 'rank_class');
        $education = $this->omniCount(
                        $this->omniQuery('education', 'training_name', 'nip', 'like', $trName), 'education');
        $pass = $this->omniCount(
            $this->omniQuery('graduate_status', 'training_name', 'nip', 'like', $trName), 'graduate_status');

        $training = $this->omniQuery('training_name', 'training_name', 'training_name', 'like', $trName);

        $data = [
            'training' => $trName,
            'gender' => json_encode($gender),
            'age' => json_encode($age),
            'rc' => json_encode($rankClass),
            'education' => json_encode($education),
            'echelon' => json_encode($echelon),
            'pass' => json_encode($pass),
            'training' => json_encode($training)
        ];

        return view('pages.dashboard', $data);
    }

    private function getAggregateAge($trName)
    {
        // resulting row of objects
        $query = $this->omniQuery('birthday', 'training_name', 'nip', 'like', $trName);

        $now = time();

        foreach ($query as $row) {
            $age = date('Y', $now) - date('Y', strtotime($row->birthday));
            if($age <= 20) {
                $ages['l1'][] = $age;
            } elseif ($age >= 21 and $age <= 30) {
                $ages['l2'][] = $age;
            } elseif ($age >= 31 and $age <= 40) {
                $ages['l3'][] = $age;
            } elseif ($age >= 41 and $age <= 50) {
                $ages['l4'][] = $age;
            } elseif ($age >= 51 and $age <= 60) {
                $ages['l5'][] = $age;
            }
        }

        $tier1 = !empty($ages['l1']) ? collect($ages['l1']) : 0;
        $tier1 = !empty($tier1) ? $tier1->count() : 0;
        $tier2 = !empty($ages['l2']) ? collect($ages['l2']) : 0;
        $tier2 = !empty($tier2) ? $tier2->count() : 0;
        $tier3 = !empty($ages['l3']) ? collect($ages['l3']) : 0;
        $tier3 = !empty($tier3) ? $tier3->count() : 0;
        $tier4 = !empty($ages['l4']) ? collect($ages['l4']) : 0;
        $tier4 = !empty($tier4) ? $tier4->count() : 0;
        $tier5 = !empty($ages['l5']) ? collect($ages['l5']) : 0;
        $tier5 = !empty($tier5) ? $tier5->count() : 0;

        $tier = [
            '1 - 20' => $tier1,
            '21 - 30' => $tier2,
            '31 - 40' => $tier3,
            '41 - 50' => $tier4,
            '51 - 60' => $tier5,
        ];

        return $tier;
    }

    private function getAggregateEchelon($trName)
    {
        // resulting row of objects
        $query = $this->omniQuery('position_desc', 'training_name', 'nip', 'like', $trName);

        // group echelon
        foreach($query as $row) {
            $position = $row->position_desc;

            // pattern echelons
            $p1 = '/dirjen|direkotrat jenderal|sekdir|sekretaris direktorat|kaban|kepala badan/i';
            $p2 = '/kepala wilayah|kepala pusat/i';
            $p3 = '/kabid|kepala bidag|kabag|kepala bagian/i';
            $p4 = '/kasubbid|kepala subbidang|kasubbag|kepala subbagian|kasi|kepala seksi|kepala subdirektorat|kasubdir|kasubdit|kepala subdir|kepala subdit/i';
            $p5 = '/^pelaksana|penata|penyaji|pengatur|^pengolah/i';

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
        if(!empty($e['Eselon I'])) {
            $c['Eselon I'] = collect($e['Eselon I'])->count();
        } else {
            $c['Eselon I'] = 0;
        }
        if(!empty($e['Eselon II'])) {
            $c['Eselon II'] = collect($e['Eselon II'])->count();
        } else {
            $c['Eselon II'] = 0;
        }
        if(!empty($e['Eselon III'])) {
            $c['Eselon III'] = collect($e['Eselon III'])->count();
        } else {
            $c['Eselon III'] = 0;
        }
        if(!empty($e['Eselon IV'])) {
            $c['Eselon IV'] = collect($e['Eselon IV'])->count();
        } else {
            $c['Eselon IV'] = 0;
        }
        if(!empty($e['Pelaksana'])) {
            $c['Pelaksana'] = collect($e['Pelaksana'])->count();
        } else {
            $c['Pelaksana'] = 0;
        }

        $c['Fungsional'] = collect($e['Fungsional'])->count();

        return $c;
    }

    private function omniQuery($selectField, $whereField, $distinctField = NULL, $queryOperator, $queryString)
    {
        // resulting row of objects
        if(!empty($queryString)) {
            if(empty($distinctField)) {
                $query = TrxResultsModel::select($selectField)
                            ->where($whereField, $queryOperator, $queryString)
                            ->get();
            } else {
                $query = TrxResultsModel::select($selectField)
                            ->where($whereField, $queryOperator, $queryString)
                            ->distinct($distinctField)
                            ->get();
            }
        } else {
            $query = TrxResultsModel::select($selectField, $distinctField)
                        //->distinct($distinctField)
                        ->get();
        }

        return $query;
    }

    private function omniCount($queryResult = NULL, $selectField)
    {
        if(!empty($queryResult)) {
            foreach ($queryResult as $row) {
                $data[] = $row->$selectField;
            }

            $collection = collect($data);

            $counted = $collection->countBy();

            return $counted->all();
        }

        return false;
    }
}
