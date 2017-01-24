<?php

namespace App\Http\Controllers;

use App\AgronomicMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AgronomicMessageController extends Controller
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

        return AgronomicMessage::paginate(5);
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
                'unfavourable_lower_bound_message' => 'required',
                'unfavourable_lower_bound' => 'required',
                'parameters' => 'required',
                'optional_lower' => 'required',
                'optional_message' => 'required',
                'unfavourable_upper_bound_message' => 'required',
                'unfavourable_upper_bound' => 'required',
                'stage_id' => 'required|exists:stages,id',
            )
        ); //close validation

        //If validation fail send back the Input with errors
        if ($validation->fails()) {
            //withInput keep the users info
            return $validation->messages();
        } else {
            $season = new AgronomicMessage();
            $season->stage_id = $request['stage_id'];
            $season->parameters = $request['parameters'];
            $season->unfavourable_lower_bound = $request['unfavourable_lower_bound'];
            $season->unfavourable_lower_bound_message = $request['unfavourable_lower_bound_message'];
            $season->optional_lower = $request['optional_lower'];
            $season->optional_message = $request['optional_message'];
            $season->unfavourable_upper_bound = $request['unfavourable_upper_bound'];
            $season->unfavourable_upper_bound_message = $request['unfavourable_upper_bound_message'];
            $season->save();

            return $season->toJson();// remove to JSON incase of error
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
