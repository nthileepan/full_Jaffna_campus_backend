<?php

namespace App\Http\Controllers;

use App\Models\studentPaymentsDetail;
use App\Models\transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{

    public function index()
    {
        $thisMonthDue = studentPaymentsDetail::whereMonth('next_due_date',Carbon::today()->month)->sum('next_due_amount');
        $todayCollection = transaction::whereDate('created_at', Carbon::today())->sum('amount');
        $noOfStudent = DB::table('students')->count();
        $todayArrers = studentPaymentsDetail::whereMonth('next_due_date',Carbon::today()->month)->sum('arrears');
        $thisMonthCollection = transaction::whereMonth('created_at',Carbon::today()->month)->sum('amount');

        return response()->json([
            'thisMonthDue' => $thisMonthDue,
            'todayCollection' => $todayCollection,
            'noOfStudent' => $noOfStudent,
            'todayArrers' => $todayArrers,
            'thisMonthCollection' => $thisMonthCollection
        ]);
    }
}
