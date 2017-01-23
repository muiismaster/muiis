<?php

namespace App\Http\Controllers;
use AfricasTalkingGateway;
use App\AfricasTalkingGatewayException;
use Carbon\Carbon;
use App\Farmer as Farmer;
use App\Subscription as Subscription;
use App\Payment as Payment;
use App\Crop as Crop;
use App\Season as Season;
use App\Sms as Sms;
use App\Region as Region;
use App\District as District;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    // return paginated lis of sent messages
    public function index($currentPage=1,$startDate=null,$endDate=null)
    {

        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if($startDate==null){
            $sms = Sms::where('transaction_date', '>=', Carbon::now()->subMonth(2))
                                        ->where('transaction_date', '<=', Carbon::now())
                                        ->orderBy('id','desc')->paginate(1000);
            return  $sms;
        }else{
            //use supplied date range
             $dtStart = Carbon::createFromFormat('Y-m-d', $startDate);
             $dtEnd = Carbon::createFromFormat('Y-m-d',$endDate);
             $sms = Sms::where('transaction_date', '>=', $dtStart )
                                        ->where('transaction_date', '<=', $dtEnd)
                                        ->orderBy('id','desc')->paginate(1000);
            return  $sms;
        }

    }

   

     //scheduled  message                   
    public function scheduledsms($currentPage=1,$startDate=null,$endDate=null)
    {
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                    return $currentPage;
                });

        if($startDate==null){
            $sms = Sms::where('transaction_date', '>=', Carbon::now()->subMonth(2))
                                        ->where('transaction_date', '<=', Carbon::now())
                                        ->where('is_scheduled','1')
                                        ->orderBy('id','desc')->paginate(1000);
            return  $sms;
        }else{
            //use supplied date range
            $dtStart = Carbon::createFromFormat('Y-m-d', $startDate);
            $dtEnd = Carbon::createFromFormat('Y-m-d',$endDate);
            $sms = Sms::where('transaction_date', '>=', $dtStart )
                                        ->where('transaction_date', '<=', $dtEnd)
                                        ->where('is_scheduled','1')
                                        ->orderBy('id','desc')->paginate(1000);
            return  $sms;
        }
    }

    //send message 
    public function send(Request $request)
    {
        
            $region_filter = $request->input('region_filter');
            $district_filter = $request->input('district_filter');
            $crop_filter = $request->input('crop_filter');
            $no_filter = $request->input('no_filter');
            $text = $request->input('text');
            $schedule_date = $request->input('schedule_date');

            $region =null;
            $crop =null;
            $district =null;
             $farmers =null;
           //check filters
            if ($region_filter) {
                $region = Region::find($region_filter);
                $farmers = DB::table('farmers')
                    ->join('subscriptions', 'farmers.id', '=', 'subscriptions.farmer_id')
                    ->join('seasons', 'subscriptions.season_id', '=', 'seasons.id')
                    ->where('farmers.region', $region->name)
                    ->where('seasons.start_day', '<' ,Carbon::now())
                    ->where('seasons.end_day', '>', Carbon::now())
                    ->select(['farmers.phone'])
                    ->get();
            }

            
           
            if ($district_filter) {
                $district = District::find($district_filter);
                $farmers = DB::table('farmers')
                    ->join('subscriptions', 'farmers.id', '=', 'subscriptions.farmer_id')
                    ->join('seasons', 'subscriptions.season_id', '=', 'seasons.id')
                    ->where('farmers.district', $district->name)
                    ->where('seasons.start_day', '<' ,Carbon::now())
                    ->where('seasons.end_day', '>', Carbon::now())
                    ->select(['farmers.phone'])
                    ->get();
            }

           
            if ($crop_filter) {
                $crop = Crop::find($crop_filter);
                $farmers = DB::table('subscriptions')
                    ->join('farmers', 'subscriptions.farmer_id', '=', 'farmers.id')
                    ->join('seasons', 'subscriptions.season_id', '=', 'seasons.id')
                    ->where('seasons.start_day', '<' ,Carbon::now())
                    ->where('subscriptions.crop_id', $crop_filter)
                    ->where('seasons.end_day', '>', Carbon::now())
                    ->select(['farmers.phone'])
                    ->get();
            }

            if($schedule_date !=''){
                //shecedule message for later sending
                $all_farmers = collect($farmers);
                $sms = new Sms;

                $sms->text = (string)$text;
                if( count($farmers) >0 )
                   $sms->sent_to = count($farmers).' farmers'  ;
                else
                   $sms->sent_to = $no_filter  ;
                   
                $sms->status = "Schecudled";
                $sms->cost = '0';                    

                $sms->region_filter = $region != null ? (string) $region->name : '';
                $sms->district_filter = $district != null ? (string) $district->name : '';
                $sms->crop_filter = $crop != null ? (string) $crop->crop_name : '';
                $sms->transaction_date = Carbon::now();
                $sms->schedule_date = $schedule_date != null ? Carbon::createFromFormat('Y-m-d', $schedule_date): null;
                $sms->is_scheduled = 1;

                $sms->save();
                // $this->send_message($no_filter, $text);

            }else if ($no_filter != '') {
                //  $sms->schedule_date = $schedule_date;

                $res = $this->send_message($no_filter, $text);
                if ($res != null) {

                    try {

                        foreach ($res as $result) {

                            $sms = new Sms;
                            $sms->text = (string)$text;
                            $sms->sent_to = (string) $result->number;
                            $sms->status =  $result->status;
                            $sms->cost = $result->cost;

                            $sms->region_filter = $region != null ? (string) $region->name : '';
                            $sms->district_filter = $district != null ? (string) $district->name : '';
                            $sms->crop_filter = $crop != null ? (string) $crop->crop_name : '';
                            $sms->transaction_date = Carbon::now();
                            //$sms->schedule_date = Carbon::createFromFormat('Y-m-d', $schedule_date);
                            $sms->save();
                        }

                        return redirect()->action('SmsController@index', ['currentPage' => 1]);

                    } catch (Exception $e) {
                        // do task when error
                        return $e->getMessage();  
                    }
                }

            } else {

                $all_farmers = collect($farmers);
                try {
               
               // if($all_farmers = '' || count($farmers)<=0)
               //   return "No numbers supplied for sms";

                    $res = $this->send_message($all_farmers, $text);
                    
                    if ($res != null) {
                        foreach ($res as $result) {

                            $sms = new Sms;

                            $sms->text = $result->message;
                            $sms->sent_to = $result->number;
                            $sms->status = $result->status;
                            $sms->cost = $result->cost;

                            $sms->region_filter = $result->region_filter;
                            $sms->district_filter = $result->district_filter;
                            $sms->crop_filter = $result->crop_filter;

                            $sms->save();

                        }

                    return redirect()->action('SmsController@index', ['currentPage' => 1]);
                }

                } catch (Exception $e) {
                    // do task when error
                    return $e->getMessage();   // insert query
                }
                
            }
        
    }

    
    public function send_message($recipients=null,$message=null){
            
        // Specify your login credentials
        $username   = "muiis";
        $apikey     = "21827a39d3b453dd513caaa7ba13fe36f9f87a36ae3d7f89af8eb9929f0fc50d";

        // Specify the numbers that you want to send to in a comma-separated list
        // Please ensure you include the country code (+256 for Uganda in this case)
        if($recipients==null)
            $recipients =  "+256787738616"; //,+256784724155

        // And of course we want our recipients to know what we really do
        if($message==null)
            $message    = "Test message random ".rand(1,10);

        // Create a new instance of our awesome gateway class
        $gateway    = new AfricasTalkingGateway($username, $apikey);

        // Any gateway error will be captured by our custom Exception class below, 
        // so wrap the call in a try-catch block

        try 
        { 
        // Thats it, hit send and we'll take care of the rest. 
        $results = $gateway->sendMessage($recipients, $message);			
        
        }
        catch ( AfricasTalkingGatewayException $e )
        {
        // echo "Encountered an error while sending: ".$e->getMessage();
        $results[] = null;
        }        
        
        return $results;
    }

    public function districts(){
        $districts = District::with('region')->get();
        return $districts;
    }

    public function regions(){
        $regions = Region::all();
        return $regions;
    }

    public function crops(){
        $crops = Crop::all();
        return $crops;
    }

}
