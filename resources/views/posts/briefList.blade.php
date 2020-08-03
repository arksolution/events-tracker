@if (count($posts) > 0)

	@foreach ($posts as $post)
    <tbody>
	<tr id='post-{{ $post->id }}'>
		<td></td>
		<td>
		    @if (isset($post->user))
		      @include('users.avatar', ['user' => $post->user])
		    @else
		    -
		    @endif
		</td>

		<td class="hidden-xs">{{ $post->created_at->diffForHumans() }}</td>
	</tr>
	<tr>
		<td colspan='6' class="post-body">
			<!-- TO DO: change this to storing the trust in the user at post save -->
			@if (isset($post->user) && $post->user->can('trust_post'))
				{!! $post->body !!}
			@else
				{{ $post->body }}
			@endcan
			<span>

			@if ($signedIn && (($post->ownedBy($user) && $post->isRecent()) || $user->hasGroup('super_admin')))
				<a href="{!! route('posts.edit', ['post' => $post->id]) !!}" title="Edit this post."><span class='glyphicon glyphicon-pencil text-primary'></span></a>
				{!! link_form_icon('glyphicon-trash text-warning', $post, 'DELETE', 'Delete the [post]') !!}

			@endif
            @if ($signedIn)
                @if ($like = $post->likedBy($user))
                    <a href="{!! route('posts.unlike', ['id' => $post->id]) !!}" title="Click to unlike"><span class='glyphicon glyphicon-star text-success'></span></a>
                @else
                    <a href="{!! route('posts.like', ['id' => $post->id]) !!}" title="Click to like"><span class='glyphicon glyphicon-star-empty text-warning'></span></a>
                @endif
            @endif
            </span>

		<br>

			@unless ($post->entities->isEmpty())
			Related:
				@foreach ($post->entities as $entity)
					<span class="label label-tag"><a href="/posts/relatedto/{{ urlencode($entity->slug) }}">{{ $entity->name }}</a></span>
				@endforeach
			@endunless

			@unless ($post->tags->isEmpty())
			Tags:
				@foreach ($post->tags as $tag)
					<span class="label label-tag"><a href="/posts/tag/{{ urlencode($tag->name) }}">{{ $tag->name }}</a></span>
				@endforeach
			@endunless
		</td>
	</tr>
	</tbody>
	@endforeach

@else
	<tr>
	<td colspan="6"><i>No posts listed</i></td>
	</tr>
@endif

