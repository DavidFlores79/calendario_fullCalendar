@extends('layouts.main')

@section('page-title', 'Inicio')
@section('ngApp', 'home')
@section('ngController', 'home')

@section('content')
<div ng-cloak class="main mx-auto col-xl-7 col-lg-7 col-sm-12 mb-4">
    <div class="card contenedor">
        <div class="card-header bg-default d-md-flex justify-content-between ">
            <h5 class="font-weight-bold centers-title">@yield('page-title')</h5>
            <input type="text" name="buscar" class="search-query form-control col-lg-3 col-md-4 col-sm-12" placeholder="Buscar..." ng-model="searchQuery">
        </div>
        <div class="card-body">
            <div class="" id="calendar"></div>
        </div>
    </div>

</div>

<!-- Modal Actualizar -->
<div class="modal fade" id="confirmUpdateModal" tabindex="-1" aria-labelledby="confirmUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmUpdateModalLabel">Drag & Drop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">¿Realmente desea cambiar este evento?</div>
                <div class="">Evento: <span class="font-weight-bold" id="event-title"></span></div>
                <div class="">Inicio: <span class="font-weight-bold" id="event-start"></span></div>
                <div class="">Fin: <span class="font-weight-bold" id="event-end"></span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="update()">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Crear Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form ng-submit="create()" class="was-validated">
                    <div class="form-group">
                        <label for="title">Título</label>
                        <input type="text" minlength="3" name="title" id="title" class="form-control" ng-model="createForm.title" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="color">Color</label>
                        <select ng-model="createForm.color" required class="form-control show-tick selectpicker" data-style="'btn-outline-success'" title="Color..." data-actions-box="true" data-live-search="true" data-size="6">
                            <option value="blue">Azul</option>
                            <option value="red">Rojo</option>
                            <option value="purple">Morado</option>
                            <option value="green">Verde</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </div>
        </form>
    </div>
</div>

<!-- Modal Actualizar -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Eliminar Evento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                ¿Realmente desea eliminar el evento <span id="event-title-delete" class="font-weight-bold"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" ng-click="delete()">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ngFile')
<script src="{{ asset('js/home.js') }}"></script>
@endsection