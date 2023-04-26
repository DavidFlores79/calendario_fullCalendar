@extends('layouts.main')

@section('page-title', 'Inicio')
@section('ngApp', 'home')
@section('ngController', 'home')

@section('content')
<div class="">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="" id="calendar"></div>
                </div>
            </div>
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
            <div class="modal-body text-center">
                ¿Realmente desea cambiar en evento <span id="event-title" class="font-weight-bold"></span>
                al <span id="event-start" class="font-weight-bold"></span> ?
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
                        <label for="title">Título</label>
                        <select ng-model="createForm.centers" required id="selectId" class="form-control show-tick selectpicker" data-style="'btn-outline-success'" title="Selecciona..." multiple data-actions-box="true" data-live-search="true" data-size="6">
                            <option value="@{{centro.idcentro}}" data-subtext="@{{ centro.cebe }}" ng-repeat="centro in centros">@{{ centro.idcentro }} - @{{centro.nombre}}</option>
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
                <h5 class="modal-title" id="confirmDeleteModalLabel">Drag & Drop</h5>
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