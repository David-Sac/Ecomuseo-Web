<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Tour;
use Carbon\Carbon;

class StoreTourRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d|after:start_date',
            'contact_info' => 'required|string',
            'components' => 'required|array',
            'components.*' => 'exists:components,id',
            'volunteer_id' => 'required|exists:users,id',
            'visibility_period' => 'required|string|in:1 día,2 días,1 semana,2 semanas,1 mes,2 meses,3 meses',
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|string|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.max_capacity' => 'required|integer|min:1',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $componentIds = $this->components ?? [];
            $schedules = $this->schedules ?? [];

            foreach ($schedules as $schedule) {
                $dayOfWeek = $schedule['day_of_week'];
                $startTime = $schedule['start_time'];
                $endTime = $schedule['end_time'];

                $overlappingTours = DB::table('tours')
                    ->join('tour_schedules', 'tours.id', '=', 'tour_schedules.tour_id')
                    ->join('components_tour', 'tours.id', '=', 'components_tour.tour_id')
                    ->whereIn('components_tour.components_id', $componentIds)
                    ->where('tour_schedules.day_of_week', $dayOfWeek)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('tour_schedules.start_time', [$startTime, $endTime])
                              ->orWhereBetween('tour_schedules.end_time', [$startTime, $endTime])
                              ->orWhere(function ($query) use ($startTime, $endTime) {
                                  $query->where('tour_schedules.start_time', '<=', $startTime)
                                        ->where('tour_schedules.end_time', '>=', $endTime);
                              });
                    })
                    ->exists();

                if ($overlappingTours) {
                    $validator->errors()->add('schedules', 'hay solape en los horarios con los componenentes seleccionados el mismo día y fecha.');
                    break;
                }
            }
        });
    }
}
