<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\HealthProfessional;
use App\Transformers\HealthProfessionalsTransformer;

class DoctorController extends Controller
{
    /**
     * @param HealthProfessionalsTransformer $healthProfessionalsTransformer
     */
    public function __construct(
        protected HealthProfessionalsTransformer $healthProfessionalsTransformer
    ){}

    public function index()
    {
        $doctors = HealthProfessional::query()
            ->orderBy('updated_at', 'desc')
            ->paginate(100)
            ->through(fn(HealthProfessional $healProfessional) => $this->healthProfessionalsTransformer->transform($healProfessional));

        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(StoreDoctorRequest $request)
    {
        Doctor::create($request->validated());
        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully.');
    }

    public function show(int $doctor)
    {
        $doctor = HealthProfessional::query()->findOrFail($doctor);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(int $doctor)
    {
        $doctor = HealthProfessional::query()->findOrFail($doctor);
        return view('doctors.edit', compact('doctor'));
    }

    public function merge(int $doctor)
    {
        $doctor = HealthProfessional::query()->findOrFail($doctor);
        return view('doctors.show', compact('doctor'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->validated());
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }
}
