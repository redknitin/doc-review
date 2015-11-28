@extends('layout.standard')

@section('content')

<h1>
	Documents Awaiting Review
</h1>

<table class="table table-striped">
	<tr>
		<th>ID</th>
		<th>Description</th>
		<th>Amount</th>
		<th>State</th>
		<th>Ref ID</th>
		<th>Action</th>
	</tr>
@foreach($listing as $iter)
	<tr>
		<td>{{$iter->id}}</td>
		<td>{{$iter->description}}</td>
		<td>{{$iter->amount}}</td>
		<td>{{$iter->state}}</td>
		<td>{{$iter->ref_id}}</td>
		<td>
			{!! Html::link('doc/'.$iter->id, 'Detail') !!}
			<!--
				{!! Html::link('review/approve/'.$iter->id, 'Approve') !!}
				{!! Html::link('review/decline/'.$iter->id, 'Decline') !!}
			-->
		</td>
	</tr>
@endforeach
</table>

@endsection