<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // 1. Top-level Statistics
        $totalClients = Client::count();
        $appointmentsToday = Appointment::whereDate('appointment_date', $today)->count();
        $completedAppointments = Appointment::where('status', 'Completed')->count();

        // 2. Upcoming Appointments (Next 5 scheduled or confirmed)
        $upcomingAppointments = Appointment::with(['client', 'staff'])
            ->where('appointment_date', '>=', $today)
            ->whereIn('status', ['Scheduled', 'Confirmed'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->take(5)
            ->get();

        // 3. Recent Activity (Last 5 modified appointments)
        $recentActivity = Appointment::with(['client', 'staff', 'creator'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalClients', 
            'appointmentsToday', 
            'completedAppointments', 
            'upcomingAppointments', 
            'recentActivity'
        ));
    }
}
