<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TourSchedule;

class StoreVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'tour_schedule_id' => [
                'required',
                'exists:tour_schedules,id',
            ],
            'number_of_people' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $tourSchedule = TourSchedule::find($this->tour_schedule_id);
                    if (!$tourSchedule) {
                        return $fail("El horario seleccionado no es válido.");
                    }

                    // Asegúrate de refrescar el modelo para obtener los datos más actuales
                    $tourSchedule->refresh();

                    // Implementa la lógica para calcular los cupos disponibles
                    $reservedSeats = $tourSchedule->visits()
                        ->whereIn('status', ['pending', 'approved'])
                        ->sum('number_of_people');
                    $availableSeats = $tourSchedule->max_capacity - $reservedSeats;

                    if ($value > $availableSeats) {
                        return $fail("El número de personas excede el cupo disponible para este horario del tour. Cupos disponibles: $availableSeats.");
                    }
                },
            ],
            'additional_info' => 'required|string|max:255',
        ];
    }
}
