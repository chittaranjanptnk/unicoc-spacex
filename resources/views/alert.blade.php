@if (session()->has('error'))
	<div class="alert alert-danger {!! isset($class) ? $class : '' !!}">
		{!! session()->get('error') !!}
	</div>
@endif

@if (session()->has('success'))
	<div class="alert alert-success {!! isset($class) ? $class : '' !!}">
		{!! session()->get('success') !!}
	</div>
@endif

@if (isset($errors) && $errors->count() > 0)
	<div class="alert alert-danger form-error {!! isset($class) ? $class : '' !!}">
		<strong>There are errors:</strong>
		<ul id="form-errors">
			@foreach ($errors->all(':message') as $message)
				<li>{!! $message !!}</li>
			@endforeach
		</ul>
	</div>
@endif