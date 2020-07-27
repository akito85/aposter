@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <select class="training-list form-control form-control-lg">
                        <option>ALL</option>
                    @foreach ($trainingList as $tl)
                        @if (trim($tl->training_name) == $training)
                        <option selected>{{ trim($tl->training_name) }}</option>
                        @else
                        <option>{{ trim($tl->training_name) }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="container-fluid">
    <h4 class="justify-content-center">{{ $training }}</h4>
    <hr>
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-gender"></canvas>
                    <a class="btn btn-primary chart-gender">Unduh Diagram</a>
                    <a class="btn btn-primary data-gender">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-age"></canvas>
                    <a class="btn btn-primary chart-age">Unduh Diagram</a>
                    <a class="btn btn-primary data-age">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-rc"></canvas>
                    <a class="btn btn-primary chart-rc">Unduh Diagram</a>
                    <a class="btn btn-primary data-rc">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-education"></canvas>
                    <a class="btn btn-primary chart-education">Unduh Diagram</a>
                    <a class="btn btn-primary data-education">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-echelon"></canvas>
                    <a class="btn btn-primary chart-echelon">Unduh Diagram</a>
                    <a class="btn btn-primary data-echelon">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-pass"></canvas>
                    <a class="btn btn-primary chart-pass">Unduh Diagram</a>
                    <a class="btn btn-primary data-pass">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
function initiateChart(chartElement, chartType, chartTitle, chartData) {
    // get the chart canvas
    var cData = JSON.parse(chartData);
    var ctx = document.getElementById(chartElement);
    var labels = Object.getOwnPropertyNames(cData).sort();
    var values = [];

    for (let i = 0; i < labels.length; i++) {
        values.push(cData[labels[i]]);
    }

    // chart data
    var data = {
        labels: labels,
        datasets: [
            {
                data: values,
                backgroundColor: [
                    "#d8e2dc","#ffe5d9","#ffcad4","#f4acb7","#eae2b7",
                    "#ffb5a7","#fcd5ce","#f8edeb","#f9dcc4","#fec89a",
                    // "#03071e", "#370617", "#6a040f", "#9d0208", "#d00000", "#dc2f02",
                    // "#e85d04", "#f48c06", "#f48c06", "#faa307", "#ffba08"
                ]
            }
        ]
    };

    //options
    var options = {
        responsive: true,
        title: {
            display: true,
            position: "top",
            text: chartTitle,
            fontSize: 17,
            fontColor: "#111"
        },
        legend: {
            display: false,
        },
        plugins: {
            datalabels: {
                anchor: "center",
                clamp: true
            }
        },
        animation: {
            onComplete: function(animation) {
                ctx.nextElementSibling.setAttribute("download", "{{ $training }}" + " - " + chartTitle);
                ctx.nextElementSibling.setAttribute("href", this.toBase64Image());
            }
        }
    };

    // create Pie Chart class object
    var chart = new Chart(ctx, {
        type: chartType,
        plugins: [ChartDataLabels],
        data: data,
        options: options
    });
}

initiateChart("chart-gender", "bar", "Peserta Berdasarkan Gender", `<?php echo $gender; ?>`);
initiateChart("chart-age", "bar", "Peserta Berdasarkan Umur", `<?php echo $age; ?>`);
initiateChart("chart-rc", "horizontalBar", "Peserta Berdasarkan Pangkat / Golongan", `<?php echo $rc; ?>`);
initiateChart("chart-education", "bar", "Peserta Berdasarkan Pendidikan", `<?php echo $education; ?>`);
initiateChart("chart-echelon", "bar", "Peserta Berdasarkan Jenjang", `<?php echo $echelon; ?>`);
initiateChart("chart-pass", "horizontalBar", "Peserta Berdasarkan Kelulusan", `<?php echo $pass; ?>`);

var training = $(".training-list");
training.select2();

$(".training-list").on('select2:select', function (e) {
    var data = e.params.data;
    if(data.text != "ALL") {
        window.location = "/dashboard/" + data.text;
    } else {
        window.location = "/dashboard";
    }
});
</script>
@endsection
