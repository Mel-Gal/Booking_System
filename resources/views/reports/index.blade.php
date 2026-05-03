@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">System Reports</h2>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-end space-x-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Generate Report
                </button>
                <button type="button" onclick="downloadPDF()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Export PDF
                </button>
                <button type="submit" name="export" value="csv" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                    Export CSV
                </button>
            </div>
        </form>
    </div>

    <div id="printable-report" class="p-2">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Status Summary ({{ \Carbon\Carbon::parse($startDate)->format('M d') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d') }})</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-4 border-b-4 border-blue-400">
                <h4 class="text-gray-500 text-xs font-bold uppercase">Scheduled</h4>
                <p class="text-2xl font-bold text-gray-800">{{ $statusSummary['Scheduled'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-b-4 border-indigo-400">
                <h4 class="text-gray-500 text-xs font-bold uppercase">Confirmed</h4>
                <p class="text-2xl font-bold text-gray-800">{{ $statusSummary['Confirmed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-b-4 border-green-500">
                <h4 class="text-gray-500 text-xs font-bold uppercase">Completed</h4>
                <p class="text-2xl font-bold text-gray-800">{{ $statusSummary['Completed'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-b-4 border-red-500">
                <h4 class="text-gray-500 text-xs font-bold uppercase">Cancelled</h4>
                <p class="text-2xl font-bold text-gray-800">{{ $statusSummary['Cancelled'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-b-4 border-orange-500">
                <h4 class="text-gray-500 text-xs font-bold uppercase">No Show</h4>
                <p class="text-2xl font-bold text-gray-800">{{ $statusSummary['No Show'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Staff Activity (Completed)</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach($staffActivity as $staff)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900">{{ $staff->name }}</span>
                            <span class="text-sm font-bold text-gray-700 bg-gray-100 py-1 px-3 rounded-full">{{ $staff->completed_appointments }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Clients (Completed Visits)</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach($clientVisits as $client)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-900">{{ $client->first_name }} {{ $client->last_name }}</span>
                            <span class="text-sm font-bold text-gray-700 bg-gray-100 py-1 px-3 rounded-full">{{ $client->completed_visits }} visits</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Today's Itinerary</h3>
            @if($dailyAppointments->isEmpty())
                <p class="text-gray-600 text-sm">No appointments scheduled for today.</p>
            @else
                <table class="min-w-full leading-normal border-collapse mt-4">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Client</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyAppointments as $appointment)
                            <tr>
                                <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm font-bold">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </td>
                                <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                    {{ $appointment->client?->first_name ?? 'Unknown' }} {{ $appointment->client?->last_name ?? 'Client' }}
                                </td>

                                <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                    {{ $appointment->staff?->name ?? 'Unknown Staff' }}
                                </td>
                                <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                    {{ $appointment->status }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function downloadPDF() {
        // Find the specific container we want to convert to PDF
        const element = document.getElementById('printable-report');
        
        // Define the PDF settings
        const opt = {
            margin:       0.5,
            filename:     'System_Report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
        };

        // Generate and download the PDF
        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection