@extends('layout.layout')
@section('content')
    <style>
        .box {
            font-weight: 800;
            border: 3px solid #666;
            background-color: #ddd;
            border-radius: .5em;
            padding: 10px;
            cursor: move;
            width: 100px;
            height: 44px;
        }
        .box.over {
            border: 3px dotted #666;
            width: 100px;
            height: 44px;
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
                                    <div draggable="true" class="box">{{ $item->placa }}</div>
                                </td>
                            @endforeach
                        </tr>
                    </table>
                    <!--begin::Table-->
                    <table class="table table-row-dashed table-row-gray-300 align-middle">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder text-muted">
                                <th class="min-w-150px">Terminal</th>
                                <th class="min-w-140px">Horario</th>
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
                            @foreach ($agencia as $item)
                                <?php $total = count($item->horario);
                                $total_agencia = count($agencia); ?>
                                <tr>
                                    <td rowspan="{{ $total }}">{{ $item->nombre }}</td>
                                    <td>
                                        <table>
                                            @foreach ($item->horario as $key => $horario)
                                                <tr>
                                                    <td>{{ $horario['horario_predeterminado'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $horario['horario_alternativo'] }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                    @for ($i = 0; $i < 7; $i++)
                                        <td>
                                            <table>
                                                @foreach ($item->horario as $key => $horario)
                                                    <tr>
                                                        <td>
                                                            <div draggable="true" class="box"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div draggable="true" class="box"></div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                            </body>
                        </tbody>
                    </table>
                </div>

                <a class="btn btn-primary" onclick="javascript:doit();">Confirmar</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-primary" onclick="javascript:doit();">Autocompletar</a>
            </div>
        </div>

    </div>

    <script>
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
