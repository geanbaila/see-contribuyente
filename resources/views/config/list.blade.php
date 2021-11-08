@extends('layout.layout')
@section('content')
    <style>
        .box {
            font-weight: 800;
            border: 2px solid #029EF7;
            background-color: #C6DAFC;
            border-radius: .5em;
            padding: 10px;
            cursor: move;
            width: 90px;
            height: 44px;
        }
        .box.over {
            border: 2px dotted #029EF7;
            width: 90px;
            height: 44px;
        }
        .saved {
            border: 2px dotted green;
            background-color: rgba(179, 228, 179, 0.979);
        }

        .domingo {
            background-color: #CCCCFF;
        }
        .lunes {
            background-color: #6495ED;
        }
        .martes {
            background-color: #FF7F50;
        }
        .miercoles {
            background-color: #40E0D0;
        }
        .jueves {
            background-color: #9FE2BF;
        }
        .viernes {
            background-color: #DE3163;
        }
        .sabado {
            background-color: #FFBF00;
        }
        .focus {
            background-color: gray;
        }

    </style>

    <div class="container">
        <div class="card mb-12 mb-xl-12">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bolder fs-3 mb-1">Programación de viajes</span>
                    <span class="text-muted mt-1 fw-bold fs-7">Over 500 members</span>
                </h3>
            </div>
            <!--end::Header-->
            <div class="card-body py-3">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            @foreach ($vehiculo as $item)
                                <td>
                                    <div draggable="true" class="box"></div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                    <!--begin::Table-->
                    <table class="table table-row-dashed table-row-gray-300 align-middle">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder">
                                <th class="min-w-100px">Terminal</th>
                                <th class="min-w-80px">Horario</th>
                                <th class="min-w-120px">Lunes</th>
                                <th class="min-w-120px">Martes</th>
                                <th class="min-w-120px">Miércoles</th>
                                <th class="min-w-120px">Jueves</th>
                                <th class="min-w-120px">Viernes</th>
                                <th class="min-w-120px">Sábado</th>
                                <th class="min-w-120px">Domingo</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($salida as $item)
                            <?php
                            $w = date("w");
                            $ww = ($w ==6) ? 0 : $w+1;
                            ?>
                            <tr>
                                <td>{{$item->agencias->nombre}}</td>
                                <td><input type="text" class="form-control input-sm" name="{{$item->id}}_horario" value="{{$item->horario}}"/></td>
                                <td class="dia" data-dia="1" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 1 || $ww == 1)
                                    <div draggable="true" class="box lunes">{{$item->lunes}}
                                        <input type="hidden" name="placa" value="{{$item->lunes}}">
                                    </div>
                                    @else
                                        {{$item->lunes}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="2" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 2 || $ww == 2)
                                    <div draggable="true" class="box martes">{{$item->martes}}
                                        <input type="hidden" name="placa" value="{{$item->martes}}">
                                    </div>
                                    @else
                                        {{$item->martes}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="3" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 3 || $ww == 3)
                                    <div draggable="true" class="box miercoles">{{$item->miercoles}}
                                        <input type="hidden" name="placa" value="{{$item->miercoles}}">
                                    </div>
                                    @else
                                        {{$item->miercoles}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="4" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 4 || $ww == 4)
                                    <div draggable="true" class="box jueves">{{$item->jueves}}
                                        <input type="hidden" name="placa" value="{{$item->jueves}}">
                                    </div>
                                    @else
                                        {{$item->jueves}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="5" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 5 || $ww == 5)
                                    <div draggable="true" class="box viernes">{{$item->viernes}}
                                        <input type="hidden" name="placa" value="{{$item->viernes}}">
                                    </div>
                                    @else
                                        {{$item->viernes}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="6" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 6 || $ww == 6)
                                    <div draggable="true" class="box sabado">{{$item->sabado}}
                                        <input type="hidden" name="placa" value="{{$item->sabado}}">
                                    </div>
                                    @else
                                        {{$item->sabado}}
                                    @endif
                                </td>
                                <td class="dia" data-dia="0" data-agencia="{{$item->agencia}}" data-salida="{{$item->id}}">
                                    @if ($w == 0 || $ww == 0)
                                    <div draggable="true" class="box domingo">{{$item->domingo}}
                                        <input type="hidden" name="placa" value="{{$item->domingo}}">
                                    </div>
                                    @else
                                        {{$item->domingo}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <a class="btn btn-primary" onclick="javascript:doit();">Confirmar</a>
                
            </div>
        </div>

    </div>

    <script>
        function doit() {
            $(".dia").each(function(index, element) {
                if($(element).has("input[name='placa']").length == 1) {
                    var data = new FormData();
                    var placa = $(element).find("input[name='placa']").val();
                    var dia = $(element).data("dia");
                    var agenciaId = $(element).data("agencia");
                    var salidaId = $(element).data("salida");
                    var n = $(element).data("horario");
                    var horario = $("[name='"+salidaId+"_horario']").val();
                    console.log("'"+salidaId+"_horario'");
                    data.append("salidaId", salidaId);
                    data.append("agenciaId", agenciaId);
                    data.append("placa", placa);
                    data.append("horario", horario);
                    data.append("dia", dia);
                    console.log({"salidaId": salidaId, "agenciaId": agenciaId, "placa": placa, "dia": dia, "horario": horario});
                    $.ajax({
                        url: "{{ url('/configuracion/salida') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        data: data
                    }).done(function(result) {
                        if (result && result.result.status === "OK") {
                            console.log(result.result);
                            $(element).children().addClass("saved");
                        }
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            function handleDragStart(e) {
                this.style.opacity = '0.4';

                dragSrcEl = this;

                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
            }

            function handleDragEnd(e) {
                this.style.opacity = '1';
                
                items.forEach(function(item) {
                    item.classList.remove('over');
                });
            }

            function handleDragOver(e) {
                if (e.preventDefault) {
                    e.preventDefault();
                }

                return false;
            }

            function handleDragEnter(e) {
                this.classList.add('over');
            }

            function handleDragLeave(e) {
                this.classList.remove('over');
            }

            let items = document.querySelectorAll('.container .box');
            items.forEach(function(item) {
                item.addEventListener('dragstart', handleDragStart);
                item.addEventListener('dragover', handleDragOver);
                item.addEventListener('dragenter', handleDragEnter);
                item.addEventListener('dragleave', handleDragLeave);
                item.addEventListener('dragend', handleDragEnd);
                item.addEventListener('drop', handleDrop);
            });
        });

        function handleDrop(e) {
            e.stopPropagation();
            if (dragSrcEl !== this) {
                dragSrcEl.innerHTML = this.innerHTML;
                this.innerHTML = e.dataTransfer.getData('text/html');
            }

            return false;
        }

        {{-- function handleDrop(e) {
            e.stopPropagation(); // Stops some browsers from redirecting.
            e.preventDefault();

            var files = e.dataTransfer.files;
            for (var i = 0, f; f = files[i]; i++) {
                // Read the File objects in this FileList.
                this.innerHTML = f;
            }
        } --}}
    </script>

@endsection
