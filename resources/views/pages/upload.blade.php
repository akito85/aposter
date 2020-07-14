@extends('layouts.master')

@section('content')
<div class="container-fluid">

    <div>{{ isset($success) ? $success : '' }}</div>

    <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                Upload file
            </h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <form method="POST" action="{{ url('/fileUpload') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" id="data" class="custom-file-input" name="data">
                    <label class="custom-file-label" for="data" aria-describedby="file-label-data">Choose file</label>
                </div>
                <br><br>
                <div class="row">
                    <div class="mr-auto ml-auto">
                        <button type="submit" id="file-label-data" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </form>
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
                        <th>Nama Diklat</th>
                        <th>Pengunggah</th>
                        <th>Waktu Penambahan</th>
                        <th>Waktu Pengubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->file_name }}</td>
                        <td>{{ $log->training_name }}</td>
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
    let cf = document.getElementById("data");
        cf.addEventListener("change", function(e) {
            let files = e.target.files;
            for (var i = 0; i < files.length; i++) {
                let file = files[i];
                cf.nextElementSibling.innerHTML = file.name;
            }
        });
</script>
@endsection