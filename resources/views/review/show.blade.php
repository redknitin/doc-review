@extends('layout.standard')

@section('content')
<p>
	ID
	<br />
	{{$entity->id}}
</p>
<p>
	Description
	<br />
	{{$entity->description}}
</p>
<p>
	State
	<br />
	{{$entity->state}}
</p>
<p>
	Amount
	<br />
	{{$entity->amount}}
</p>
<p>
	Ref. ID
	<br />
	{{$entity->ref_id}}
</p>
<p>
	Ref. Status
	<br />
	{{$entity->ref_status}}
</p>

	@foreach($transition_states as $iterstate)
		<form method="post" action="{{url('submit_review/'.$entity->id)}}">
			<input type="hidden" name="id" value="{{$entity->id}}" />
			<input type="hidden" name="new_state" value="{{$iterstate}}" />
			<input type="hidden" name="op_type" value="state_change" />
			{!! Form::token() !!}
			<input type="submit" value="{{$iterstate}}" />
		</form>
	@endforeach

@endsection