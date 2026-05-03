<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to the current month if no dates are provided
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // ==========================================
        // CSV EXPORT LOGIC
        // ==========================================
        if ($request->input('export') === 'csv') {
            // Fetch all appointments in the range, INCLUDING soft-deleted/completed ones
            $appointmentsForCsv = Appointment::withTrashed()
                ->with(['client', 'staff'])
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->orderBy('appointment_date', 'asc')
                ->orderBy('appointment_time', 'asc')
                ->get();

            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=appointments_report_{$startDate}_to_{$endDate}.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use ($appointmentsForCsv) {
                $file = fopen('php://output', 'w');
                
                // Define CSV Headers
                fputcsv($file, ['Date', 'Time', 'Client', 'Service', 'Status', 'Staff Assigned']);

                // Add rows
                foreach ($appointmentsForCsv as $appointment) {
                    fputcsv($file, [
                        Carbon::parse($appointment->appointment_date)->format('Y-m-d'),
                        Carbon::parse($appointment->appointment_time)->format('h:i A'),
                        $appointment->client ? $appointment->client->first_name . ' ' . $appointment->client->last_name : 'N/A',
                        $appointment->service_type,
                        $appointment->status,
                        $appointment->staff ? $appointment->staff->name : 'N/A'
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // ==========================================
        // REGULAR REPORT VIEW LOGIC
        // ==========================================

        // 1. Appointment Status Summary (Added withTrashed)
        $statusSummary = Appointment::withTrashed()
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Ensure all statuses have a default value of 0 if not present
        $statuses = ['Scheduled', 'Confirmed', 'Completed', 'Cancelled', 'No Show'];
        foreach ($statuses as $status) {
            $statusSummary[$status] = $statusSummary[$status] ?? 0;
        }

        // 2. Daily Appointment Report (Added withTrashed)
        $dailyAppointments = Appointment::withTrashed()
            ->with(['client', 'staff'])
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time', 'asc')
            ->get();

        // 3. Staff Activity Report (Added withTrashed to the subquery)
        $staffActivity = User::where('role', 'Staff')
            ->withCount(['appointments as completed_appointments' => function ($query) use ($startDate, $endDate) {
                $query->withTrashed()
                      ->whereBetween('appointment_date', [$startDate, $endDate])
                      ->where('status', 'Completed');
            }])
            ->orderByDesc('completed_appointments')
            ->get();

        // 4. Client Visit Summary (Added withTrashed to the subquery)
        $clientVisits = Client::withCount(['appointments as completed_visits' => function ($query) use ($startDate, $endDate) {
                $query->withTrashed()
                      ->whereBetween('appointment_date', [$startDate, $endDate])
                      ->where('status', 'Completed');
            }])
            ->orderByDesc('completed_visits')
            ->take(5)
            ->get();

        return view('reports.index', compact(
            'startDate', 
            'endDate', 
            'statusSummary', 
            'dailyAppointments', 
            'staffActivity', 
            'clientVisits'
        ));
    }
}