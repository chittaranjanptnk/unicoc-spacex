@extends('layouts.app')

@section('content')
	<!-- Header-->
    <header class="bg-dark py-2">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">{{ $data['flight_number'] }} - {{ $data['mission_name'] }}</h1>
            </div>
        </div>
    </header>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
        	@if (!empty($error))
                <div class="row gx-4 gx-lg-5 align-items-center">
                	<div class="alert alert-danger">
	                	<p>There is some issue with the API. Please check back soon.</p>
                	</div>
                </div>
            @else
                <div class="row gx-4 gx-lg-5 align-items-center mb-5">
	            	<div class="col-md-6">
	            		@if (!empty($data['links']['mission_patch']))
	            			<img class="card-img-top mb-5 mb-md-0" src="{{ $data['links']['mission_patch'] }}" alt="{{ $data['mission_name'] }}" />		
	            		@endif
            		</div>

            		<div class="col-md-6">
            			<div class="fs-5 mb-2">
            				@if ($data['launch_success'])
            					<p>Launch Status: <span class="text-success">Success</span></p>
        					@else
        						<p>Launch Status: <span class="text-decoration-line-through text-danger">Success</span></p>
    						@endif
            			</div>

            			<h1 class="display-5 fw-bolder">{{ $data['mission_name'] }}</h1>

            			<div class="small mb-2">Launched On: <strong>{{ date('jS M Y h:iA', $data['launch_date_unix']) }}</strong></div>

	            		<div class="desc">
		            		<p class="lead">{{ $data['details'] }}</p>
	            		</div>
            		</div>
        		</div>

        		@if (!empty($data['launch_site']))
	                <div class="row gx-4 gx-lg-5 align-items-center mb-5">
	            		<div class="col-md-12">
		            		<h3>Launch Site</h3>

		            		<div class="row">
			            		<div class="col-md-4">Id: {{ $data['launch_site']['site_id'] }}</div>
			            		<div class="col-md-8">Name: {{ $data['launch_site']['site_name_long'] }}</div>
		            		</div>
	            		</div>
	        		</div>
        		@endif

        		@if (!empty($data['rocket']))
	                <div class="row gx-4 gx-lg-5 align-items-center mb-5">
	            		<div class="col-md-12">
		            		<h3>Rocket</h3>

		            		<div class="row">
			            		<div class="col-md-4">Id: {{ $data['rocket']['rocket_id'] }}</div>
			            		<div class="col-md-4">Name: {{ $data['rocket']['rocket_name'] }}</div>
			            		<div class="col-md-4">Type: {{ $data['rocket']['rocket_type'] }}</div>
		            		</div>
	            		</div>
	        		</div>
        		@endif

        		@if (!empty($data['links']['flickr_images']))
	                <div class="row gx-4 gx-lg-5 align-items-center mb-5">
	            		<div class="col-md-12">
		            		<h3>Gallery</h3>

		            		<div class="row text-center text-lg-start">
		            			@foreach($data['links']['flickr_images'] as $image)
								    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
								      	<span href="#" class="d-block mb-4 h-100">
								        	<img class="img-fluid img-thumbnail" src="{{ $image }}" alt="{{ $image }}" />
						      			</span>
						    		</div>
		            			@endforeach
	            			</div>
	            		</div>
	        		</div>
        		@endif
            @endif
        </div>
    </section>
@endsection