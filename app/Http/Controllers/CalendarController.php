<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\WorkDetails;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('templ.calendar');
    }

// public function getEvents(Request $request)
// {
//     $month = $request->input('month', date('m'));
//     $year = $request->input('year', date('Y'));
    
//     // Get start and end dates for the month
//     $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
//     $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

//     $news = News::where('is_active', 1)
//         ->whereBetween('publish_date', [$startDate, $endDate])
//         ->select('id', 'title', 'publish_date', 'time', 'location')
//         ->get()
//         ->map(function ($n) {
//             return [
//                 'id' => 'news-' . $n->id,
//                 'title' => $n->title,
//                 'start' => $n->publish_date,
//                 'end' => $n->publish_date,
//                 'location' => $n->location,
//                 'time' => $n->time,
//                 'color' => '#198754',
//                 'type' => 'news'
//             ];
//         })
//         ->toArray(); // Convert to array

//     $workshops = WorkDetails::where('is_approved', 1)
//         ->where(function($query) use ($startDate, $endDate) {
//             $query->whereBetween('st_date', [$startDate, $endDate])
//                   ->orWhereBetween('end_date', [$startDate, $endDate])
//                   ->orWhere(function($q) use ($startDate, $endDate) {
//                       $q->where('st_date', '<=', $startDate)
//                         ->where('end_date', '>=', $endDate);
//                   });
//         })
//         ->select('id', 'workshop_ar_title', 'st_date', 'end_date', 'place')
//         ->get()
//         ->map(function ($w) {
//             return [
//                 'id' => 'workshop-' . $w->id,
//                 'title' => $w->workshop_ar_title,
//                 'start' => $w->st_date,
//                 'end' => $w->end_date,
//                 'location' => $w->place,
//                 'color' => '#0d6efd',
//                 'type' => 'workshop'
//             ];
//         })
//         ->toArray(); // Convert to array

//     // Merge arrays and return
//     return response()->json(array_merge($news, $workshops));
// }
public function getEvents(Request $request)
{
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));
    
    $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
    $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

    $news = News::where('is_active', 1)
            ->whereBetween('publish_date', [$startDate, $endDate])
            ->select('id', 'title', 'publish_date', 'time', 'location')
            ->get()
            ->map(function ($n) {
                return [
                    'id' => 'news-' . $n->id,
                    'event_id' =>$n->id,
                    'title' => $n->title,
                    'start' => \Carbon\Carbon::parse($n->publish_date)->format('Y-m-d'),
                    'end' => \Carbon\Carbon::parse($n->publish_date)->format('Y-m-d'),
                    'location' => $n->location,
                    'time' => $n->time,
                    'color' => '#198754',
                    'type' => 'news'
                ];
            });

    $workshops = WorkDetails::where('is_approved', 1)
        ->where(function($query) use ($startDate, $endDate) {
            $query->whereBetween('st_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q) use ($startDate, $endDate) {
                      $q->where('st_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
        })
        ->select('id', 'workshop_ar_title', 'workshop_en_title','st_date', 'end_date', 'place')
        ->get()
        ->map(function ($w) {
            return [
                'id' => 'workshop-' . $w->id,
                'event_id' =>$w->id,
                'title' => !empty($w->workshop_ar_title) ? $w->workshop_ar_title : $w->workshop_en_title,
                'start' => $w->st_date,
                'end' => $w->end_date,
                'location' => $w->place,
                'color' => '#0d6efd',
                'type' => 'workshop'
            ];
        });

    // Use concat instead of merge for arrays within Collection
    return response()->json($news->concat($workshops)->values());
}
}
