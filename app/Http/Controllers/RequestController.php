<?php

namespace App\Http\Controllers;

use App\Models\CertificateRequest;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WorkReg;
use App\Models\WorkshopAttendance;
use App\Models\workDetails;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CertificateRequestsExport;

class RequestController extends Controller
{
    public function index()
    {
        $requests = CertificateRequest::with('workshop')->get();
        return view('Workshops.Admin.certificate_requests', ['requests' => $requests]);
    }

   
    public function confirm(Request $request, $id)
    {
        $certificateRequest = CertificateRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:confirmed,rejected'
        ]);

        $certificateRequest->update([
            'status' => $request->status
        ]);

        $message = $request->status === 'confirmed'
            ? 'Request confirmed successfully.'
            : 'Request rejected successfully.';

        return redirect()->back()->with('info', $message);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'workshop_id' => 'required|exists:workshops_details,id',
            'cert_count'  => 'required|integer|min:1',
            'days'        => 'required|array|min:1',
            'days.*'      => 'integer|min:1',
            'image_receipt' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = auth()->user();

        // منع التكرار
        $exists = CertificateRequest::where('user_id', $user->id)
            ->where('workshop_id', $request->workshop_id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'You already requested a certificate for this workshop.');
        }

        $certCount = count($request->days);
        $cost = $certCount * 100;
        $path = $request->file('image_receipt')
         ->store('receipts', 'public');
        CertificateRequest::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'workshop_id' => $request->workshop_id,
            'user_id'     => $user->id,
            'days'        => $request->days,
            'cert_count'  => $request->cert_count,
            'cost'        => $cost * $request->cert_count + 7,
            'status'      => 'pending',
            'image_receipt' => $path,
        ]);

        return back()->with('message', 'Certificate request submitted successfully.');
    }

    public function update(Request $request, $id)
    {
        $certRequest = CertificateRequest::findOrFail($id);

        if ($certRequest->status !== 'rejected') {
            return back()->with('info', 'Only rejected requests can be edited.');
        }

        $request->validate([
            'cert_count' => 'required|integer|min:1',
            'days' => 'required|array|min:1',
            'image_receipt' => 'required|image|max:2048'
        ]);

        // upload new receipt
        $path = $request->file('image_receipt')->store('receipts', 'public');

        $daysCount = count($request->days);
        $cost = ($daysCount * 100 * $request->cert_count) + 7;

        $certRequest->update([
            'cert_count'    => $request->cert_count,
            'days'          => $request->days,
            'cost'          => $cost,
            'image_receipt' => $path,
            'status'        => 'pending'
        ]);

        return back()->with('info', 'Request updated and sent for review.');
    }

    
    public function importAttendance($id)
    {
        $workshop = workDetails::find($id);
        $attendances = WorkshopAttendance::get();
        return view('Workshops.Admin.attendance', ['workshop' => $workshop, 'attendances' => $attendances]);
    }

   public function import(Request $request, $workshopId)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,csv',
        //     'day_number' => 'required|integer|min:1'
        // ]);
        // dd($request->all());


        $rows = Excel::toArray([], $request->file('file'))[0];
        $inserted = 0;
        $skipped = [];
        $day = $request->day_number;

        foreach ($rows as $index => $row) {

            // Skip header
            if ($index === 0) continue;

            if (!isset($row[0]) || empty(trim($row[0]))) {
                $skipped[] = "Row ".($index+1).": National ID is empty";
                continue;
            }

            $nationalId = trim($row[0]);

            // 1️⃣ check user
            $user = User::where('national_id', $nationalId)->first();
            if (!$user) {
                $skipped[] = "Row ".($index+1).": User not found ($nationalId)";
                continue;
            }

            // 2️⃣ check registration
            $workReg = WorkReg::where('workshop_id', $workshopId)
                ->where('national_id', $nationalId)
                ->exists();

            // if (!$registered) {
            //     $skipped[] = "Row ".($index+1).": User not registered in workshop ($nationalId)";
            //     continue;
            // }

            if (!$workReg) {
                if (!empty($user->uni_id) && !empty($user->fac_id)) {
                    $institutionName = null;
                } else {
                    // غير تابع لجامعة (جهة خارجية)
                    $institutionName = $user->institution_name ?? 'External Participant';
                }

                $workReg = WorkReg::create([
                    'workshop_id'       => $workshopId,
                    'uni_id'            => $user->uni_id ?? null,
                    'fac_id'            => $user->fac_id ?? null,
                    'full_name'         => $user->name,
                    'gender'            => $user->gender ?? null,
                    'email'             => $user->email,
                    'par_type'          => $user->par_type ?? null,
                    'par_sub_type'      => $user->par_sub_type ?? null,
                    'national_id'       => $nationalId,
                    'phone'             => $user->phone ?? null,
                    'institution_name'  => $institutionName,
                ]);
            }

            // 3️⃣ insert attendance (prevent duplicates)
            $created = WorkshopAttendance::firstOrCreate([
                'workshop_id' => $workshopId,
                'user_id' => $user->id,
                'day_number' => $day,
            ]);

            if ($created->wasRecentlyCreated) {
                $inserted++;
            } else {
                $skipped[] = "Row ".($index+1).": Attendance already exists ($nationalId)";
            }
        }

        return back()->with([
            'success' => "$inserted attendance records imported successfully.",
            'errors' => $skipped
        ]);
    }



    public function downloadTemplate()
    {
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=attendance_template.csv",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['national_id']);

            // Example row
            fputcsv($file, ['12345678901234']);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
   public function bulkAction(Request $request)
    {
        $request->validate([
            'request_ids' => 'required|array',
            'status' => 'required|in:confirmed,rejected',
        ]);

        CertificateRequest::whereIn('id', $request->request_ids)
            ->update(['status' => $request->status]);

        return back()->with('info', 'Requests updated successfully');
    }
    public function export()
    {
        return Excel::download(
            new CertificateRequestsExport,
            'certificate_requests.xlsx'
        );
    }
}
