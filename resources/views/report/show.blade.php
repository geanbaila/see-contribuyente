@extends('layout.layout')
@section('head')
    <script src="https://code.highcharts.com/highcharts.js"></script>
@endsection
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <div class="row">
                    <div class="col">
                        <table border="1"
                            class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                            <thead class="border-gray-200 fw-bold bg-lighten">
                                <tr>
                                    <td scope="row" width="10"></td>
                                    <th scope="col" width="100"></th>
                                    <th scope="col" width="100">Cuenta 1</th>
                                    <th scope="col" width="100">Cuenta 2</th>
                                    <th scope="col" width="100">Cuenta 3</th>
                                    <th scope="col" width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prg_ingresos as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $item->fecha }}</td>
                                     
                                        <td>{{ $item->cuenta1_ingreso }}</td>
                                        <td>{{ $item->cuenta1_ingreso }}</td>
                                        <td>{{ $item->cuenta1_ingreso }}</td>
                                        <td>{{ $item->cuenta1_ingreso *3 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            
                        </table>
                    </div>
                    <div class="col" id="torta-mensual"></div>
                </div>
                <div class="row">
                    <div class="col" id="barra-mensual"></div>
                    <div class="col" id="barra-anual"></div>
                </div>

            </div>
        </div>
    </div>

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <!--end::Container-->
@endsection
@section('scripts')
    <script>
        Highcharts.chart('barra-mensual', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Ingresos Mensuales'
            },
            xAxis: {
                categories: [
                    'ene',
                    'feb',
                    'mar',
                    'abr',
                    'may',
                    'jun',
                    'jul',
                    'ago',
                    'sep',
                    'oct',
                    'nov',
                    'dic'
                ],
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'miles'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Cuenta 1',
                data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

            }, {
                name: 'Cuenta 2',
                data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

            }, {
                name: 'Cuenta 3',
                data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

            }]
        });



        Highcharts.chart('barra-anual', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Ingresos Mensuales'
            },
            xAxis: {
                categories: ['ago', 'sep', 'oct', 'nov', 'dic'],
                title: {
                    text: null
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'miles',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ' millions'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Year 1800',
                data: [107, 31, 635, 203, 2]
            }, {
                name: 'Year 1900',
                data: [133, 156, 947, 408, 6]
            }, {
                name: 'Year 2000',
                data: [814, 841, 3714, 727, 31]
            }, {
                name: 'Year 2016',
                data: [1216, 1001, 4436, 738, 40]
            }]
        });




        // Build the chart
        Highcharts.chart('torta-mensual', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Representación de ingresos'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Brands',
                colorByPoint: true,
                data: [{
                    name: 'Cuenta 1',
                    y: 61.41,
                    sliced: true,
                    selected: true
                }, {
                    name: 'Cuenta 2',
                    y: 11.84
                }, {
                    name: 'Cuenta 3',
                    y: 10.85
                }]
            }]
        });
    </script>
@endsection
