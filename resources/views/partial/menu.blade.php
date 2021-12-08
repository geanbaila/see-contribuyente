<div class="menu-item me-lg-1">
    <a class="menu-link py-3" href="{{ url('/') }}">
        <span class="menu-title">DASHBOARD</span>
    </a>
</div>

<div class="menu-item me-lg-1">
    <a class="menu-link py-3 {{(isset($menu_venta_active))?"active":""}}" href="{{ url('/venta') }}">
        <span class="menu-title">ENCARGOS</span>
    </a>
</div>
<div class="menu-item me-lg-1">
    <a class="menu-link py-3 {{(isset($menu_despacho_active))?"active":""}}" href="{{ url('/despacho') }}">
        <span class="menu-title">DESPACHOS</span>
    </a>
</div>
<div class="menu-item me-lg-1">
    <a class="menu-link py-3 {{(isset($menu_manifiesto_active))?"active":""}}" href="{{ url('/manifiesto') }}">
        <span class="menu-title">MANIFIESTO</span>
    </a>
</div>
<div class="menu-item me-lg-1">
    <a class="menu-link py-3 {{(isset($menu_configuracion_active))?"active":""}}" href="{{ url('/configuracion') }}">
        <span class="menu-title">CONFIGURACIÓN</span>
    </a>
</div>
<!--begin::Breadcrumb-->
{{-- <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
	<li class="breadcrumb-item text-dark">
		<a href="{{url('/venta')}}" class="btn btn-sm btn-secondary">Cargas y encomiendas</a>
	</li>
	<li class="breadcrumb-item text-dark">
		<a href="{{url('/manifiesto')}}" class="btn btn-sm btn-secondary">
			Manifiesto de carga
		</a>
	</li>
	<li class="breadcrumb-item text-dark">
		<a href="#" class="btn btn-sm btn-secondary">Gestión de documentos</a>
	</li>
	<li class="breadcrumb-item text-dark">
		<a href="#" class="btn btn-sm btn-secondary">
			Número de faltantes
		</a>
	</li>
	<li class="breadcrumb-item text-dark">
		<a href="#" class="btn btn-sm btn-secondary">
			Facturación electrónica
		</a>
	</li>
</ul> --}}
<!--end::Breadcrumb-->
