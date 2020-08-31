<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Requests\UploadParticipantsRequest;
use App\Http\Requests\UploadResultsRequest;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantsImport;
use App\Imports\TrxTrainingsImport;
use App\Models\UploadLogModel;
use App\Models\TrxResultsModel;
use App\Helpers\CleanerHelper;
use Maatwebsite\Excel\Facades\Excel;
use PHPHtmlParser\Dom;

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

        $user = auth()->user();
        $this->email = 'akito.evol@gmail.com';
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

    public function fileUploadParticipants(UploadParticipantsRequest $request)
    {
        $cleaner = new CleanerHelper();

        $idata = Excel::toArray(new ParticipantsImport, $file);
        $title = $idata[0][1][0];
        $ta = explode(' ', $cleaner->removeWhiteSpace($idata[0][2][0]));
        $ta = $ta[2];

        Excel::Import(new ParticipantsImport, $file);
        Excel::Import(new TrxTrainingsImport, $file);

        $this->saveUploadLog($this->changeFileName('data_participants'), $title, $ta, $this->email);

        return redirect('upload')->with('status', 'Successfully uploaded!');
    }

    public function fileUploadResults(UploadResultsRequest $request) {
        $rawfile = $request->file('data_results');
        // $tempfile = fopen('temp/temp.txt', 'w') or die('Unable to open file!');
        $html = fopen($rawfile, 'r') or die('Unable to open file!');

        /* this code use python to do the html parse
            fwrite($tempfile, fgets($html));
            fclose($tempfile);
            $cmd = 'python bin/synrs.py 2>&1';

            try {
                $exe = shell_exec($cmd);
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            $json = json_decode($exe, true);
        */

        $html = preg_replace('/( xmlns:o="urn:schemas-microsoft-com:office:office")/', '', fgets($html));
        $html = preg_replace('/( xmlns:x="urn:schemas-microsoft-com:office:excel")/', '', $html);
        $html = preg_replace('/( xmlns="http:\/\/www.w3.org\/TR\/REC-html40")/', '', $html);
        $html = preg_replace('/(<!--\[if gte mso 9\]>)/', '', $html);
        $html = preg_replace('/(<!\[endif\]-->)/', '', $html);
        $html = preg_replace('/(<meta charset="utf-8"\/>)/', '',$html);
        $html = preg_replace('/(<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>data<\/x:Name><x:WorksheetOptions><x:DisplayGridlines\/><\/x:WorksheetOptions><\/x:ExcelWorksheet><\/x:ExcelWorksheets><\/x:ExcelWorkbook><\/xml>)/', '', $html);

        $dom = new \DOMDocument;
        $html = $dom->loadHTML($html);
        $tbody = $dom->getElementsByTagName('tbody');
        $rows = $tbody->item(0)->getElementsByTagName('tr');

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');

            // Fix weird case where date field is empty string...
            $date = NULL; // default
            if (is_object($cols->item(42))) {
                if(!empty($cols->item(42)->nodeValue)) {
                    $date = $cols->item(42)->nodeValue;
                } else {
                    $date = NULL;
                }
            }

            if(empty($date)) {
                $date = NULL;
            }

            $data = [
                'training_id' => (is_object($cols->item(0)) ? $cols->item(0)->nodeValue : NULL),
                'training_name' => (is_object($cols->item(1)) ? $cols->item(1)->nodeValue : NULL),
                'start' => (is_object($cols->item(2)) ? $cols->item(2)->nodeValue : NULL),
                'end' => (is_object($cols->item(3)) ? $cols->item(3)->nodeValue : NULL),
                'hours' => (is_object($cols->item(4)) ? $cols->item(4)->nodeValue : NULL),
                'days' => (is_object($cols->item(5)) ? $cols->item(5)->nodeValue : NULL),
                'organization_name' => (is_object($cols->item(6)) ? $cols->item(6)->nodeValue : NULL),
                'type' => (is_object($cols->item(7)) ? $cols->item(7)->nodeValue : NULL),
                'cost' => (is_object($cols->item(8)) ? $cols->item(8)->nodeValue : NULL),
                'cost_detail' => (is_object($cols->item(9)) ? $cols->item(9)->nodeValue : NULL),
                'elearning' => (is_object($cols->item(10)) ? $cols->item(10)->nodeValue : NULL),
                'type_test' => (is_object($cols->item(11)) ? $cols->item(11)->nodeValue : NULL),
                'class' => (is_object($cols->item(12)) ? $cols->item(12)->nodeValue : NULL),

                'student_id' => (is_object($cols->item(13)) ? $cols->item(13)->nodeValue : NULL),
                'name' => (is_object($cols->item(14)) ? $cols->item(14)->nodeValue : NULL),
                'nip' => (is_object($cols->item(15)) ? preg_replace('/~/', '', $cols->item(15)->nodeValue) : NULL),
                'nrp_nik' => (is_object($cols->item(16)) ? preg_replace('/~/', '', $cols->item(16)->nodeValue) : NULL),
                'rank_class' => (is_object($cols->item(17)) ? $cols->item(17)->nodeValue : NULL),
                'born' => (is_object($cols->item(18)) ? $cols->item(18)->nodeValue : NULL),
                'birthday' => (is_object($cols->item(19)) ? $cols->item(19)->nodeValue : NULL),
                'gender' => (is_object($cols->item(20)) ? $cols->item(20)->nodeValue : NULL),
                'phone' => (is_object($cols->item(21)) ? $cols->item(21)->nodeValue : NULL),
                'email' => (is_object($cols->item(22)) ? $cols->item(22)->nodeValue : NULL),
                'office_address' => (is_object($cols->item(23)) ? $cols->item(23)->nodeValue : NULL),
                'education' => (is_object($cols->item(24)) ? $cols->item(24)->nodeValue : NULL),
                'education_desc' => (is_object($cols->item(25)) ? $cols->item(25)->nodeValue : NULL),
                'position' => (is_object($cols->item(26)) ? $cols->item(26)->nodeValue : NULL),
                'position_desc' => (is_object($cols->item(27)) ? $cols->item(27)->nodeValue : NULL),
                'married' => (is_object($cols->item(28)) ? $cols->item(28)->nodeValue : NULL),
                'religion' => (is_object($cols->item(29)) ? $cols->item(29)->nodeValue : NULL),
                'main_unit' => (is_object($cols->item(30)) ? $cols->item(30)->nodeValue : NULL),
                'eselon2' => (is_object($cols->item(31)) ? $cols->item(31)->nodeValue : NULL),
                'eselon3' => (is_object($cols->item(32)) ? $cols->item(32)->nodeValue : NULL),
                'eselon4' => (is_object($cols->item(33)) ? $cols->item(33)->nodeValue : NULL),
                'satker' => (is_object($cols->item(34)) ? $cols->item(34)->nodeValue : NULL),
                'test_result' => (is_object($cols->item(35)) ? $cols->item(35)->nodeValue : NULL),
                'graduate_status' => (is_object($cols->item(36)) ? $cols->item(36)->nodeValue : NULL),
                'activity' => (is_object($cols->item(37)) ? $cols->item(37)->nodeValue : NULL),
                'presence' => (is_object($cols->item(38)) ? $cols->item(38)->nodeValue : NULL),
                'pre_test' => (is_object($cols->item(39)) ? $cols->item(39)->nodeValue : NULL),
                'post_test' => (is_object($cols->item(40)) ? $cols->item(40)->nodeValue : NULL),

                'number' => (is_object($cols->item(41)) ? $cols->item(41)->nodeValue : NULL),
                'date' => $date,
                'execution_value' => (is_object($cols->item(43)) ?  $cols->item(43)->nodeValue : NULL),
                'trainer_value' => (is_object($cols->item(44)) ? $cols->item(44)->nodeValue : NULL)
            ];

            if(!empty(array_filter($data))) {
                TrxResultsModel::create($data);
            }
         }

        $this->saveUploadLog($this->changeFileName($rawfile), '', '', $this->email);

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
}
