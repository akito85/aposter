<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Requests\UploadRequest;
use App\Http\Controllers\Controller;
use App\Imports\ParticipantsImport;
use App\Imports\TrxTrainingsImport;
use App\Models\UploadLogModel;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
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
    public function index()
    {
        return view('pages.upload');
    }

    public function fileUpload(UploadRequest $request)
    {
        // get logged user
        $user = auth()->user();
        $id = $user->id;
        $name = $user->name;
        $email = $user->email;

        // upload timestamp for filename
        $ts = date('Y-m-d H:i:s');
        $ts = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $ts
        )->format('d-m-Y_H.i.s');

        $file = $request->file('data');
        $fn = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $idata = Excel::toArray(new ParticipantsImport, $file);
        $title = $idata[0][1][0];
        $ta = $idata[0][2][0];

        Excel::Import(new ParticipantsImport, $file);

        Excel::Import(new TrxTrainingsImport, $file);

        UploadLogModel::create(
            [
                'file_name' => $fn,
                'training_name' => $title . ' ' . $ta,
                'uploader_name' => $email
            ]
        );

        $logs = UploadLogModel::select('*')
                    ->limit(11)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pages.upload', ['logs' => $logs]);
    }
}
