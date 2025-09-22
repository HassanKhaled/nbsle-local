<?php

namespace App\Http\Controllers;

use App\Models\fac_uni;
use App\Models\workDetails;
use App\Models\workReg;
use App\Models\universitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class WorkshopsController extends Controller
{
    /**
     * Show university workshop submission form
     */
    public function GetSemFormUni($uniID)
    {
        $UniName = universitys::findOrFail($uniID);
        $facultyName = fac_uni::where('uni_id', $uniID)->get(['id','name','ImagePath']);

        return view('Universitys.workshopSub', compact('uniID', 'UniName', 'facultyName'));
    }

    /**
     * Show faculty workshop submission form
     */
    public function GetSemFormFac($uniID, $facultyID)
    {
        return view('Facultys.workshopSub', compact('uniID', 'facultyID'));
    }

    /**
     * Search faculty names (used for select2 autocomplete)
     */
    public function searchFacultyName(Request $request)
    {
        $query = fac_uni::select('id','name');

        if ($request->has('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        return response()->json($query->get());
    }

    /**
     * Store workshop for a University (faculty name provided in request)
     */
    public function storeUniWorkshopDetails(Request $request, $uni_id)
    {
        $faculty = fac_uni::where('uni_id', $uni_id)
                          ->where('name', $request->FacultyName)
                          ->firstOrFail();

        return $this->storeWorkshop($request, $uni_id, $faculty->id);
    }

    /**
     * Store workshop for a Faculty (faculty id given directly)
     */
    public function storeFacWorkshopDetails(Request $request, $uni_id, $fac_id)
    {
        return $this->storeWorkshop($request, $uni_id, $fac_id);
    }

    /**
     * Common workshop storage logic
     */
    private function storeWorkshop(Request $request, int $uni_id, int $fac_id)
    {
        // 1. Validate
        $request->validate([
            'WorkshopArabicName'   => 'required|string|max:100',
            'WorkshopEnglishName'  => 'nullable|string|max:100',
        'WorkshopSDate'        => 'required|date_format:m/d/Y',
        'WorkshopEDate'        => 'required|date_format:m/d/Y|after:WorkshopSDate',
            'WorkshopPer'          => 'required|integer|min:1',
            'WorkshopPl'           => 'required|string|max:100',
            'WorkshopCname'        => 'required|string|max:100',
            'WorkshopCphone'       => 'required|string|max:20',
            'WorkshopCemail'       => 'required|email|max:100',
            'Wlogo'                => 'nullable|image|max:2048',
            'nolec'                => 'required|integer|min:1',
            'nofees'               => 'required|integer|min:1',
        ]);

        // 2. Dates (already validated in Y-m-d format)
        // $startDate = $request->WorkshopSDate;
        // $endDate   = $request->WorkshopEDate;

            // 2. Convert dates to Y-m-d before saving
        $startDate = Carbon::createFromFormat('m/d/Y', $request->WorkshopSDate)->format('Y-m-d');
        $endDate   = Carbon::createFromFormat('m/d/Y', $request->WorkshopEDate)->format('Y-m-d');

        // 3. Lecturers
        $lecturers = $this->buildLecturers($request, (int) $request->nolec);

        // 4. Logo upload
        $logoPath = $this->handleWorkshopLogo($request, $uni_id);

        // 5. Fees
        [$feesTypes, $fees] = $this->processFees($request);

        // 6. Save to DB
        $workshop = workDetails::create([
            'Uni_id'            => $uni_id,
            'Faculty_id'        => $fac_id,
            'workshop_ar_title' => $request->WorkshopArabicName,
            'workshop_en_title' => $request->WorkshopEnglishName,
            'workshop_logoPath' => $logoPath,
            'no_lecturers'      => $request->nolec,
            'Lec_ar_names'      => $lecturers['ar_names'],
            'Lec_en_names'      => $lecturers['en_names'],
            'Lec_ar_details'    => $lecturers['ar_details'],
            'Lec_en_details'    => $lecturers['en_details'],
            'workshop_period'   => $request->WorkshopPer,
            'st_date'           => $startDate,
            'end_date'          => $endDate,
            'attendees_types'   => $request->nofees,
            'fees_types'        => $feesTypes ?? [],
            'fees_values'       => $fees ?? [],
            'place'             => $request->WorkshopPl,
            'rep_name'          => $request->WorkshopCname,
            'rep_phone'         => $request->WorkshopCphone,
            'rep_email'         => $request->WorkshopCemail,
            'notes'             => $request->Wnotes,
        ]);

        // 7. Debug log (optional, to confirm saving works)
        \Log::info('Workshop created', $workshop->toArray());

        return back()->with('message', 'Workshop Advertisement has been stored successfully.');
    }


    /**
     * Build lecturers arrays
     */
    private function buildLecturers(Request $request, int $count): array
    {
        $arNames    = [];
        $enNames    = [];
        $arDetails  = [];
        $enDetails  = [];

        for ($i = 0; $i < $count; $i++) {
            $arNames[]   = $request->input("LecturerArabicName{$i}", null);
            $enNames[]   = $request->input("LecturerEnglishName{$i}", null);
            $arDetails[] = $request->input("LecturerDetailsInAr{$i}", null);
            $enDetails[] = $request->input("LecturerDetailsInEng{$i}", null);
        }

        return [
            'ar_names'   => $arNames,
            'en_names'   => $enNames,
            'ar_details' => $arDetails,
            'en_details' => $enDetails,
        ];
    }


    /**
     * Handle workshop logo upload
     */
    private function handleWorkshopLogo(Request $request, int $uniId): string
    {
        if (!$request->hasFile('Wlogo')) {
            return 'images/workshops/No_Image.png';
        }

        $image = $request->file('Wlogo');
        $path = $image->storeAs("images/workshops/$uniId", $image->getClientOriginalName(), 'public');

        return "storage/$path";
    }

    /**
     * Process workshop fees
     */
    private function processFees(Request $request): array
    {
        switch ((int) $request->nofees) {
            case 1:
                return [
                    ['same'],
                    [$request->input('samefees')]
                ];

            case 2:
                return [
                    ['internal members', 'external members'],
                    [
                        $request->input('internalfees'),
                        $request->input('externalfees')
                    ]
                ];

            default:
                return [[], []]; // fallback if no fees type selected
        }
    }



    /**
     * Show participant registration form
     */
    public function GetRegForm()
    {
        return view('Users.PartregistrationForm');
    }

    /**
     * Store participant registration
     */
    public function storeRegistrationDetails(Request $request)
    {
        $data = $request->validate([
            'PartName'    => 'required|string|max:300',
            'partGender'  => 'required|string|max:100',
            'partEmail'   => 'required|email|max:100',
            'partType'    => 'required|string|max:100',
            'parSubType'  => 'nullable|string|max:100',
            'PartUni'     => 'required|string|max:100',
            'PartFaculty' => 'required|string|max:100',
            'PartDept'    => 'required|string|max:100',
        ]);

        if ($data['partType'] === 'Employee') {
            $data['parSubType'] = 'Employee';
        }

        workReg::create([
            'full_name'   => $data['PartName'],
            'gender'      => $data['partGender'],
            'email'       => $data['partEmail'],
            'par_type'    => $data['partType'],
            'par_sub_type'=> $data['parSubType'],
            'par_uni'     => $data['PartUni'],
            'par_fac'     => $data['PartFaculty'],
            'par_dept'    => $data['PartDept'],
        ]);

        return back()->with('message', 'Participant registered successfully.');
    }
}
