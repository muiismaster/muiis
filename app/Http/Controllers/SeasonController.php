<?php

namespace App\Http\Controllers;

use App\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($currentPage)
    {
        //
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        return Season::paginate(5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validation = Validator::make(Input::all(),
            array(
                'start_day' => 'required|date',
                'end_day' => 'required|date|after:start_day',
                'date_created' => 'required|date',
                'crop_id' => 'required|exists:crops,id',
            )
        ); //close validation

        //If validation fail send back the Input with errors
        if ($validation->fails()) {
            //withInput keep the users info
            //return $validation->messages();
            return Response::json([
                'errors' => $validation->messages
            ], 500); // Status code here
        } else {
            $season = new Season();
            $season->start_day = $request['start_day'];
            $season->end_day = $request['end_day'];
            $season->date_created = $request['date_created'];
            $season->crop_id = $request['crop_id'];
            $season->save();

            //return $season->toJson();// remove to JSON incase of error
            return Response::json([
                'season' => $season
            ], 200); // Status code here
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    DB::table('seasons')->update(['start_day'=>$request['start_day'], 'end_day'=>$request['end_day'], 'date_created'=>$request['date_created'], 'crop_id'=>$request['crop_id']])
            ->where('seasons.id', $id);

        return Season::findOrFail($id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
