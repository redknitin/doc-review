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
		$wi = new \WorkflowInstance('material-request', $entity->id, $entity->state, $entity);

        $states_to_transition = [];
		$states_to_transition = $wi->getTransitionStates(\Auth::user()->email);

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
		$wi = new \WorkflowInstance('material-request', $entity->id, $entity->state, $entity);
		
        if ($request->get('op_type')=='state_change') {
            $new_state = $request->get('new_state');
			
			$wi->setState($new_state);
			$entity->state=$new_state;
            $entity->save();
        } // end if op_type is state_change
		
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
