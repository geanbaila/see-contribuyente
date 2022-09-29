  <!--begin::Modal - modalPrepararComprobante-->
  <div class="modal fade" id="modalPrepararComprobante" tabindex="-1" aria-hidden="true">
      <!--begin::Modal dialog-->
      <div class="modal-dialog modal-xl modal-dialog-centered">
          <!--begin::Modal content-->
          <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                  <!--begin::Modal title-->
                  <h2>Generar comprobante de pago electrónico</h2>
                  <!--end::Modal title-->
                  <!--begin::Close-->
                  <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black" />
                          </svg>
                      </span>
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body py-lg-10 px-lg-10">
                  <!--begin::Stepper-->
                  <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                      id="kt_modal_create_app_stepper">
                      <!--begin::Content-->
                      <div class="flex-row-fluid">
                          <!--begin::Form-->
                          <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                            <input type="hidden" name="encargo_id">
                              <div class="row gy-5">
                                  <div class="col-2">
                                      <label for="exampleDataList" class="form-label">Documento:</label>
                                      <select class="form-select" aria-label="--" id="documento" name="documento">
                                          <option value="--" selected>--</option>
                                          <option value="1">BOLETA</option>
                                          <option value="2">FACTURA</option>
                                      </select>
                                  </div>
                                  <div class="col-2">
                                      <label class="form-label">Recibe:</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control" name="doc_recibe"
                                              value="" />
                                          <div class="input-group-append">
                                              <span class="input-group-text" id="basic-addon2">
                                                  &nbsp;<img class="loading-input2" style="display:none"
                                                      src="{{ asset('public/assets/media/loading.gif') }}"
                                                      width="20" />
                                              </span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-4">
                                      <label class="form-label">&nbsp;</label>
                                      <input type="text" class="form-control" id="nombre_recibe" name="nombre_recibe"
                                          value="" disabled />
                                  </div>
                                  <div class="col-4">
                                      <label for="exampleDataList" class="form-label">&nbsp;</label>
                                      <div class="form-floating">
                                          <button type="button" class="btn btn-lg btn-primary"
                                              data-kt-stepper-action="next"
                                              onclick="javascript:crearComprobante();" id="btnPagarModal">Pagar</button>
                                          @php
                                              $img_enable = isset($encargo) ? '-white' : '';
                                              $btn_enable = isset($encargo) ? 'btn-primary' : 'btn-secondary disabled';
                                          @endphp
                                          <a id="btnImprimir" class="btn {{ $btn_enable }}" data-bs-toggle="modal"
                                              data-bs-target="#modalImprimirComprobante"
                                              onclick="javascript:printElement()">
                                              <img src="{{ asset('public/assets/media/printer' . $img_enable . '.svg') }}"
                                                  width="20" />
                                          </a>
                                          <a id="btnEmail" class="btn {{ $btn_enable }}" data-bs-toggle="modal"
                                              data-bs-target="#modalEnviarEmail">
                                              <img src="{{ asset('public/assets/media/email' . $img_enable . '.svg') }}"
                                                  width="20" />
                                          </a>
                                      </div>
                                  </div>
                              </div>

                              <br>
                              <br>
                              <div class="row gy-5">
                                  <table
                                      class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                                      <thead class="border-gray-200 fw-bold bg-lighten">
                                          <tr>
                                              <th scope="col">Envía</th>
                                              <th scope="col">Recibe</th>
                                              <th scope="col">Destino</th>
                                              <th scope="col">Fecha de registro</th>
                                              <th scope="col">Por pagar </th>
                                              <th scope="col">Pagado </th>
                                          </tr>
                                      </thead>
                                      <tbody id="responseChargeRow2">

                                      </tbody>
                                      <tfoot>
                                          <tr>
                                              <td colspan="6" align="center"><img class="loading-modal"
                                                      style="display:none"
                                                      src="{{ asset('public/assets/media/loading.gif') }}"
                                                      width="20" /></td>
                                          </tr>
                                      </tfoot>
                                  </table>
                              </div>

                              <!--begin::Actions-->
                              <div class="d-flex flex-stack pt-10">
                                  <!--begin::Wrapper-->
                                  <div>
                                      <button type="button" class="btn btn-lg btn-primary"
                                          data-kt-stepper-action="submit">
                                          <span class="indicator-label">Submit
                                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                              <span class="svg-icon svg-icon-3 ms-2 me-0">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                      height="24" viewBox="0 0 24 24" fill="none">
                                                      <rect opacity="0.5" x="18" y="13"
                                                          width="13" height="2" rx="1"
                                                          transform="rotate(-180 18 13)" fill="black" />
                                                      <path
                                                          d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                          fill="black" />
                                                  </svg>
                                              </span>
                                              <!--end::Svg Icon-->
                                          </span>
                                          <span class="indicator-progress">Please wait...
                                              <span
                                                  class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                      </button>


                                  </div>
                                  <!--end::Wrapper-->
                              </div>
                              <!--end::Actions-->
                          </form>
                          <!--end::Form-->
                      </div>
                      <!--end::Content-->
                  </div>
                  <!--end::Stepper-->
              </div>
              <!--end::Modal body-->
          </div>
          <!--end::Modal content-->
      </div>
      <!--end::Modal dialog-->
  </div>
  <!--end::Modal - modalPrepararComprobante-->

  <!--begin::Modal - modalBuscarVenta-->
  <div class="modal fade" id="modalBuscarVenta" tabindex="-1" aria-hidden="true">
      <!--begin::Modal dialog-->
      <div class="modal-dialog modal-xl modal-dialog-centered">
          <!--begin::Modal content-->
          <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                  <!--begin::Modal title-->
                  <h2>Buscar Guía de Remisión - Transportista</h2>
                  <!--end::Modal title-->
                  <!--begin::Close-->
                  <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black" />
                          </svg>
                      </span>
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body py-lg-10 px-lg-10">
                  <!--begin::Stepper-->
                  <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                      id="kt_modal_create_app_stepper">
                      <!--begin::Content-->
                      <div class="flex-row-fluid">
                          <!--begin::Form-->
                          <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                              <div class="row gy-5">
                                  <div class="col-3">
                                      <label for="exampleDataList" class="form-label">Recibe / Envía:</label>
                                      <input class="form-control buscaDocumento" id="buscaDocRecibeDocEnvia">
                                  </div>
                                  <div class="col-3">
                                      <label for="exampleDataList" class="form-label">Guía de Remisión:</label>
                                      <input class="form-control buscaDocumento" id="buscaDocumento">
                                  </div>
                                  <div class="col-3">
                                      <label for="exampleDataList" class="form-label">&nbsp;</label>
                                      <button type="button" class="form-control btn btn-lg btn-primary"
                                          data-kt-stepper-action="next"
                                          onclick="javascript:askEncargo();">Consultar</button>
                                  </div>
                              </div>

                              <br>
                              <br>
                              <div class="row gy-5">
                                  <table
                                      class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                                      <thead class="border-gray-200 fw-bold bg-lighten">
                                          <tr>
                                              <th scope="col">Envía</th>
                                              <th scope="col">Recibe</th>
                                              <th scope="col">Destino</th>
                                              <th scope="col">Fecha de registro</th>
                                              <th scope="col">Pagado </th>
                                              <th scope="col">Por pagar </th>
                                              <th scope="col">&nbsp;</th>
                                          </tr>
                                      </thead>
                                      <tbody id="responseChargeRow">

                                      </tbody>
                                      <tfoot>
                                          <tr>
                                              <td colspan="7" align="center"><img class="loading-modal"
                                                      style="display:none"
                                                      src="{{ asset('public/assets/media/loading.gif') }}"
                                                      width="20" /></td>
                                          </tr>
                                      </tfoot>
                                  </table>
                              </div>

                              <!--begin::Actions-->
                              <div class="d-flex flex-stack pt-10">
                                  <!--begin::Wrapper-->
                                  <div>
                                      <button type="button" class="btn btn-lg btn-primary"
                                          data-kt-stepper-action="submit">
                                          <span class="indicator-label">Submit
                                              <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                              <span class="svg-icon svg-icon-3 ms-2 me-0">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                      height="24" viewBox="0 0 24 24" fill="none">
                                                      <rect opacity="0.5" x="18" y="13"
                                                          width="13" height="2" rx="1"
                                                          transform="rotate(-180 18 13)" fill="black" />
                                                      <path
                                                          d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                          fill="black" />
                                                  </svg>
                                              </span>
                                              <!--end::Svg Icon-->
                                          </span>
                                          <span class="indicator-progress">Please wait...
                                              <span
                                                  class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                      </button>


                                  </div>
                                  <!--end::Wrapper-->
                              </div>
                              <!--end::Actions-->
                          </form>
                          <!--end::Form-->
                      </div>
                      <!--end::Content-->
                  </div>
                  <!--end::Stepper-->
              </div>
              <!--end::Modal body-->
          </div>
          <!--end::Modal content-->
      </div>
      <!--end::Modal dialog-->
  </div>
  <!--end::Modal - modalBuscarVenta-->

  <!--begin::Modal - modalEliminarVenta-->
  <div class="modal fade" id="modalEliminarVenta" tabindex="-1" aria-hidden="true">
      <!--begin::Modal dialog-->
      <div class="modal-dialog modal-dialog-centered mw-900px">
          <!--begin::Modal content-->
          <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                  <!--begin::Modal title-->
                  <h2>Eliminar Comprobante de Pago electrónico</h2>
                  <!--end::Modal title-->
                  <!--begin::Close-->
                  <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black" />
                          </svg>
                      </span>
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body py-lg-10 px-lg-10">
                  <!--begin::Stepper-->
                  <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                      id="kt_modal_create_app_stepper">
                      <!--begin::Content-->
                      <div class="flex-row-fluid">
                          <!--begin::Form-->
                          <p class="fs-3">Está a punto de comunicar a SUNAT la baja de comprobantes de pago
                              electrónico.
                              ¿Está seguro que desea continuar?</p>
                          <!--end::Form-->
                      </div>
                      <!--end::Content-->
                  </div>
                  <!--end::Stepper-->
              </div>
              <!--end::Modal body-->
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-danger" id="btnBajaCPE" onclick="javascript:bajaCPE();">Sí,
                      continuar</button>
              </div>
          </div>
          <!--end::Modal content-->
      </div>
      <!--end::Modal dialog-->
  </div>
  <!--end::Modal - modalEliminarVenta-->

  <!--begin::Modal - modalEnviarEmail-->
  <div class="modal fade" id="modalEnviarEmail" tabindex="-1" aria-hidden="true">
      <!--begin::Modal dialog-->
      <div class="modal-dialog modal-dialog-centered mw-900px">
          <!--begin::Modal content-->
          <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                  <!--begin::Modal title-->
                  <h2>Enviar documento electrónico</h2>
                  <!--end::Modal title-->
                  <!--begin::Close-->
                  <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black" />
                          </svg>
                      </span>
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body py-lg-10 px-lg-10">
                  <!--begin::Stepper-->
                  <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                      id="kt_modal_create_app_stepper">
                      <!--begin::Content-->
                      <div class="flex-row-fluid">
                          <!--begin::Form-->
                          <form>
                              <div class="mb-3">
                                  <label for="exampleInputEmail1" class="form-label">E-mail:</label>
                                  <input type="email" class="form-control" name="email_adquiriente"
                                      value="" />
                                  <div id="emailHelp" class="form-text">Se le enviará la documento electrónico.
                                  </div>
                              </div>
                              <button id="btnEnviarEmail" type="button" class="btn btn-primary"
                                  data-bs-dismiss="modal" onclick="javascript:enviarEmail()">Confirmar</button>
                          </form>
                          <!--end::Form-->
                      </div>
                      <!--end::Content-->
                  </div>
                  <!--end::Stepper-->
              </div>
              <!--end::Modal body-->
          </div>
          <!--end::Modal content-->
      </div>
      <!--end::Modal dialog-->
  </div>
  <!--end::Modal - modalEnviarEmail-->

  <!--begin::Modal - modalImprimirComprobante-->
  <div class="modal fade" id="modalImprimirComprobante" tabindex="-1" aria-hidden="true">
      <!--begin::Modal dialog-->
      <div class="modal-dialog modal-dialog-centered mw-900px">
          <!--begin::Modal content-->
          <div class="modal-content">
              <!--begin::Modal header-->
              <div class="modal-header">
                  <!--begin::Modal title-->
                  <h2>Comprobante de pago</h2>
                  <!--end::Modal title-->
                  <!--begin::Close-->
                  <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                      <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                      <span class="svg-icon svg-icon-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="24" viewBox="0 0 24 24"
                              fill="none">
                              <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                  rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                              <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                  transform="rotate(45 7.41422 6)" fill="black" />
                          </svg>
                      </span>
                      <!--end::Svg Icon-->
                  </div>
                  <!--end::Close-->
              </div>
              <!--end::Modal header-->
              <!--begin::Modal body-->
              <div class="modal-body py-lg-10 px-lg-10">
                  <!--begin::Stepper-->
                  <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid"
                      id="kt_modal_create_app_stepper">
                      <!--begin::Content-->
                      <div class="flex-row-fluid">
                          <!--begin::Form-->
                          <form class="form" novalidate="novalidate" id="">
                              <div class="row gy-5">
                                  <div class="col-12">
                                      <embed id="comprobantePago" src="" frameborder="0" width="100%"
                                          height="500px" type="application/pdf">

                                  </div>
                              </div>
                          </form>
                          <!--end::Form-->
                      </div>
                      <!--end::Content-->
                  </div>
                  <!--end::Stepper-->
              </div>
              <!--end::Modal body-->
          </div>
          <!--end::Modal content-->
      </div>
      <!--end::Modal dialog-->
  </div>
  <!--end::Modal - modalImprimirComprobante-->

  <!--begin::Modal - modalAlertarDetraccion-->
  <div class="modal" id="modalAlertarDetraccion" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Aviso de detracción</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>Esta operación está sujeta a una detracción por superar los <b>S/.
                          {{ env('DETRACCION') }}</b><br>¿Está seguro que desea continuar?</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                  <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                      onclick="javascript:enviarDatos(null);">Sí, continuar</button>
              </div>
          </div>
      </div>
  </div>
  <!--end::Modal - modalAlertarDetraccion-->
