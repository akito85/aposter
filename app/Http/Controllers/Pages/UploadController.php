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

        $logFilename = UploadLogModel::where('file_name', $this->changeFileName($rawfile));
        $trxFilename = DB::table('trx_results')->where('filename', $this->changeFileName($rawfile));

        $records = $this->csvToArray($rawfile);
        $row = [];

        if(!empty($logFilename) && !empty($trxFilename)) {
            $logFilename->delete();
            $trxFilename->delete();
        }

        foreach($records as $record) {
            $row = [
                'trx_id' => $this->UTF8Conf($record[0]),
                'trx_name' => $this->UTF8Conf($record[1]),
                'trx_start_date' => $this->UTF8Conf(date("Y-m-d", strtotime($record[2]))),
                'trx_end_date' => $this->UTF8Conf(date("Y-m-d", strtotime($record[3]))),
                'organization_name' => empty($this->UTF8Conf($record[6])) ? 'Tidak Diisi' : $this->UTF8Conf($record[6]),
                'stx_id' => $this->UTF8Conf($record[13]),
                'nip' => $this->UTF8Conf(ltrim($record[15], "~")),
                'nrp_nik' => $this->UTF8Conf(ltrim($record[16], "~")),
                'rank_class' => empty($this->UTF8Conf($record[17])) ? 'Tidak Diisi' : $this->UTF8Conf($record[17]),
                'gender' => empty($this->UTF8Conf($record[20])) ? 'Tidak Diisi' : $this->UTF8Conf($record[20]),
                'education_level' => empty($this->UTF8Conf($record[25])) ? 'Tidak Diisi' : $this->UTF8Conf($record[25]),
                'position_desc' => empty($this->UTF8Conf($record[28])) ? 'Tidak Diisi' : $this->UTF8Conf($record[28]),
                'main_unit' => empty($this->UTF8Conf($record[31])) ? 'Tidak Diisi' : $this->UTF8Conf($record[31]),
                'eselon2' => empty($this->UTF8Conf($record[32])) ? 'Tidak Diisi' : $this->UTF8Conf($record[32]),
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

    public function deleteUploadRecords(Request $request)
    {
        $logFilename = UploadLogModel::where('file_name', $request->filename)->delete();
        $trxFilename = DB::table('trx_results')
            ->where('filename', $request->filename)
            ->delete();

        return redirect('upload')->with('status', 'Successfully deleted!');
    }

    private function listUploadLog()
    {
        $count = DB::table('trx_results')
            ->select(DB::raw("COUNT(*) as rows, MAX(created_at) as cr_at"))
            ->orderBy('cr_at', 'desc')
            ->groupBy('filename')
            ->get();

        $log = UploadLogModel::select('*')
                    ->limit(11)
                    ->orderBy('created_at', 'desc')
                    ->get();

        $logs =  [
            'log' => $log,
            'count' => $count
        ];

        return $logs;
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

    private function csvToArray($filename = '')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];
        $delimiter = ',';

        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 9999, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else {
                    // $data[] = array_combine($header, $row);
                    if(!empty($row[0])) {
                        $data[] = $row;
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }

    private function csvDelimCheck($csvFile)
    {
        $delimiters = array( ',' => 0, ';' => 0, "\t" => 0, '|' => 0, );
        $firstLine = '';
        $handle = fopen($csvFile, 'r');

        if ($handle)
        {
            $firstLine = fgets($handle); fclose($handle);
        }
        if ($firstLine)
        {
            foreach ($delimiters as $delimiter => &$count)
            {
                $count = count(str_getcsv($firstLine, $delimiter));
            }
            return(array_search(max($delimiters), $delimiters));
        }
        else
        {
            return(key($delimiters));
        }
    }

    private function UTF8Conf($string)
    {
        $c = New CleanerHelper();

        // return $c->removeWhiteSpace($string);
        return str_replace('\\', ' ', mb_convert_encoding($c->removeWhiteSpace($string), "UTF-8", "UTF-8"));
    }
}
