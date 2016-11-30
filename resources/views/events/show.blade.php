@extends('app')

@section('title','Event View')

@section('content')

<h1>Event
	@include('events.crumbs', ['slug' => $event->slug])
</h1>

<P>
@if ($user && Auth::user()->id == $event->user->id)	
	<a href="{!! route('events.edit', ['id' => $event->id]) !!}" class="btn btn-primary">Edit Event</a>
@endif
	<a href="{!! URL::route('events.index') !!}" class="btn btn-info">Return to list</a>
</P>

<div class="row">
<div class="col-md-6">
	<div class='event-date'>
	<h2>{!! $event->start_at->format('l F jS Y') !!}</h2>

	{!! $event->start_at->format('h:i A') !!} {!! $event->end_time ? 'until '.$event->end_time->format('h:i A') : '' !!}
	</div>

	<h2>{{ $event->name }}</h2>
	<i>{{ $event->short }}</i><br>


	<b>
	@if (!empty($event->series_id))
	<a href="/series/{{$event->series_id }}">{!! $event->series->name !!}</a> series
	@endif

	<a href="/events/type/{{$event->eventType->name }}">{{ $event->eventType->name }}</a>
	<br>

	@if (!empty($event->venue_id))
	<a href="/entities/{{$event->venue->id }}">{!! $event->venue->name !!}</a>

	@if ($event->venue->getPrimaryLocationAddress() )
		{{ $event->venue->getPrimaryLocationAddress() }}
	@endif
	@else
	no venue specified
	@endif
	</b>

	@if ($event->door_price)
	${{ number_format($event->door_price,0) }}
	@endif
 	
 	@if ($event->min_age)
	{{ $event->min_age }}
	@endif

	<br>
	@if ($link = $event->primary_link)
	<a href="{{ $link }}" target="_" title="Primary link">
	<span class='glyphicon glyphicon-link'></span>
	</a>
	@endif
	@if ($ticket = $event->ticket_link)
	<a href="{{ $link }}" target="_" title="Ticket link">
	<span class='glyphicon glyphicon-shopping-cart'></span>
	</a>
	@endif

	<!-- display attending - x-editable or just dropdown -->

	@if ($signedIn)
		@if ($response = $event->getEventResponse($user))
		<a href="{!! route('events.unattending', ['id' => $event->id]) !!}" title="Click to mark unattending"><span class='glyphicon glyphicon-star text-warning'></span> {{ $response->responseType->name }}</a>
		@else
		<a href="{!! route('events.attending', ['id' => $event->id]) !!}" title="Click to mark attending"><span class='glyphicon glyphicon-star text-info'></span>  No response</a>
		@endif
	@endif

	{{ $event->attendingCount }} users attending

 	<br><br>

	<p> 
	@if ($event->description)
	<event class="body">
		{!! nl2br($event->description) !!}
	</event> 
	@endif

	<br>
	<i>Added by <a href="/users/{{ $event->user->id }}">{{ $event->user->name or '' }}</a></i>

	<P>
	@unless ($event->entities->isEmpty())
	Related Entities:
		@foreach ($event->entities as $entity)
		<span class="label label-tag"><a href="/events/relatedto/{{ $entity->slug }}">{{ $entity->name }}</a></span>
		@endforeach
	@endunless
	</P>

	@unless ($event->tags->isEmpty())
	<P>Tags:
	@foreach ($event->tags as $tag)
		<span class="label label-tag"><a href="/events/tag/{{ $tag->name }}">{{ $tag->name }}</a></span>
		@endforeach
	@endunless
	</P>
	</div>

	<div class="col-md-6">
	@if ($user && Auth::user()->id == $event->user->id)	
	<form action="/events/{{ $event->id }}/photos" class="dropzone" method="POST">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
	</form>
	@endif

	<br style="clear: left;"/>

	@foreach ($event->photos->chunk(4) as $set)
	<div class="row">
	@foreach ($set as $photo)
		<div class="col-md-2">
		<a href="/{{ $photo->path }}" data-lightbox="{{ $photo->path }}"><img src="/{{ $photo->thumbnail }}" alt="{{ $event->name}}"  style="max-width: 100%;"></a>
		@if ($user && Auth::user()->id == $event->user->id)	
			{!! link_form('Delete', $photo, 'DELETE') !!}
			@if ($photo->is_primary)
			<button class="btn btn-success">Primary</button>
			{!! link_form('Unset Primary', '/photos/'.$photo->id.'/unsetPrimary', 'POST') !!}
			@else
			{!! link_form('Make Primary', '/photos/'.$photo->id.'/setPrimary', 'POST') !!}
			@endif
		@endif
		</div>
	@endforeach
	</div>
	@endforeach
	</div>

</div>

	<div class="row">
		<div class="col-md-4">
			@if ($comments = $event->comments AND count($comments) > 0)
			<b>Comments:</b><br>
			@foreach ($comments as $comment)
				<div class="well well-sm">
					<b>{{ $comment->author->name }}</b><br>
					{!! $comment->message !!}<br>
					{{ $comment->created_at->diffForHumans() }} <br>
					@if ($signedIn && $comment->createdBy($user))
					<a href="{!! route('events.comments.edit', ['event' => $event->id, 'id' => $comment->id]) !!}">
					<span class='glyphicon glyphicon-pencil'></span></a>
					{!! Form::open(['route' => ['events.comments.destroy', 'event' => $event->id, 'id' => $comment->id], 'method' => 'delete']) !!}
        			<button type="submit" class="btn btn-danger btn-mini">Delete</button>
    				{!! Form::close() !!}

					@endif
				</div>
			@endforeach
			@endif
		</div>

	</div>


	<P>
	@if (Auth::user())	
		<span> 
			<a href="{!! route('events.comments.create', ['id' => $event->id]) !!}" class="btn btn-primary">Add Comment</a>
		</span>
	@endif
	</P>

@stop

@section('scripts.footer')
<script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>
<script>
Dropzone.options.addPhotosForm = {
	maxFilesize: 3,
	accept: ['.jpg','.png','.gif']
}
</script>
@stop
