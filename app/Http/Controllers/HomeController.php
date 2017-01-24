<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function dash(){
        $payments = DB::table('payments')
            ->select(DB::raw("(SUM(amount)) as payments"), DB::raw("YEAR(date_created) as year"), DB::raw("MONTH(date_created) as month"), DB::raw("DAY(date_created) as day"))
            ->orderBy('date_created')
            ->groupBy(DB::raw("DAY(date_created)"))
            ->get();

//        dd($payments);
        $subscriptions = DB::table('subscriptions')
            ->select(DB::raw("(COUNT(*)) as subscriptions"), DB::raw("YEAR(date_created) as year"), DB::raw("MONTH(date_created) as month"), DB::raw("DAY(date_created) as day"))
            ->orderBy('date_created')
            ->groupBy(DB::raw("DAY(date_created)"))
            ->get();
//        dd($subscriptions);
        return view('index', ['payments'=>$payments, 'subscriptions'=>$subscriptions]);
    }
}
