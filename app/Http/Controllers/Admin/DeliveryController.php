<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryRecord;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    /**
     * Display a listing of delivery records.
     */
    public function index(Request $request)
    {
        $query = DeliveryRecord::with(['patient.user', 'doctor.user', 'admin'])
            ->orderBy('delivery_datetime', 'desc');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('delivery_outcome')) {
            $query->where('delivery_outcome', $request->delivery_outcome);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('delivery_datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('delivery_datetime', '<=', $request->date_to);
        }

        $deliveries = $query->paginate(15);

        // Statistics for the header
        $stats = [
            'total_deliveries' => DeliveryRecord::count(),
            'successful_deliveries' => DeliveryRecord::successful()->count(),
            'maternal_deaths' => DeliveryRecord::whereIn('maternal_status', ['deceased'])->count(),
            'infant_deaths' => DeliveryRecord::whereIn('delivery_outcome', ['infant_death', 'both_deaths'])->count(),
            'cesarean_rate' => DeliveryRecord::whereIn('delivery_type', ['cesarean', 'emergency_cesarean'])->count(),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'stats'));
    }

    /**
     * Show the form for creating a new delivery record.
     */
    public function create(Request $request)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        
        $selectedPatient = null;
        if ($request->filled('patient_id')) {
            $selectedPatient = Patient::with('user')->find($request->patient_id);
        }

        return view('admin.deliveries.create', compact('patients', 'doctors', 'selectedPatient'));
    }

    /**
     * Store a newly created delivery record in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'delivery_datetime' => 'required|date',
            'delivery_type' => 'required|in:natural,cesarean,assisted,emergency_cesarean',
            'delivery_outcome' => 'required|in:successful,complicated,maternal_death,infant_death,both_deaths',
            'pregnancy_type' => 'required|in:single,twins,triplets,quadruplets,other_multiple',
            'number_of_babies' => 'required|integer|min:1|max:10',
            'maternal_status' => 'required|in:alive_healthy,alive_complications,deceased',
            'delivery_location' => 'required|string|max:255',
            'babies_details' => 'required|array|min:1',
            'babies_details.*.gender' => 'required|in:male,female',
            'babies_details.*.weight' => 'nullable|numeric|min:0.1|max:10',
            'babies_details.*.status' => 'required|in:alive,deceased',
            'complications' => 'nullable|string',
            'surgery_performed' => 'boolean',
            'surgery_details' => 'nullable|string',
            'maternal_complications' => 'nullable|string',
            'maternal_death_time' => 'nullable|date',
            'maternal_death_cause' => 'nullable|string',
            'attending_physician' => 'nullable|string|max:255',
            'midwife_nurse' => 'nullable|string|max:255',
            'medical_notes' => 'nullable|string',
            'ward_room' => 'nullable|string|max:255',
            'discharge_datetime' => 'nullable|date|after:delivery_datetime',
            'post_delivery_notes' => 'nullable|string',
            'requires_followup' => 'boolean',
            'next_appointment_date' => 'nullable|date|after:delivery_datetime',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['admin_id'] = auth()->id();
        $data['surgery_performed'] = $request->has('surgery_performed');
        $data['requires_followup'] = $request->has('requires_followup');

        DeliveryRecord::create($data);

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery record created successfully.');
    }

    /**
     * Display the specified delivery record.
     */
    public function show(DeliveryRecord $delivery)
    {
        $delivery->load(['patient.user', 'doctor.user', 'admin']);
        
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery record.
     */
    public function edit(DeliveryRecord $delivery)
    {
        $delivery->load(['patient.user', 'doctor.user']);
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();

        return view('admin.deliveries.edit', compact('delivery', 'patients', 'doctors'));
    }

    /**
     * Update the specified delivery record in storage.
     */
    public function update(Request $request, DeliveryRecord $delivery)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'delivery_datetime' => 'required|date',
            'delivery_type' => 'required|in:natural,cesarean,assisted,emergency_cesarean',
            'delivery_outcome' => 'required|in:successful,complicated,maternal_death,infant_death,both_deaths',
            'pregnancy_type' => 'required|in:single,twins,triplets,quadruplets,other_multiple',
            'number_of_babies' => 'required|integer|min:1|max:10',
            'maternal_status' => 'required|in:alive_healthy,alive_complications,deceased',
            'delivery_location' => 'required|string|max:255',
            'babies_details' => 'required|array|min:1',
            'babies_details.*.gender' => 'required|in:male,female',
            'babies_details.*.weight' => 'nullable|numeric|min:0.1|max:10',
            'babies_details.*.status' => 'required|in:alive,deceased',
            // ... other validation rules similar to store method
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['surgery_performed'] = $request->has('surgery_performed');
        $data['requires_followup'] = $request->has('requires_followup');

        $delivery->update($data);

        return redirect()->route('admin.deliveries.show', $delivery)
            ->with('success', 'Delivery record updated successfully.');
    }

    /**
     * Remove the specified delivery record from storage.
     */
    public function destroy(DeliveryRecord $delivery)
    {
        $delivery->delete();

        return redirect()->route('admin.deliveries.index')
            ->with('success', 'Delivery record deleted successfully.');
    }

    /**
     * Quick add delivery form for specific patient.
     */
    public function quickAdd(Patient $patient)
    {
        $doctors = Doctor::with('user')->get();
        
        return view('admin.deliveries.quick-add', compact('patient', 'doctors'));
    }

    /**
     * Get delivery statistics for dashboard.
     */
    public function getDeliveryStats()
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        return [
            'recent_deliveries' => DeliveryRecord::where('delivery_datetime', '>=', $thirtyDaysAgo)->count(),
            'successful_rate' => $this->calculateSuccessRate(),
            'cesarean_rate' => $this->calculateCesareanRate(),
            'maternal_mortality_rate' => $this->calculateMaternalMortalityRate(),
            'infant_mortality_rate' => $this->calculateInfantMortalityRate(),
        ];
    }

    private function calculateSuccessRate(): float
    {
        $total = DeliveryRecord::count();
        if ($total === 0) return 0;
        
        $successful = DeliveryRecord::successful()->count();
        return round(($successful / $total) * 100, 2);
    }

    private function calculateCesareanRate(): float
    {
        $total = DeliveryRecord::count();
        if ($total === 0) return 0;
        
        $cesarean = DeliveryRecord::whereIn('delivery_type', ['cesarean', 'emergency_cesarean'])->count();
        return round(($cesarean / $total) * 100, 2);
    }

    private function calculateMaternalMortalityRate(): float
    {
        $total = DeliveryRecord::count();
        if ($total === 0) return 0;
        
        $deaths = DeliveryRecord::where('maternal_status', 'deceased')->count();
        return round(($deaths / $total) * 1000, 2); // per 1000 deliveries
    }

    private function calculateInfantMortalityRate(): float
    {
        $totalBabies = DeliveryRecord::sum('number_of_babies');
        if ($totalBabies === 0) return 0;
        
        $deaths = DeliveryRecord::whereIn('delivery_outcome', ['infant_death', 'both_deaths'])->sum('number_of_babies');
        return round(($deaths / $totalBabies) * 1000, 2); // per 1000 births
    }
}
