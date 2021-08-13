@extends('layouts.master')

@section('custom_css')
    .chart-gender, .data-gender,
    .chart-age, .data-age,
    .chart-rc, .data-rc,
    .chart-education, .data-education,
    .chart-echelon, .data-echelon,
    .chart-pass, .data-pass
    {
        color: #ffffff !important;
    }
    .select2-container {max-height: 41px;}
    canvas {
        height: 666px !important;
    }
@endsection

@section('content')
<br>
<br>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="card mt-3 mb-1">
                <div class="card-body">
                    <label>Periode</label>
                    <form>
                        <div class="form-group" style="margin-bottom: 0;">
                            <input id="periode" type="text" name="daterange" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="card mt-3 mb-1">
                <div class="card-body">
                    <label>Filter By Pelatihan</label>
                    <select class="training-list form-control form-control-lg">
                        <option value="ALL">ALL</option>
                        @if($trainingList)
                            @foreach ($trainingList as $tl)
                                @if (trim($tl->trx_id) == $training)
                                <option value="{{ trim($tl->trx_id) }}" selected>{{ trim($tl->trx_name) }}</option>
                                @else
                                <option value="{{ trim($tl->trx_id) }}">{{ trim($tl->trx_name) }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <!--
        <div class="col-lg-3 col-md-12 col-sm-12">
            <div class="card mt-3 mb-1">
                <div class="card-body">
                    <label>Filter By Kementerian / Unit / Badan</label>
                    <select class="training-list form-control form-control-lg">
                        <option value="ALL">ALL</option>
                        @if($trainingList)
                            @foreach ($trainingList as $tl)
                                @if (trim($tl->trx_id) == $training)
                                <option value="{{ trim($tl->trx_id) }}" selected>{{ trim($tl->trx_name) }}</option>
                                @else
                                <option value="{{ trim($tl->trx_id) }}">{{ trim($tl->trx_name) }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        -->
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chart-evagara"></canvas>
                        <a href="javascript:void(0)" class="btn btn-primary chart-main-unit">Unduh Diagram</a>
                        <a href="javascript:void(0)" class="btn btn-primary data-main-unit">Lihat Data</a>
                    </div>
                </div>
            </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-main-unit"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-main-unit">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-main-unit">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-gender"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-gender">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-gender">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-age"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-age">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-age">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-rc"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-rc">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-rc">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-education"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-education">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-education">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-echelon"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-echelon">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-echelon">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-pass"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-pass">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-pass">Lihat Data</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="chart-position"></canvas>
                    <a href="javascript:void(0)" class="btn btn-primary chart-pass">Unduh Diagram</a>
                    <a href="javascript:void(0)" class="btn btn-primary data-pass">Lihat Data</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
function initiateChart(chartElement, chartType, chartTitle, chartData, chartSteps) {
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
                    "#845ec2", "#d65db1", "#ff6f91", "#ff9671", "#ffc75f", "#9d0208",
                    "#1983E3", "#C2BA7D", "#E69C48", "#DA476B", "#231B34", "#6a040f",
                    "#898B93", "#E1C871", "#AF3A70", "#251933","#9DB669", "#C16EB5",
                    "#EF2E6C", "#666D77", "#26292B",
                    // "#d8e2dc","#ffe5d9","#ffcad4","#f4acb7","#eae2b7",
                    // "#ffb5a7","#fcd5ce","#f8edeb","#f9dcc4","#fec89a",
                    // "#03071e", "#370617", "#9d0208", "#d00000", "#dc2f02",
                    // "#f48c06", "#f48c06", "#faa307", "#ffba08"
                ]
            }
        ]
    };

    //options
    var options = {
        responsive: true,
        maintainAspectRatio: false,
        title: {
            display: true,
            position: "top",
            text: chartTitle,
            fontSize: 17,
            fontColor: "#111",
            padding: 29
        },
        legend: {
            display: false,
        },
        layout: {
            padding: {
                left: 3,
                right: 33,
                top: 3,
                bottom: 3
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    // stepSize: chartSteps,
                    fontColor: "#000"
                },
                offset: true,
                gridLines: {
                    offsetGridLines: false
                },
                scaleLabel: {
                    fontColor: "#000",
                    fontSize: 13
                }
            }],
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    // stepSize: chartSteps,
                    fontColor: "#000"
                },
                offset: true,
                gridLines: {
                    offsetGridLines: false
                },
                scaleLabel: {
                    fontColor: "#000",
                    fontSize: 13
                }
            }],
        },
        plugins: {
            datalabels: {
                color: "#000",
                anchor:"end",
                align: "end",
                clamp: true,
                font: {
                    weight: "bold",
                    size: 12
                }
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
// var thePath = window.location.pathname;
//     thePath = thePath.substring(thePath.lastIndexOf('/') + 1);

// var steps = (thePath == "ALL") ? 200 : 3;
var steps;

    initiateChart("chart-gender", "bar", "Peserta Berdasarkan Gender", `<?php echo $gender; ?>`, steps);
    initiateChart("chart-main-unit", "horizontalBar", "Peserta Berdasarkan Unit Utama Kerja (ES I)", `<?php echo $main_unit; ?>`, steps);
    initiateChart("chart-age", "bar", "Peserta Berdasarkan Umur", `<?php echo $age; ?>`, steps);
    initiateChart("chart-rc", "horizontalBar", "Peserta Berdasarkan Pangkat / Golongan", `<?php echo $rc; ?>`, steps);
    initiateChart("chart-education", "bar", "Peserta Berdasarkan Pendidikan", `<?php echo $education; ?>`, steps);
    initiateChart("chart-echelon", "bar", "Peserta Berdasarkan Jenjang", `<?php echo $echelon; ?>`, steps);
    initiateChart("chart-pass", "horizontalBar", "Peserta Berdasarkan Kelulusan", `<?php echo $pass; ?>`, steps);
    initiateChart("chart-position", "horizontalBar", "Peserta Deskripsi Jabatan", `<?php echo $positionDesc; ?>`, steps);

var x = <?php echo $evagara; ?>;

if(x !== undefined || x.length != 0 || x !== "") {
    x = JSON.parse(x);

    if(x > 0) {
        var ctxEvagara = document.getElementById('chart-evagara');
        var chartEvagara = new Chart(ctxEvagara, {
        type: 'bar',
        data: {
            labels: [
                        ["Kesesuaian materi", "pembelajaran dengan", "harapan kebutuhan peserta"],
                        ["Bahan ajar mudah", "dipahami"],
                        ["Kesesuaian metode", "pembelajaran", "dengan materi", "Pelatihan Jarak Jauh"],
                        ["Ketercukupan waktu", "penyelenggaraan", "Pelatihan Jarak Jauh dengan", "jumlah materi yang diberikan"],
                        ["Kesigapan penyelenggara", "dalam melayani", "peserta selama proses", "Pelatihan Jarak Jauh"],
                        ["Ketercukupan waktu dalam ", "mengerjakan penugasan,", "kuis, atau ujian"],
                        ["Fasilitas PJJ", "mudah diakses"],
                        ["Fasilitas PJJ", "mudah digunakan"]
                    ],
            datasets: [
                {
                    label: 'Kenyataan Pelatihan',
                    data: x.x.x,
                    backgroundColor: "#f4acb7"
                },
                {
                    label: 'Harapan Peserta',
                    data: x.y.y,
                    backgroundColor: "#eae2b7"
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'EVALUASI PENYELENGGARA'
            }
            }
        },
        });
    }
}

$(".training-list").select2();

$(".training-list").on('select2:select', function (e) {
    var data = e.params.data;
    var start = `<?php echo $start; ?>`;
    var end = `<?php echo $end; ?>`;
        window.location = "/dashboard/" + start + "/" + end + "/" + data.id;
});

$('input[name="daterange"]').daterangepicker({
    timePicker: false,
    startDate: `<?php echo $start; ?>`,
    endDate: `<?php echo $end; ?>`,
    locale: {
        format: 'YYYY-MM-DD'
    }
});

$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
  var start = picker.startDate.format('YYYY-MM-DD');
  var end = picker.endDate.format('YYYY-MM-DD');
  window.location = "/dashboard/" + start + "/" + end + "/ALL";
});
</script>
@endsection
