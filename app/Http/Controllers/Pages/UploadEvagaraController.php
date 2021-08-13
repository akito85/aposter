<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadResultsRequest;
use App\Http\Controllers\Controller;
use App\Models\UploadLogModel;
use App\Helpers\CleanerHelper;
use App\Imports\EvagaraImport;
use Maatwebsite\Excel\Facades\Excel;

class UploadEvagaraController extends Controller
{
    public $email;

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
    public function index()
    {
        return view('pages.uploadevagara', ['logs' => $this->listUploadEvagaraLog()]);
    }

    public function fileUploadResultsEvagara(UploadResultsRequest $request)
    {
        $rawfile = $request->file('data_results');

        $f = Excel::toArray(new EvagaraImport, $rawfile);

        $data = [];

        if (isset($f[0])) {

            $row = $f[0];

            $data['header'] = [
                'title' => isset($row[0][3]) ? $row[0][3] : '',
                'date' => isset($row[1][3]) ? $row[1][3] : '',
                'place' => isset($row[2][3]) ? $row[2][3] : '',
            ];

            $data['payload_x'] = [
                'label' => [$row[7][1],$row[8][1],$row[9][1],$row[10][1],$row[11][1],$row[12][1],$row[13][1],$row[14][1]],
                'x' => [$row[7][5],$row[8][5],$row[9][5],$row[10][5],$row[11][5],$row[12][5],$row[13][5],$row[14][5],],
                'x_avg' => $row[15][5]
            ];

            $data['payload_y'] = [
                'label' => [$row[7][1],$row[8][1],$row[9][1],$row[10][1],$row[11][1],$row[12][1],$row[13][1],$row[14][1]],
                'y' => [$row[7][4],$row[8][4],$row[9][4],$row[10][4],$row[11][4],$row[12][4],$row[13][4],$row[14][4],
                ],
                'y_avg' => $row[15][4]
            ];

            $data['others'] = [
                'quandrant' => [$row[7][7],$row[8][7],$row[9][7],$row[10][7],$row[11][7],$row[12][7],$row[13][7],$row[14][7],
                ],
                'remark' => [$row[7][6],$row[8][6],$row[9][6],$row[10][6],$row[11][6],$row[12][6],$row[13][6],$row[14][6],
                ]
            ];
        }

        $payload = ['x'=> $data['payload_x'], 'y'=> $data['payload_y']];

        $payload = json_encode($payload);

        // $payload = "'" . $payload . "'";

        DB::table('trx_results_evagara')->insert(
            [
                'trx_id' => '',
                'trx_name' => $data['header']['title'],
                'trx_date' => $data['header']['date'],
                'payload' =>  $payload
            ]
        );

        $this->saveUploadEvagaraLog($this->changeFileName($rawfile), '', $data['header']['date'], Auth::user()->email);

        return redirect('uploadEvagara')->with('status', 'Successfully uploaded!');
    }

    public function deleteUploadEvagaraRecords(Request $request)
    {
        $logFilename = UploadLogModel::where('file_name', $request->filename)->delete();
        $trxFilename = DB::table('trx_results')
            ->where('filename', $request->filename)
            ->delete();

        return redirect('upload')->with('status', 'Successfully deleted!');
    }

    private function listUploadEvagaraLog()
    {
        $logEvagara = DB::table('upload_evagara_log')
                    ->select('*')
                    ->limit(11)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $logs =  [
            'log' => $logEvagara
        ];

        return $logs;
    }

    private function saveUploadEvagaraLog($file, $title, $year, $email)
    {
        DB::table('upload_evagara_log')->insert(
            [
                'file_name' => $file,
                'training_name' => $title,
                'training_year' => $year,
                'uploader_name' => $email
            ]
        );
    }

    private function changeFileName($file)
    {
        // upload timestamp for filename
        $ts = date('Y-m-d H:i:s');
        $ts = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $ts
        )->format('d-m-Y_H.i.s');

        $fn = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $fn;
    }
}
