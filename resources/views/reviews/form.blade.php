<div class="row">
	<div class="form-group {{$errors->has('name') ? 'has-error' : '' }} col-md-4">
		{!! Form::label('review','Review') !!}
		{!! Form::textArea('review', null, ['class' =>'form-control']) !!}
		{!! $errors->first('review','<span class="help-block">:message</span>') !!}
	</div>
</div>


<div class="row">
	<div class="form-group col-md-2">
		{!! Form::label('review_type_id','Type') !!}
		{!! Form::select('review_type_id', $reviewTypes, (isset($eventReview->review_type_id) ? $eventReview->event_type_id : NULL),['class' =>'form-control']) !!}
		{!! $errors->first('review_type_id','<span class="help-block">:message</span>') !!}
	</div>

</div>
<div class="row">
	<div class="form-group col-md-2">
		{!! Form::label('attended','Attended') !!}
		{!! Form::checkbox('attended', null, ['placeholder' => 'Attended','class' =>'form-control']) !!}
		{!! $errors->first('attended','<span class="help-block">:message</span>') !!}
	</div>

	<div class="form-group col-md-2">
		{!! Form::label('confirmed','Confirmed') !!}
		{!! Form::checkbox('confirmed', null, ['placeholder' => 'Attendance confirmed','class' =>'form-control']) !!}
		{!! $errors->first('confirmed','<span class="help-block">:message</span>') !!}
	</div>
</div>
<div class="row">
	<div class="form-group col-md-2">
		{!! Form::label('expectation','Expected') !!}
		{!! Form::text('expectation', null, ['placeholder' => 'Expected rating (1-10)','class' =>'form-control']) !!}
		{!! $errors->first('expectation','<span class="help-block">:message</span>') !!}
	</div>

	<div class="form-group col-md-2">
		{!! Form::label('rating','Rating') !!}
		{!! Form::text('rating', null, ['placeholder' => 'Rating (1-10)','class' =>'form-control']) !!}
		{!! $errors->first('rating','<span class="help-block">:message</span>') !!}
	</div>


</div>

<div class="form-group">
	{!! Form::submit(isset($action) ? 'Update Review' : 'Add Review', null, ['class' =>'btn btn-primary']) !!}
</div>
