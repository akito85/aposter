@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-gender"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-age"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-rc"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-education"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-echelon"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <canvas id="chart-pass"></canvas>
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
            "#d8e2dc",
            "#ffe5d9",
            "#ffcad4",
            "#f4acb7",
            "#eae2b7"
            ],
            borderColor: [
            "#d8e2dc",
            "#ffe5d9",
            "#ffcad4",
            "#f4acb7",
            "#eae2b7"
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
  $(function(){
    initiateChart("chart-gender", "bar", "Peserta Berdasarkan Gender", `<?php echo $gender; ?>`);
    initiateChart("chart-age", "bar", "Peserta Berdasarkan Umur", `<?php echo $age; ?>`);
    initiateChart("chart-rc", "horizontalBar", "Peserta Berdasarkan Pangkat / Golongan", `<?php echo $rc; ?>`);
    initiateChart("chart-education", "bar", "Peserta Berdasarkan Pendidikan", `<?php echo $education; ?>`);
    initiateChart("chart-echelon", "bar", "Peserta Berdasarkan Jenjang", `<?php echo $echelon; ?>`);
    initiateChart("chart-pass", "horizontalBar", "Peserta Berdasarkan Kelulusan", `<?php echo $pass; ?>`);
  });
</script>
@endsection
