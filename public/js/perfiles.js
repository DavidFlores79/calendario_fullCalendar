var app = angular.module('perfiles', []);

app.controller('perfiles', function ($scope, $http) {
    $scope.createForm = {};
    $scope.formulario_crear = [];
    $scope.formulario_editar = [];
    $scope.dato = {};
    $scope.datos = [];
    
    $http({
        url: 'get-perfiles',
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    }).then(
        function successCallback(response) {
            console.log('index', response);
            $scope.datos = response.data.datos;
            console.log($scope.datos);
        },
        function errorCallback(response) {
            console.log(response);
            if (response.status === 422) {
                let mensaje = '';
                for (let i in response.data.errors) {
                    mensaje += response.data.errors[i] + '\n';
                }
                swal('Mensaje del Sistema', mensaje, 'error');
            } else {
                swal(
                    'Mensaje del Sistema',
                    response.data.message,
                    response.data.status
                );
            }
        }
    );

    $scope.create = function () {

        $http({
            url: 'perfiles/create',
            method: 'POST',
            data: {
                formulario_crear: 'perfiles_crear'
            },
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(
            function successCallback(response) {
                console.log(response);
                $scope.formulario_crear = response.data.formulario_crear;
                $scope.createForm.estatus = true;
                $scope.createForm.visible = true;
                $('#agregarModal').modal('show');
                // setTimeout(() => {
                //     $('#createForm').trigger('reset');
                // }, 500);
            },
            function errorCallback(response) {
                console.log(response);
                if (response.status === 422) {
                    let mensaje = '';
                    for (let i in response.data.errors) {
                        mensaje += response.data.errors[i] + '\n';
                    }
                    swal('Mensaje del Sistema', mensaje, 'error');
                } else {
                    swal(
                        'Mensaje del Sistema',
                        response.data.message,
                        response.data.status
                    );
                }
            }
        );
    }

    $scope.store = function () {
        // console.log('name:', $scope.createForm);
        // return;
        $http({
            url: 'perfiles',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            data: $scope.createForm
        }).then(
            function successCallback(response) {
                console.log(response);
                $scope.datos = [...$scope.datos, response.data.dato];
                $scope.createForm = {};
                $('#createForm').trigger('reset');
                $('#agregarModal').modal('hide');
                swal(
                    'Mensaje del Sistema',
                    response.data.message,
                    response.data.status
                );
            },
            function errorCallback(response) {
                console.log(response);
                //$('#agregarModal').modal('hide');
                
                if (response.status === 422) {
                    let mensaje = '';
                    for (let i in response.data.errors) {
                        mensaje += response.data.errors[i] + '\n';
                    }
                    swal('Mensaje del Sistema', mensaje, 'error');
                } else {
                    swal(
                        'Mensaje del Sistema',
                        response.data.message,
                        response.data.status
                    );
                }
                
            }
        );
    }

    $scope.edit = function (dato) {
        $scope.editForm = {};
        if(dato.nombre) $scope.editForm['nombre'] = dato.nombre;
        if(dato.id) $scope.editForm['id'] = dato.id;
        $scope.editForm.estatus = (dato.estatus) ? true:false;
        $scope.editForm.visible = (dato.visible) ? true:false;

        $http({
            url: 'perfiles/edit',
            method: 'POST',
            data: {
                formulario_editar: 'perfiles_editar'
            },
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(
            function successCallback(response) {
                console.log(response);
                $scope.formulario_editar = response.data.formulario_editar;
                $('#editarModal').modal('show');
            },
            function errorCallback(response) {
                console.log(response);
                if (response.status === 422) {
                    let mensaje = '';
                    for (let i in response.data.errors) {
                        mensaje += response.data.errors[i] + '\n';
                    }
                    swal('Mensaje del Sistema', mensaje, 'error');
                } else {
                    swal(
                        'Mensaje del Sistema',
                        response.data.message,
                        response.data.status
                    );
                }
            }
        );
    }

    $scope.update = function () {

        $http({
            url: `perfiles`,
            method: 'PUT',
            data: $scope.editForm,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(
            function successCallback(response) {
                console.log('response: ', response);
                $scope.dato = response.data.perfil;
                $scope.datos = $scope.datos.map(dato => (dato.id == response.data.dato.id) ? dato = response.data.dato : dato);
                $('#editarModal').modal('hide');
                swal(
                    'Mensaje del Sistema',
                    response.data.message,
                    response.data.status
                );
            },
            function errorCallback(response) {
                console.log(response);
                if (response.status === 422) {
                    let mensaje = '';
                    for (let i in response.data.errors) {
                        mensaje += response.data.errors[i] + '\n';
                    }
                    swal('Mensaje del Sistema', mensaje, 'error');
                } else {
                    swal(
                        'Mensaje del Sistema',
                        response.data.message,
                        response.data.status
                    );
                }
            }
        );
    }

    $scope.confirmarEliminar = function (dato) {
        $scope.dato = dato;
        $('#nombre-dato').html(dato.nombre);
        $('#eliminarModal').modal('show');
    }

    $scope.delete = function () {
        console.log('perfil: ', $scope.dato);

        $http({
            url: `perfiles/${$scope.dato.id}`,
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(
            function successCallback(response) {
                console.log(response);
                $scope.datos = $scope.datos.filter(dato => dato.id !== $scope.dato.id);
                $('#eliminarModal').modal('hide');
                swal(
                    'Mensaje del Sistema',
                    response.data.message,
                    response.data.status
                );
            },
            function errorCallback(response) {
                console.log(response);
                swal(
                    'Mensaje del Sistema',
                    response.data.message,
                    response.data.status
                );
            }
        );
        
    }

    $('#editarModal').on('hidden.bs.modal', function () {
        console.log('haz algo');
    });
});

app.filter('activoInactivo', function() {
    return function(input) {
        return input ? 'Activo' : 'Inactivo';
    }
});
app.filter('siNo', function() {
    return function(input) {
        return input ? 'Si' : 'No';
    }
});