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
  $(function(){
    /** can be refactored and simplified **/

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $gender; ?>`);
      var ctx = $("#chart-gender");

      // chart data
      var data = {
        labels: ["PRIA", "WANITA"],
        datasets: [
          {
            label: "Data Jenis Kelamain",
            data: [cData.PRIA, cData.WANITA],
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_gender = new Chart(ctx, {
        type: "bar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $age; ?>`);
      var ctx = $("#chart-age");

      // chart data
      var data = {
        labels: ['1 - 20', '21 - 30', '31 - 40', '41 - 50', '51 - 60'],
        datasets: [
          {
            label: "Umur Peserta",
            data: [cData.T1, cData.T2, cData.T3, cData.T4, cData.T5],
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_age = new Chart(ctx, {
        type: "bar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $rc; ?>`);
      var ctx = $("#chart-rc");

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
            label: "Pangkat / Golongan Peserta",
            data: values,
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_rc = new Chart(ctx, {
        type: "horizontalBar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $education; ?>`);
      var ctx = $("#chart-education");

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
            label: "Pendidikan",
            data: values,
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_education = new Chart(ctx, {
        type: "bar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $echelon; ?>`);
      var ctx = $("#chart-echelon");

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
            label: "Eselon dan non-eselon",
            data: values,
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_echelon = new Chart(ctx, {
        type: "horizontalBar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });

      // get the chart canvas
      var cData = JSON.parse(`<?php echo $pass; ?>`);
      var ctx = $("#chart-pass");

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
            label: "Kelulusan",
            data: values,
            backgroundColor: [
              "#DEB887",
              "#A9A9A9",
            ],
            borderColor: [
              "#CDA776",
              "#989898",
            ],
            borderWidth: [1, 1]
          }
        ]
      };

      //options
      var options = {
        responsive: true,
        title: {
          display: true,
          position: "top",
          text: "{{ $training }}",
          fontSize: 17,
          fontColor: "#111"
        },
        legend: {
          display: true,
          position: "bottom",
          labels: {
            fontColor: "#333",
            fontSize: 16
          }
        },
        plugins: {
          datalabels: {
            anchor: "center",
            clamp: true
          }
        }
      };

      // create Pie Chart class object
      var chart_pass = new Chart(ctx, {
        type: "horizontalBar",
        plugins: [ChartDataLabels],
        data: data,
        options: options
      });
  });
</script>
@endsection
