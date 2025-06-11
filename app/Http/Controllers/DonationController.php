<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Components;
use App\Models\ComponentDonation;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','store']);
    }

    public function index(): View
    {
        return view('donations.index');
    }

    public function show(): View
    {
        $donations = Donation::with(['user'])
            ->orderByRaw("FIELD(status, 'approved') DESC")
            ->orderBy('requested_date', 'desc')
            ->latest()
            ->paginate(10);

        $components = Components::all();

        // Obtener los datos para la gráfica
        $componentLabels = $components->pluck('titleComponente')->toArray();
        $componentData = $components->map(function($component) {
            return $component->donations->sum('pivot.amount');
        })->toArray();

        return view('donations.show', compact('donations', 'components', 'componentLabels', 'componentData'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required'],
            'razon_social' => ['string', 'max:255'],
            'persona_contacto' => ['required', 'string', 'max:255'],
            'email_contacto' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'celular_contacto' => ['required', 'regex:/^(9)[0-9]{8}$/'],
            'info_adicional' => ['string', 'max:255'],
            'monto' => ['numeric', 'min:0'], // Validación para el monto
        ]);

        $donation = Donation::create([
            'user_id' => auth()->check() ? auth()->id() : null, // Asignar el ID del usuario autenticado si está disponible
            'type' => $request->type,
            'razon_social' => $request->razon_social,
            'persona_contacto' => $request->persona_contacto,
            'celular_contacto' => $request->celular_contacto,
            'email_contacto' => $request->email_contacto,
            'requested_date' => now(),
            'additional_info' => $request->info_adicional,
            'monto' => $request->monto, // Aquí es donde se asigna el monto
        ]);

        if (!$donation) {
            return redirect()->route('donations.index')->with('error', 'No se pudo procesar su solicitud. Por favor, intente de nuevo.');
        }

        return redirect()->route('donations.index')->with('success', 'Solicitud procesada con éxito. Nos pondremos en contacto contigo pronto.');
    }

    public function export(Request $request): Response
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->endOfDay();

        $donations = Donation::with(['user'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->orderBy('requested_date', 'desc')
            ->get();

        $data = ['donations' => $donations, 'startDate' => $startDate, 'endDate' => $endDate];

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('donations.export', $data);

        $pdf->render();
        $canvas = $pdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Página $pageNumber de $pageCount";
            $font = $fontMetrics->getFont('monospace');
            $pageWidth = $canvas->get_width();
            $pageHeight = $canvas->get_height();
            $size = 10;
            $width = $fontMetrics->getTextWidth($text, $font, $size);
            $canvas->text($pageWidth - $width - 20, $pageHeight - 20, $text, $font, $size);
        });
        return $pdf->stream('Reporte_donaciones_' . $startDate . '_' . $endDate . '.pdf');
    }

    public function approve(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->type === 'dinero') {
            $totalAmount = $donation->monto;
            $assignedAmounts = $request->input('components');

            // Verificar si $assignedAmounts es nulo o no es un array
            if (is_null($assignedAmounts) || !is_array($assignedAmounts)) {
                return back()->with('error', 'No se asignaron montos a los componentes.');
            }

            $sumAssignedAmounts = array_reduce($assignedAmounts, function($carry, $item) {
                return $carry + $item['amount'];
            }, 0);

            if ($sumAssignedAmounts > $totalAmount) {
                return back()->with('error', 'La suma de los montos asignados no puede superar el monto total de la donación.');
            }

            foreach ($assignedAmounts as $componentId => $amount) {
                if ($amount['amount'] > 0) {
                    ComponentDonation::create([
                        'donation_id' => $donation->id,
                        'components_id' => $componentId,
                        'amount' => $amount['amount'],
                    ]);
                }
            }
        }

        $donation->update(['status' => 'approved', 'approved_date' => now()]);

        return back()->with('success', 'Donación aprobada con éxito.');
    }



    public function decline($id): RedirectResponse
    {
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => 'rejected']);
        return back()->with('success', 'Donación rechazada.');
    }
}
