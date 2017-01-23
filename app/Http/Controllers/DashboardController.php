<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //return total summaries

    public function farmersSummary(){
        
        $total = DB::table('farmers')->get()->Count();       

        $farmersSummary = array("title"=>'Farmers',
        "subtitle"=>'Total Farmers Profiled',
        "total"=> $total);

        return $farmersSummary;        
    }


     public function subscriptionSummary(){
        
        $total = DB::table('subscriptions')->get()->Count();       

        $subscriptionSummary = array("title"=>'Subscriptions',
        "subtitle"=>'Total Farmer Insurance Subscriptions',
        "total"=> $total);

        return $subscriptionSummary;        
    }


    public function smsSummary(){
        
        $total = DB::table('sms')
        //->where('status','=', 'Pending')
        ->count();       

        $smsSummary = array("title"=>'Messages',
        "subtitle"=>'Total sms\'s sent to farmers',
        "total"=> $total);

        return $smsSummary;        
    }


    public function paymentsSummary(){
        
        $total = DB::table('payments')->where('status','=', 'Pending')->sum('amount');       

        $paymentsSummary = array("title"=>'Payments (UGX)',
        "subtitle"=>'Total Insurance Payments',
        "total"=> $total);

        return $paymentsSummary;        
    }


}
