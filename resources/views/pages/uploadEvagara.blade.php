@extends('layouts.master')

@section('content')
<br>
<br>
<br>
<br>
<div class="container-fluid">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Upload file results
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form method="POST" action="{{ secure_url('/fileUploadResultsEvagara') }}" enctype="multipart/form-data">
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
                        <th></th>
                        <th>Nama File</th>
                        <th>Pengunggah</th>
                        <th>Waktu Penambahan</th>
                        <th>Waktu Pengubahan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs['log'] as $log)
                    <tr>
                        <td>
                        <a href="{{ secure_url('deleteUploadRecords') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('delete-form_{{ $log->file_name }}').submit();">
                            Delete
                        </a>

                        <form id="delete-form_{{ $log->file_name }}"
                            action="{{ secure_url('deleteUploadRecords') }}"
                            method="POST"
                            style="display: none;">
                            @csrf
                            <input type="hidden"
                                name="filename"
                                value="{{ $log->file_name }}">
                        </form>
                        </td>
                        <td>{{ $log->file_name }}</td>
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