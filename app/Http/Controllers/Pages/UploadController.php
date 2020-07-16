<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Requests\UploadParticipantsRequest;
use App\Http\Requests\UploadReultsRequest;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantsImport;
use App\Imports\TrxTrainingsImport;
use App\Models\UploadLogModel;
use App\Helpers\CleanerHelper;
use Maatwebsite\Excel\Facades\Excel;

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
        $this->email = auth()->user()->email;
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

        return view('pages.upload', ['logs' => $this->logUploadList()]);
    }

    public function fileUploadResults(UploadResultsRequest $request) {

        $this->saveUploadLog($this->changeFileName('data_requests'), '', '', $this->email);

        return view('pages.upload', ['logs' => $this->listUploadLog()]);
    }

    private function listUploadLog()
    {
        return UploadLogModel::select('*')
                    ->limit(11)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    private function saveUploadLog($file, $training_name = '', $year = '', $email)
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

        $file = $request->file($file);
        $fn = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $fn;
    }
}
