<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeviceRating;
use App\Models\Reservation; 
class DeviceRatingController extends Controller
{

    public function create()
    {
      $reservations = Reservation::with(['device', 'rating'])->where('user_id', auth()->id())->get();

        return view('templ.rating', compact('reservations'));
    }

    public function store(Request $request)
    {
        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'service_quality' => 'required|integer|min:1|max:5',
            'device_info_clarity' => 'nullable|integer|min:1|max:5',
            'search_interface' => 'nullable|integer|min:1|max:5',
            'request_steps_clarity' => 'nullable|integer|min:1|max:5',
            'device_condition' => 'nullable|integer|min:1|max:5',
            'research_results_quality' => 'nullable|integer|min:1|max:5',
            'device_availability' => 'nullable|integer|min:1|max:5',
            'response_speed' => 'nullable|integer|min:1|max:5',
            'technical_support' => 'nullable|integer|min:1|max:5',
            'research_success' => 'nullable|integer|min:1|max:5',
            'recommend_service' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string'
        ];

        $request->validate($rules);

        $reservation = Reservation::where('id', $request->reservation_id)
            ->where('user_id', auth()->id())
            ->where('confirmation', 'Confirmed')
            ->firstOrFail();

        DeviceRating::create([
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
            'device_id' => $reservation->device_id,
            'service_quality' => $request->service_quality,
            'device_info_clarity' => $request->device_info_clarity,
            'search_interface' => $request->search_interface,
            'request_steps_clarity' => $request->request_steps_clarity,
            'device_condition' => $request->device_condition,
            'research_results_quality' => $request->research_results_quality,
            'device_availability' => $request->device_availability,
            'response_speed' => $request->response_speed,
            'technical_support' => $request->technical_support,
            'research_success' => $request->research_success,
            'recommend_service' => $request->recommend_service,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'تم إرسال تقييمك بنجاح');
    }

    public function update(Request $request, DeviceRating $rating)
    {

        $rules = [
            'reservation_id' => 'required|exists:reservations,id',
            'service_quality' => 'required|integer|min:1|max:5',
            'device_info_clarity' => 'nullable|integer|min:1|max:5',
            'search_interface' => 'nullable|integer|min:1|max:5',
            'request_steps_clarity' => 'nullable|integer|min:1|max:5',
            'device_condition' => 'nullable|integer|min:1|max:5',
            'research_results_quality' => 'nullable|integer|min:1|max:5',
            'device_availability' => 'nullable|integer|min:1|max:5',
            'response_speed' => 'nullable|integer|min:1|max:5',
            'technical_support' => 'nullable|integer|min:1|max:5',
            'research_success' => 'nullable|integer|min:1|max:5',
            'recommend_service' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string'
        ];

       $validated = $request->validate($rules);

        $rating->update($validated);

        return back()->with('success', 'Rating updated successfully.');
    }
}
