<?php
// sudut-timur-backend/app/Http/Controllers/ActivityController.php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivitiesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CostDriver; // Import Inertia

class ActivityController extends Controller
{
    /**
     * Display a listing of the activities and render the Inertia page.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $search = $request->get('search');

        $query = Activity::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $activities = $query->latest()
            ->paginate($perPage)
            ->appends($request->all());

        $costDrivers = CostDriver::all(['id', 'name', 'unit']);
        // Pass activity data directly to the React component via Inertia props
        return Inertia::render('Activities/Index', [
            'activities' => $activities,
            'costDrivers' => $costDrivers,
            'filters' => [
                'search' => $search,
                'perPage' => $perPage,
            ],
        ]);
    }

    /**
     * Store a newly created activity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'primary_cost_driver_id' => 'required|exists:cost_drivers,id', // ✅ tambahkan ini
        ]);

        Activity::create([
            'name' => $request->name,
            'description' => $request->description,
            'primary_cost_driver_id' => $request->primary_cost_driver_id, // ✅
        ]);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil ditambahkan!');
    }

    /**
     * Update the specified activity in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Activity $activity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'primary_cost_driver_id' => 'required|exists:cost_drivers,id', // ✅
        ]);

        $activity->update([
            'name' => $request->name,
            'description' => $request->description,
            'primary_cost_driver_id' => $request->primary_cost_driver_id, // ✅
        ]);

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil diperbarui!');
    }

    /**
     * Remove the specified activity from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new ActivitiesExport, 'daftar-aktivitas.xlsx');
    }

    public function exportPdf()
    {
        $activities = Activity::all();

        $pdf = PDF::loadView('exports.activities', compact('activities'));
        return $pdf->stream('daftar-aktivitas.pdf');
    }
}
