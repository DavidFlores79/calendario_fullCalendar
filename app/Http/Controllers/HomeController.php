<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function getEvents(Request $request)
    {
        //validar el request

        $start = ($request->start) ? $request->start : ('');
        $end = ($request->end) ? $request->end : ('');
        $events = Event::whereDate("start", ">=", $start)->whereDate("end", "<=", $end)->select("id", "title", "start", "end", "allDay", "color")->get();
        return response()->json($events);
    }

    /**Crea un evento en el calendario para agendar Inventarios para
     * uno o mas centros, y para uno o mas distritos, recibe y guarda
     * de igual forma grupos de articulos y articulos individuales
     */
    public function createEvent(Request $request)
    {
        /** validamos el request */
        $rules = [
            'start' => 'required|after_or_equal:today',
        ];
        $messages = [
            'start.after_or_equal' => 'No es posible crear un evento anterior a la fecha de hoy.',
        ];
        $this->validate($request, $rules, $messages);

        try {
            $data = $request->all();
            $now = Carbon::now();
            $event_start = Carbon::parse($data["start"]);

            /**
             * Esta es una validacion para la hora actual, en comparacion con
             * la del evento. Si el evento no dura todo el dia no revisa las horas,
             * unicamente las fechas
             */
            if ( (!$data["allDay"]) &&  !$event_start->gte($now) ) throw new \ErrorException("No es posible crear un evento con fecha/hora anterior a la fecha/hora actual.", 403);

            $events = Event::create($data);
            return response()->json($events);                
        // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Devuelve informacion de acerca de un Inventario
     * programado que se ha guardado en la BD
     */
    public function show($id)
    {
        try {
            // return $request;
            $event = Event::findOrFail($id);

            $data = [
                "code" => 200,
                "status" => "success",
                "event" => $event,
            ];

            return response()->json($data, $data["code"]);

            // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Actualiza un evento en el calendario y se asegura
     * que no se de una fecha en el pasado, devuelve lo que
     * necesita el fullCalendar para mostrar en el front
     */
    public function updateEvent(Request $request)
    {
        /** validamos el request */
        $rules = [
            'id' => 'required|exists:events,id',
            'start' => 'required|after_or_equal:today',
        ];
        $messages = [
            'start.after_or_equal' => 'No es posible modificar un evento anterior a la fecha de hoy.',
        ];
        $this->validate($request, $rules, $messages);
        
        try {
            // return $request;
            $data = $request->all();
            $event = Event::where("id", $data["id"])->first();

            $now = Carbon::now();
            $event_start = Carbon::parse($event->start);
            $event_update_start = Carbon::parse($data["start"]);
            /**
             * Esta es una validacion para la hora actual, en comparacion con
             * la del evento. Si el evento no dura todo el dia no revisa las horas,
             * unicamente las fechas
             */
            if ( (!$event->allDay) &&  (!$event_start->gte($now) || !$event_update_start->gte($now)) ) throw new \ErrorException("No es posible cambiar un evento a una fecha/hora anterior a la fecha/hora actual.", 403);

            $event->update(["title" => $data["title"], "start" => $data["start"], "end" => $data["end"],]);

            $data = [
                "code" => 200,
                "status" => "success",
                "message" => "Registro Actualizado.",
                "event" => $event
            ];

            return response()->json($data, $data["code"]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

    /**
     * Elimina un evento en el calendario y se
     * asegura que no sea una fecha que ya paso
     * 
     * TODO: No eliminar, solo marcar como eliminado
     */
    public function deleteEvent(Request $request)
    {
        /** validamos el request */
        $rules = [
            'id' => 'required|exists:events,id',
        ];
        $this->validate($request, $rules);
        
        try {
            $event = Event::findOrFail($request->get('id'));
            $now = Carbon::now();
            $event_start = Carbon::parse($event->start);
            
            if ( (!$event->allDay) &&  !$event_start->gte($now) ) throw new \ErrorException("No es posible eliminar un evento que ya pasó.", 403);

            $event->delete();
            return response()->json($event);                

            // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

}
