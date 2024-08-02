<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\Components;
use App\Models\User;
use Carbon\Carbon;

class ToursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtenemos los voluntarios
        $volunteers = User::role('Volunteer senior')->get();

        $toursData = [
            [
                'name' => 'Ruta del Huayruro: Tesoros de la Selva',
                'description' => 'Un viaje que explora la historia, significado cultural y usos del emblemático Huayruro en la Amazonía.',
                'start_date' => Carbon::now()->addDays(10),
                'end_date' => Carbon::now()->addDays(30), // Cobertura para todos los días de 'schedules'
                'visibility_period' => '1 mes',
                'components' => [2],
                'schedules' => [
                    ['day_of_week' => 'Lunes', 'start_time' => '14:00', 'end_time' => '16:00'],
                    ['day_of_week' => 'Miércoles', 'start_time' => '14:00', 'end_time' => '16:00'],
                    ['day_of_week' => 'Viernes', 'start_time' => '14:00', 'end_time' => '16:00'],
                ],
            ],
            [
                'name' => 'El Zumbido Secreto: El Mundo de las Abejas Meliponas',
                'description' => 'Este tour invita a los visitantes a sumergirse en el mundo de las abejas sin aguijón, descubriendo su importancia en la polinización y la producción de miel única.',
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(40), // Cobertura para todos los días de 'schedules'
                'visibility_period' => '1 mes',
                'components' => [1],
                'schedules' => [
                    ['day_of_week' => 'Martes', 'start_time' => '10:00', 'end_time' => '12:00'],
                    ['day_of_week' => 'Jueves', 'start_time' => '10:00', 'end_time' => '12:00'],
                ],
            ],
            [
                'name' => 'Gigantes Verdes: El Legado de las Cashaponas',
                'description' => 'Un recorrido que destaca la majestuosidad de los árboles Cashapona, su rol en el ecosistema y los esfuerzos por su conservación.',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(35), // Cobertura para todos los días de 'schedules'
                'visibility_period' => '1 mes',
                'components' => [3],
                'schedules' => [
                    ['day_of_week' => 'Lunes', 'start_time' => '09:00', 'end_time' => '11:00'],
                    ['day_of_week' => 'Miércoles', 'start_time' => '09:00', 'end_time' => '11:00'],
                    ['day_of_week' => 'Viernes', 'start_time' => '09:00', 'end_time' => '11:00'],
                ],
            ],
            [
                'name' => 'Sinfonía de la Selva: Huayruro, Meliponas y Cashaponas',
                'description' => 'Una experiencia integral que fusiona la magia del Huayruro, el mundo fascinante de las Abejas Meliponas y la grandeza de las Cashaponas, ofreciendo una visión completa de la biodiversidad amazónica.',
                'start_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(45), // Cobertura para todos los días de 'schedules'
                'visibility_period' => '1 mes',
                'components' => [1, 2, 3],
                'schedules' => [
                    ['day_of_week' => 'Sábado', 'start_time' => '15:00', 'end_time' => '17:00'],
                    ['day_of_week' => 'Domingo', 'start_time' => '15:00', 'end_time' => '17:00'],
                ],
            ],
            [
                'name' => 'Alas y Raíces: Polinizadores y Protectores del Amazonas',
                'description' => 'Este tour combina la exploración de las Abejas Meliponas con el conocimiento sobre los árboles Cashapona, enfatizando su interdependencia y su papel vital en el mantenimiento de los ecosistemas.',
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(60), // Cobertura para todos los días de 'schedules'
                'visibility_period' => '1 mes',
                'components' => [1, 3],
                'schedules' => [
                    ['day_of_week' => 'Miércoles', 'start_time' => '13:00', 'end_time' => '15:00'],
                    ['day_of_week' => 'Viernes', 'start_time' => '13:00', 'end_time' => '15:00'],
                ],
            ],
        ];

        foreach ($toursData as $tourData) {
            // Seleccionar un voluntario aleatorio
            $volunteer = $volunteers->random();

            // Crear el tour
            $tour = Tour::create([
                'name' => $tourData['name'],
                'description' => $tourData['description'],
                'start_date' => $tourData['start_date'],
                'end_date' => $tourData['end_date'],
                'contact_info' => $volunteer->phone,
                'visibility_period' => $tourData['visibility_period'],
            ]);

            // Relacionar los componentes específicos con el tour creado
            $tour->components()->attach($tourData['components']);

            // Asignar el voluntario al tour
            $tour->volunteers()->attach($volunteer->id);

            // Crear los horarios para el tour
            foreach ($tourData['schedules'] as $scheduleData) {
                TourSchedule::create([
                    'tour_id' => $tour->id,
                    'day_of_week' => $scheduleData['day_of_week'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                ]);
            }
        }
    }
}
