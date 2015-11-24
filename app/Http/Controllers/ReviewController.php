<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

require(app_path().'/includes/state-machine.php');

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $arr = [];
        
        // $obj_mock1 = new \stdClass();
        // $obj_mock1->id = 1;
        // $obj_mock1->description = 'Chlorine based cleaner';
        // $obj_mock1->amount = 14.25;
        // $obj_mock1->ref_id='10012';

        // $arr[] = $obj_mock1;
        
        // $obj_mock2 = new \stdClass();
        // $obj_mock2->id = 2;
        // $obj_mock2->description = 'Electrical wire';
        // $obj_mock2->amount = 8.25;
        // $obj_mock2->ref_id='10013';

        // $arr[] = $obj_mock2;

        $arr = \App\Document::all();

        return view('review.index')->withListing($arr);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $entity = \App\Document::find($id);

        $states_to_transition = [];

        global $state_machine;

        foreach($state_machine['material-request']['transitions'] as $transkey=>$itertrans) {
            $possible_trans = false;
            foreach($itertrans['from'] as $iter_from_state) {
                if ($iter_from_state==$entity->state) {
                    $possible_trans = true;
                    break;
                }
            }

            if (!$possible_trans) {
                continue;
            }
            else 
            {
                foreach ($itertrans['conditions'] as $trialbyfire) {
                    if (!$trialbyfire($entity)) {
                        $possible_trans = false;
                        continue;
                    }
                }

                if ($possible_trans) {
                    $states_to_transition[] = $itertrans['to'];
                }
            }
        }

        return view('review.show')->withEntity($entity)->withTransitionStates($states_to_transition);
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
        $entity = \App\Document::find($id);
        if ($request->get('op_type')=='state_change') {
            $entity->state=$request->get('new_state');
            $entity->save();
        }
        return Redirect('/');
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
