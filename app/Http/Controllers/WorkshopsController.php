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
    // Show all workshops pending approval for Admin
  
    public function showAdminWorkshops(Request $request)
    {
        // Base query with eager loads
        $query = workDetails::with(['university', 'faculty']);

        // Search filter (title in Arabic or English)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('workshop_ar_title', 'like', "%{$search}%")
                  ->orWhere('workshop_en_title', 'like', "%{$search}%");
            });
        }

        // University filter
        if ($request->filled('university')) {
            // NOTE: column name in DB is "Uni_id", not "university_id"
            $query->where('Uni_id', $request->input('university'));
        }

        // Faculty filter
        if ($request->filled('faculty')) {
            // NOTE: DB column is "Faculty_id"
            $query->where('Faculty_id', $request->input('faculty'));
        }

        // Status filter (is_approved boolean)
        if ($request->filled('status')) {
            if ($request->input('status') === 'approved') {
                $query->where('is_approved', 1);
            } elseif ($request->input('status') === 'pending') {
                $query->where('is_approved', 0);
            }
        }

        // Sorting and pagination — withQueryString preserves filters on pagination links
        $workshops = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        // Dropdown values
        $universities = universitys::orderBy('name')->get();

        // If a university is selected, restrict faculties to that university
        if ($request->filled('university')) {
            $faculties = fac_uni::where('uni_id', $request->input('university'))->orderBy('name')->get();
        } else {
            $faculties = fac_uni::orderBy('name')->get();
        }

        return view('Workshops.Admin.index', compact('workshops', 'universities', 'faculties'));
    }

    // Approve a workshop
    public function approve($id)
    {
        $workshop = WorkDetails::findOrFail($id);
        $workshop->update(['is_approved' => 1]);

        return redirect()->back()->with('message','Workshop confirmed successfully!');
    }

    //Workshop Reservations For Admin
    public function workshopReservations()
    {
        $user = Auth()->user();
        if($user->hasRole('university')){
            $reservations = workReg::with(['workshop.university', 'workshop.faculty'])
                        ->where('uni_id', $user->uni_id)
                        ->orderBy('id', 'desc')
                        ->paginate(15);
        }
        $reservations = workReg::with(['workshop.university', 'workshop.faculty'])
                        ->orderBy('id', 'desc')
                        ->paginate(15);

        return view('Workshops.Admin.reservations', compact('reservations'));
    }
    
    // Show university workshop submission form
    public function showUnivWorkshops(Request $request)
    {
        $user = Auth()->user();
        
        if($user->hasRole('university')){
             // Base query with eager loads
            $query = workDetails::with(['university', 'faculty'])->where('Uni_id', $user->uni_id);

            // Search filter (title in Arabic or English)
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('workshop_ar_title', 'like', "%{$search}%")
                    ->orWhere('workshop_en_title', 'like', "%{$search}%");
                });
            }

            // Faculty filter
            if ($request->filled('faculty')) {
                // NOTE: DB column is "Faculty_id"
                $query->where('Faculty_id', $request->input('faculty'));
            }

            // Status filter (is_approved boolean)
            if ($request->filled('status')) {
                if ($request->input('status') === 'approved') {
                    $query->where('is_approved', 1);
                } elseif ($request->input('status') === 'pending') {
                    $query->where('is_approved', 0);
                }
            }

            // Sorting and pagination — withQueryString preserves filters on pagination links
            $workshops = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

            // Dropdown values
            $universities = universitys::where('id',$user->uni_id)->get();
            // If a university is selected, restrict faculties to that university
            $faculties = fac_uni::where('uni_id',$user->uni_id)->orderBy('name')->get();
        }
        return view('Universitys.workshopindex', compact('workshops', 'universities', 'faculties'));
    }
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
        // 1. Base validation
        $rules = [
            'optradio'        => 'required|in:arabic,english,bothLan',
            'WorkshopSDate'   => 'required|date_format:m/d/Y',
            'WorkshopEDate'   => 'required|date_format:m/d/Y|after:WorkshopSDate',
            'WorkshopPer'     => 'required|integer|min:1',
            'WorkshopPl'      => 'required|string|max:100',
            'WorkshopCname'   => 'required|string|max:100',
            'WorkshopCphone'  => 'required|string|max:20',
            'WorkshopCemail'  => 'required|email|max:100',
            'Wlogo'           => 'nullable|image|max:2048',
            'nolec'           => 'required|integer|min:1',
            'nofees'          => 'required|integer|min:1',
        ];

        // Add workshop title validation based on selected language
        if ($request->optradio === 'arabic') {
            $rules['WorkshopArabicName'] = 'required|string|max:100';
        } elseif ($request->optradio === 'english') {
            $rules['WorkshopEnglishName'] = 'required|string|max:100';
        } elseif ($request->optradio === 'bothLan') {
            $rules['WorkshopArabicName'] = 'required|string|max:100';
            $rules['WorkshopEnglishName'] = 'required|string|max:100';
        }

        $request->validate($rules);

        // 2. Convert dates
        $startDate = Carbon::createFromFormat('m/d/Y', $request->WorkshopSDate)->format('Y-m-d');
        $endDate   = Carbon::createFromFormat('m/d/Y', $request->WorkshopEDate)->format('Y-m-d');

        // 3. Collect Lecturers from dynamic inputs
        $lecturers = [
            'ar_names'   => [],
            'en_names'   => [],
            'ar_details' => [],
            'en_details' => [],
        ];

        for ($i = 0; $i < (int) $request->nolec; $i++) {
            if ($request->optradio === 'arabic' || $request->optradio === 'bothLan') {
                $lecturers['ar_names'][]   = $request->input("LecturerArabicName{$i}");
                $lecturers['ar_details'][] = $request->input("LecturerDetailsInAr{$i}");
            }
            if ($request->optradio === 'english' || $request->optradio === 'bothLan') {
                $lecturers['en_names'][]   = $request->input("LecturerEnglishName{$i}");
                $lecturers['en_details'][] = $request->input("LecturerDetailsInEng{$i}");
            }
        }

        // 4. Logo upload
        $logoPath = $this->handleWorkshopLogo($request, $uni_id);

        // 5. Fees
        [$feesTypes, $fees] = $this->processFees($request);

        // 6. Save to DB
        $workshop = workDetails::create([
            'Uni_id'            => $uni_id,
            'Faculty_id'        => $fac_id,
            'workshop_ar_title' => $request->optradio !== 'english' ? $request->WorkshopArabicName : null,
            'workshop_en_title' => $request->optradio !== 'arabic' ? $request->WorkshopEnglishName : null,
            'workshop_logoPath' => $logoPath,
            'no_lecturers'      => $request->nolec,
            'Lec_ar_names'      => !empty($lecturers['ar_names'])   ? $lecturers['ar_names']   : null,
            'Lec_en_names'      => !empty($lecturers['en_names'])   ? $lecturers['en_names']   : null,
            'Lec_ar_details'    => !empty($lecturers['ar_details']) ? $lecturers['ar_details'] : null,
            'Lec_en_details'    => !empty($lecturers['en_details']) ? $lecturers['en_details'] : null,
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


    
    //Display all workshops
    public function listWorkshops()
    {
        $workshops = workDetails::where('is_approved', 1)->orderBy('st_date', 'desc')->get();
        return view('templ.workshop', compact('workshops'));
    }

    
    //Show single workshop details
    public function showWorkshop($id)
    {
        $workshop = workDetails::where('is_approved', 1)->findOrFail($id);

        // increment views counter
        $workshop->increment('views');
        //$workshop->refresh();

        return view('templ.workshop_details', compact('workshop'));
    }

    // Handle like AJAX
    public function likeWorkshop($id)
    {
        $workshop = workDetails::findOrFail($id);
        $workshop->increment('likes');

        $workshop->refresh();
        return response()->json(['likes' => $workshop->likes]);
    }

    /**
     * Show participant registration form
     */
    public function GetRegForm($workshopId)
    {
        $workshop = workDetails::findOrFail($workshopId);
        return view('Users.PartregistrationForm', [
            'workshop' => $workshop,
            'uniID'    => $workshop->Uni_id,
            'facID'    => $workshop->Faculty_id,
        ]);
    }

    /**
     * Store participant registration
     */
    public function storeRegistrationDetails(Request $request)
    {
        //dd($request);
        $data = $request->validate([
            'workshop_id' => 'required|exists:workshops_details,id',
            'uni_id'      => 'required|exists:universitys,id',
            'fac_id'      => 'required|exists:fac_uni,fac_id',
            'PartName'    => 'required|string|max:300',
            'partGender'  => 'required|string|max:100',
            'partEmail'   => 'nullable|email|max:100',
            'partType'    => 'required|string|max:100',
            'parSubType'  => 'nullable|string|max:100',
        ]);

        if ($data['partType'] === 'Employee') {
            $data['parSubType'] = 'Employee';
        }

        workReg::create([
            'workshop_id'   => $data['workshop_id'],
            'uni_id'        => $data['uni_id'],
            'fac_id'        => $data['fac_id'],
            'full_name'     => $data['PartName'],
            'gender'        => $data['partGender'],
            'email'         => $data['partEmail'],
            'par_type'      => $data['partType'],
            'par_sub_type'  => $data['parSubType'],
        ]);

        return back()->with('message', 'Participant registered successfully for this workshop.');
    }
}
