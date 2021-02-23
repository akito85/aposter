<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadResultsRequest;
use App\Http\Controllers\Controller;
use App\Models\UploadLogModel;
use App\Helpers\CleanerHelper;
class UploadController extends Controller
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
        return view('pages.upload', ['logs' => $this->listUploadLog()]);
    }

    public function fileUploadResults(UploadResultsRequest $request)
    {
        $rawfile = $request->file('data_results');
        $records = $this->csvToArray($rawfile);
        $row = [];

        foreach($records as $record) {
            $row = [
                'trx_id' => $this->UTF8Conf($record[0]),
                'trx_name' => $this->UTF8Conf($record[1]),
                'trx_start_date' => $this->UTF8Conf($record[2]),
                'trx_end_date' => $this->UTF8Conf($record[3]),
                'trx_hours' => $this->UTF8Conf($record[4]),
                'trx_days' => $this->UTF8Conf($record[5]),
                'organization_name' => $this->UTF8Conf($record[6]),
                'trx_type' => $this->UTF8Conf($record[7]),
                'trx_cost' => $this->UTF8Conf($record[8]),
                'trx_cost_detail' => $this->UTF8Conf($record[9]),
                'elearning' => $this->UTF8Conf($record[10]),
                'type_test' => $this->UTF8Conf($record[11]),
                'stx_class' => $this->UTF8Conf($record[12]),
                'stx_id' => $this->UTF8Conf($record[13]),
                'stx_name' => $this->UTF8Conf($record[14]),
                'nip' => $this->UTF8Conf(ltrim($record[15], "~")),
                'nrp_nik' => $this->UTF8Conf(ltrim($record[16], "~")),
                'rank_class' => $this->UTF8Conf($record[17]),
                'born' => $this->UTF8Conf($record[18]),
                'birthday' => $this->UTF8Conf($record[19]),
                'gender' => $this->UTF8Conf($record[20]),
                'phone' => $this->UTF8Conf($record[21]),
                'email' => $this->UTF8Conf($record[22]),
                'office_address' => $this->UTF8Conf($record[23]),
                'office_phone' => $this->UTF8Conf($record[24]),
                'education_level' => $this->UTF8Conf($record[25]),
                'education_desc' => $this->UTF8Conf($record[26]),
                'position_level' => $this->UTF8Conf($record[27]),
                'position_desc' => $this->UTF8Conf($record[28]),
                'married' => $this->UTF8Conf($record[29]),
                'religion' => $this->UTF8Conf($record[30]),
                'main_unit' => $this->UTF8Conf($record[31]),
                'eselon2' => $this->UTF8Conf($record[32]),
                'eselon3' => $this->UTF8Conf($record[33]),
                'eselon4' => $this->UTF8Conf($record[34]),
                'satker' => $this->UTF8Conf($record[35]),
                'test_result' => $this->UTF8Conf($record[36]),
                'graduate_status' => $this->UTF8Conf($record[37]),
                'activity' => $this->UTF8Conf($record[38]),
                'presence' => $this->UTF8Conf($record[39]),
                'pre_test' => $this->UTF8Conf($record[40]),
                'post_test' => $this->UTF8Conf($record[41]),
                'cert_no' => $this->UTF8Conf($record[42]),
                'cert_date' => $this->UTF8Conf($record[43]) instanceof Date ? $this->UTF8Conf($record[43]) : "NULL",
                'cert_link' => $this->UTF8Conf($record[44]),
                'execution_value' => $this->UTF8Conf($record[45]),
                'trainer_value' => $this->UTF8Conf($record[46]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'filename' => $this->changeFileName($rawfile)
            ];

            DB::table('trx_results')->insert($row);
        }

        $this->saveUploadLog($this->changeFileName($rawfile), '', '', Auth::user()->email);

        return redirect('upload')->with('status', 'Successfully uploaded!');
    }

    private function listUploadLog()
    {
        return UploadLogModel::select('*')
                    ->limit(11)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    private function saveUploadLog($file, $title, $year, $email)
    {
        UploadLogModel::create(
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

    private function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 9999, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    // $data[] = array_combine($header, $row);
                    $data[] = $row;
            }
            fclose($handle);
        }

        return $data;
    }

    private function UTF8Conf($string)
    {
        $c = New CleanerHelper();

        // return $c->removeWhiteSpace($string);
        return mb_convert_encoding($c->removeWhiteSpace($string), "UTF-8", "UTF-8");
    }
}
