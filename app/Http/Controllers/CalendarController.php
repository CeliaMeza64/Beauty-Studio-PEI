<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        
        $date = Carbon::createFromDate($year, $month, 1);

        
        return view('reservas.calendar', [
            'date' => $date,
        ]);
    }
}
