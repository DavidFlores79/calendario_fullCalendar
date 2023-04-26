var app = angular.module('home', []);


app.controller('home', function ($scope, $http) {
    $scope.createForm = {};
    $scope.editForm = {};
    $scope.dato = {};
    $scope.datos = [];
    $scope.centros = [];

    $http({
        url: 'get-all-centros',
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    }).then(
        function successCallback(response) {
            console.log('ok', response);
            $scope.centros = response.data.centros;
            setTimeout(() => {
                $('.selectpicker').selectpicker('deselectAll');
                $('.selectpicker').selectpicker('refresh');
            }, 500);
        },
        function errorCallback(error) {
            console.log('error', error);
        }
    );


    var calendarEl = document.getElementById('calendar');

    $scope.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventColor: '#0C4C91',
        events: 'events',
        navLinks: true,
        editable: true,
        displayEventTime: true,
        eventRender: (info, element, view) => {
            console.log('render info', info);
            if (info.allDay === 'true') {
                info.allDay = true;
            } else {
                info.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: ({ start, end, allDay }) => {

            $('.selectpicker').selectpicker('deselectAll');
            $('.selectpicker').selectpicker('refresh');
            $('.tooltiptopicevent').remove();
            $scope.createForm.start = moment(start, 'DD.MM.YYYY').format('YYYY-MM-DD HH:mm:ss');
            $scope.createForm.end = moment(end, 'DD.MM.YYYY').format('YYYY-MM-DD HH:mm:ss');
            $scope.createForm.allDay = allDay;

            $('#createModal').modal('show');
        },
        eventClick: (info) => {
            $('.tooltiptopicevent').remove();
            $scope.confirmDelete( info.event );
        },
        eventDrop: (info) => {
            $('.tooltiptopicevent').remove();
            $scope.edit(info);
        },
        eventMouseEnter: function (info) {
            const {el, event} = info;
            console.log('info', info);
            console.log('el', el);
            console.log('event', event);
            console.log('event id', event.id);

            $scope.show(event.id);
        },
        eventMouseLeave: function (info) {
            // $(this).css('z-index', 8);
            $('.tooltiptopicevent').remove();
        },
        locale: 'es',
        eventTimeFormat: { // like '14:30:00'
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false
        },
    });
    $scope.calendar.render();

    $scope.show = ( id ) => {
        $http({
            url: `events/${id}`,
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        }).then(
            function successCallback(response) {
                console.log('ok', response);

                const event = response.data.event;
                const format = (event.allDay) ? 'YYYY-MM-DD' : 'YYYY-MM-DD HH:mm'
                const color = (event.color) ? event.color : "#0C4C91"

                tooltip = '<div class="tooltiptopicevent" style="background:'+color+';">' + 'Evento: ' + ': ' + event.title 
                + '</br>' + 'Inicia: ' + ': ' + moment(event.start).format(format)
                + '</br>' + 'Finaliza: ' + ': ' + moment(event.end).format(format)
                + '</br>' + 'Centros: ' + ': ' + event.centers
                + '</div>';
                
                $("#calendar").append(tooltip);
                $(this).mouseover(function (e) {
                    $(this).css('z-index', 10000);
                    $('.tooltiptopicevent').fadeIn('1000');
                    $('.tooltiptopicevent').fadeTo('10', 1.9);
                })    
            },
            function errorCallback(error) {
                console.log('error', error);
            }
        );
    }

    $scope.create = () => {

        $http({
            url: 'events',
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            data: $scope.createForm
        }).then(
            function successCallback(response) {
                console.log('ok', response);
                $scope.createForm = {};
                $('#createModal').modal('hide');
                $scope.calendar.refetchEvents();
            },
            function errorCallback(error) {
                console.log('error', error);
            }
        );


    }

    $scope.store = function () {

    }

    $scope.edit = (info) => {
        let start_date = moment(info.event.start, 'DD.MM.YYYY').format('YYYY-MM-DD HH:mm:ss');
        let end_date = moment(info.event.end, 'DD.MM.YYYY').format('YYYY-MM-DD HH:mm:ss');
        $scope.evento = {
            id: info.event.id,
            title: info.event.title,
            start: start_date,
            end: end_date,
            allDay: info.event.allDay
        };

        const date = new Date($scope.evento.start);
        let options = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        if (!info.event.allDay) {
            options.hour = "2-digit";
            options.minute = "2-digit";
        }
        console.log(date.toLocaleDateString("es-MX", options));

        $('#event-title').html($scope.evento.title);
        $('#event-start').html(date.toLocaleDateString("es-MX", options));
        $('#confirmUpdateModal').modal('show');
    }

    $('#confirmUpdateModal').on('hidden.bs.modal', function () {
        console.log('cancelar update');
        $scope.calendar.refetchEvents();
    });

    $scope.update = function () {

        $http({
            url: 'events',
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            data: $scope.evento
        }).then(
            function successCallback(response) {
                console.log('ok', response);
                $('#confirmUpdateModal').modal('hide');
                // $scope.calendar.refetchEvents();
            },
            function errorCallback(error) {
                console.log('error', error);
            }
        );

    }

    $scope.confirmDelete = function (dato) {
        $scope.evento = dato;
        $('#event-title-delete').html($scope.evento.title);
        $('#confirmDeleteModal').modal('show');
    }

    $scope.delete = function (dato) {
        console.log('dato: ', dato);

        $http({
            url: 'events',
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            data: {
                id: $scope.evento.id,
            }
        }).then(
            function successCallback(response) {
                console.log('ok', response);
                $('#confirmDeleteModal').modal('hide');
                $scope.calendar.refetchEvents();
            },
            function errorCallback(error) {
                console.log('error', error);
            }
        );

    }

    $('#editModal').on('hidden.bs.modal', function () {
        console.log('haz algo');
    });
});

app.filter('activoInactivo', function () {
    return function (input) {
        return input ? 'Activo' : 'Inactivo';
    }
});
app.filter('siNo', function () {
    return function (input) {
        return input ? 'Si' : 'No';
    }
});
