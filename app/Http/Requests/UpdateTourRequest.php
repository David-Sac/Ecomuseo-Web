<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB; // Importa el facade DB para realizar consultas
use App\Models\Tour; // Asegúrate de importar el modelo Tour aquí.
use App\Models\User;
use Illuminate\Validation\Rule; // Si vas a usar reglas de validación personalizadas.


class UpdateTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tourId = $this->route('tour');

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
            'max_people' => 'required|integer|min:1',
            'contact_info' => 'required|string',
            'components' => 'sometimes|array',
            'components.*' => 'exists:components,id',
            'volunteer_id' => 'required|exists:users,id', // Añadimos la validación para el voluntario
            'visibility_period' => 'required|string|in:1 día,2 días,1 semana,2 semanas,1 mes,2 meses,3 meses'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $startDate = Carbon::parse($this->start_date);
            $endDate = Carbon::parse($this->end_date);
            $tourId = $this->route('tour')->id;

            if ($this->filled('components')) {
                $overlappingTours = Tour::whereHas('components', function ($query) {
                    $query->whereIn('components.id', $this->components);
                })->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate]);
                })->where('id', '!=', $tourId)->exists();

                if ($overlappingTours) {
                    $validator->errors()->add('components', 'The selected components are already reserved for another tour within the provided date range.');
                }
            }

            $volunteerId = $this->input('volunteer_id');
            $volunteer = User::find($volunteerId);
            if ($volunteer && empty($volunteer->phone)) {
                $validator->errors()->add('volunteer_id', 'El voluntario seleccionado no tiene un número de teléfono.');
            }
        });
    }
}
