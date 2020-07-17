<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Requests\UploadParticipantsRequest;
use App\Http\Requests\UploadResultsRequest;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantsImport;
use App\Imports\TrxTrainingsImport;
use App\Models\UploadLogModel;
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
        $tempfile = fopen('temp/temp.txt', 'w') or die('Unable to open file!');

        $html = fopen($rawfile, 'r') or die('Unable to open file!');

        fwrite($tempfile, fgets($html));
        fclose($tempfile);

        $cmd = 'python bin/synrs.py 2>&1';

        try {
            $exe = shell_exec($cmd);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $json = json_decode($exe, true);

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
