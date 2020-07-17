@extends('layouts.master')

@section('content')
<div class="container-fluid">

    <div>{{ isset($success) ? $success : '' }}</div>
    <div class="row">
        <div class="col-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Upload file participants
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form method="POST" action="{{ url('/fileUploadParticipants') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" id="data_participants" class="custom-file-input" name="data_participants">
                            <label class="custom-file-label" for="data_participants" aria-describedby="file-label-data">Choose file</label>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="mr-auto ml-auto">
                                <button type="submit" id="file-label-data" class="btn btn-primary">Upload Data Participants</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Upload file results
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form method="POST" action="{{ url('/fileUploadResults') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" id="data_results" class="custom-file-input" name="data_results">
                            <label class="custom-file-label" for="data_results" aria-describedby="file-label-data">Choose file</label>
                        </div>
                        <br><br>
                        <div class="row">
                            <div class="mr-auto ml-auto">
                                <button type="submit" id="file-label-data" class="btn btn-primary">Upload Data Results</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                Upload log
            </h6>
        </div>
        <!-- Card Body -->
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Nama File</th>
                        <!-- <th>Nama Diklat</th> -->
                        <th>Pengunggah</th>
                        <th>Waktu Penambahan</th>
                        <th>Waktu Pengubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->file_name }}</td>
                        <!-- <td>{{ $log->training_name }}</td> -->
                        <td>{{ $log->uploader_name }}</td>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->updated_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
    let dp = document.getElementById("data_participants");
        dp.addEventListener("change", function(e) {
            let files = e.target.files;
            for (var i = 0; i < files.length; i++) {
                let file = files[i];
                dp.nextElementSibling.innerHTML = file.name;
            }
        });

    let dr = document.getElementById("data_results");
    dr.addEventListener("change", function(e) {
        let files = e.target.files;
        for (var i = 0; i < files.length; i++) {
            let file = files[i];
            dr.nextElementSibling.innerHTML = file.name;
        }
    });
</script>
@endsection