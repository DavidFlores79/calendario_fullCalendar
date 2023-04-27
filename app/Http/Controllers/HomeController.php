<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            $events = Event::create($data);
            return response()->json($events);                
        // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

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

    public function deleteEvent(Request $request)
    {
        try {
            $event = Event::findOrFail($request->get('id'));
            $event->delete();
            return response()->json($event);                
            // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

    public function getAllCentros()
    {
        $timeout = 20;
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->getAuthorizacion(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout($timeout)->get($this->getRestUri() . '/services/centro/all?lang=S');

            if ($response->json('estatus')) {

                $data = [
                    "code" => 200,
                    "status" => "success",
                    "message" => "Se obtuvieron los centros.",
                    "centros" => $response->json('centros')
                ];
            } else {
                throw new \ErrorException("Error al obtener los catálogos.", 404);
            }

            return response()->json($data, $data["code"]);
            // throw new \ErrorException("Error al obtener los catálogos.", 404);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "Failed to connect")) throw new \ErrorException("Tiempo de espera agotado.", 500);
            throw new HttpException(($e->getCode() > 500 || $e->getCode() < 100) ? 500 : $e->getCode(), $e->getMessage());
        }
    }

    public function getRestUri()
    {
        return "http://localhost:8080/hope_api";
    }

    public function getAuthorizacion()
    {
        return "Basic WVdSdGFXND06U1hSemIyWjBNREV1";
    }
}
