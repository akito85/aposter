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
                'organization_name' => $this->UTF8Conf($record[6]),
                'stx_id' => $this->UTF8Conf($record[13]),
                'nip' => $this->UTF8Conf(ltrim($record[15], "~")),
                'nrp_nik' => $this->UTF8Conf(ltrim($record[16], "~")),
                'rank_class' => $this->UTF8Conf($record[17]),
                'gender' => $this->UTF8Conf($record[20]),
                'education_level' => $this->UTF8Conf($record[25]),
                'position_desc' => $this->UTF8Conf($record[28]),
                'main_unit' => $this->UTF8Conf($record[31]),
                'graduate_status' => $this->UTF8Conf($record[37]),
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
