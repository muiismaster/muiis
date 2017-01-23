<?php
namespace App\Http\Controllers;

//require 'vendor/autoload.php';
use Carbon\Carbon;
use App\Farmer as Farmer;
use App\Subscription as Subscription;
use App\Payment as Payment;
use App\Crop as Crop;
use App\Season as Season;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a summary of subscription payments
     *
     * @return \Illuminate\Http\Response
     */
    public function index($currentPage=1,$startDate=null,$endDate=null)
    {

        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if($startDate==null){
            $subscriptions = Subscription::where('subscribe_date', '>=', Carbon::now()->subMonth(2))
                                        ->where('subscribe_date', '<=', Carbon::now())
                                        ->orderBy('id','desc')
                                        ->with('payments','farmer','season','season.crop')->paginate(5);
            return $subscriptions;
        }else{
            //use supplied date range
             $dtStart = Carbon::createFromFormat('Y-m-d', $startDate);
             $dtEnd = Carbon::createFromFormat('Y-m-d',$endDate);
             $subscriptions = Subscription::where('subscribe_date', '>=', $dtStart )
                                        ->where('subscribe_date', '<=', $dtEnd)
                                        ->orderBy('id','desc')
                                         ->with('payments','farmer','season','season.crop')->paginate(5);
            return $subscriptions;
        }

    }


    public function payment_history($currentPage=1, $phone=null)
    {
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if($phone!= null){         

             $farmers = Farmer::where('phone','=',$phone)->with('payments','payments.subscription','payments.subscription.season'
        ,'payments.subscription.season.crop' )->orderBy('id','desc')->paginate(1);  
        
         return $farmers; 
           /* 
             // sample lazyloading filter
             $payments = Payment::whereHas('farmer', function($q) use($phone) {
            $q->where('phone', '=', $phone);
            })
            ->orderBy('id','desc')
            ->with('farmer','subscription','subscription.season' ,'subscription.season.crop')->get();
            return $payments;   
            */
        }else{
           
            $farmers = Farmer::with('payments','payments.subscription','payments.subscription.season'
        ,'payments.subscription.season.crop' )->orderBy('id','desc')->paginate(1);  

            return $farmers; 
        }
                 
    }

    public function unsubscribed_farmers($currentPage=1)
    {
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

       return Farmer::paginate(5);
    }
   
}
