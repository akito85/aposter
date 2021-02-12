<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use League\Csv\Reader;
use App\Http\Requests\UploadParticipantsRequest;
use App\Http\Requests\UploadResultsRequest;
use App\Http\Controllers\Controller;
use App\Models\UploadLogModel;
use App\Models\TrxResultsModel;
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
        $rawfile = $request->file('data_participants');

        $this->saveUploadLog($this->changeFileName($rawfile), $title, $ta, $this->email);

        return redirect('upload')->with('status', 'Successfully uploaded!');
    }

    public function fileUploadResults(UploadResultsRequest $request) {
        $c = New CleanerHelper();

        $rawfile = $request->file('data_results');
        // Create a CSV reader instance
        $reader = Reader::createFromFileObject($rawfile->openFile());
        $records = $reader->getRecords();

        foreach ($records as $offset => $record) {
            $row = [
                'training_id' => $record[0],
                'training_name' => $record[1],
                'start' => $record[2],
                'end' => $record[3],
                'hours' => $record[4],
                'days' => $record[5],
                'organization_name' => $record[6],
                'type' => $record[7],
                'cost' => $record[8],
                'cost_detail' => $record[9],
                'elearning' => $record[10],
                'type_test' => $record[11],
                'class' => $record[12],
                'student_id' => $record[13],
                'name' => $record[14],
                'nip' => $c->removeWhiteSpace(ltrim($record[15], "~")),
                'nrp_nik' => $c->removeWhiteSpace(ltrim($record[16], "~")),
                'rank_class' => $record[17],
                'born' => $record[18],
                'birthday' => $record[19],
                'gender' => $record[20],
                'phone' => $record[21],
                'email' => $record[22],
                'office_address' => $record[23],
                'office_phone' => $record[24],
                'education' => $record[25],
                'education_desc' => $record[26],
                'position' => $record[27],
                'position_desc' => $record[28],
                'married' => $record[29],
                'religion' => $record[30],
                'main_unit' => $record[31],
                'eselon2' => $record[32],
                'eselon3' => $record[33],
                'eselon4' => $record[34],
                'satker' => $record[35],
                'test_result' => $record[36],
                'graduate_status' => $record[37],
                'activity' => $record[38],
                'presence' => $record[39],
                'pre_test' => $record[40],
                'post_test' => $record[41],
                'number' => $record[42],
                'date' => $record[43],
                //'link' => $record[44],
                'execution_value' => $record[45],
                'trainer_value' => $record[46]
            ];

            if(!empty(array_filter($row))) {
                TrxResultsModel::create($row);
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
