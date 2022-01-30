@extends('layouts.app')

@section('title', 'Launches - ' . config('app.name'))

@section('content')
	<!-- Header-->
    <header class="bg-dark py-2">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Launches</h1>
            </div>
        </div>
    </header>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
        	@include('alert')

        	@if (!empty($error))
                <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-12 row-cols-xl-12 justify-content-center">
                	<div class="alert alert-danger">
	                	<p>There is some issue with the API. Please check back soon.</p>
                	</div>
                </div>
        	@elseif ($data->count())
                <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-3 row-cols-xl-4 justify-content-center">
                	@foreach($data as $launch)
                		<div class="col mb-5">
	                        <div class="card h-100">
	                            <!-- Sale badge-->
	                            @if ($launch['launch_success'])
		                            <div class="badge bg-success text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Success</div>
	                            @endif

	                            <!-- Product image-->
	                            @if (!empty($launch['links']['mission_patch']))
		                            <img class="card-img-top" src="{{ $launch['links']['mission_patch_small'] }}" alt="{{ $launch['mission_name'] }}" />
	                            @else
		                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="{{ $launch['mission_name'] }}" />
	                            @endif

	                            <!-- Product details-->
	                            <div class="card-body p-4">
	                                <div class="text-center">
	                                    <!-- Product name-->
	                                    <h5 class="fw-bolder">
	                                    	<a href="{{ route('launches-show', $launch['flight_number']) }}" class="text-info">{{ $launch['mission_name'] }}</a>
	                                    </h5>
	                                    <!-- Product price-->
	                                    <p>On: <strong>{{ date('jS M Y h:iA', $launch['launch_date_unix']) }}</strong></p>

	                                    <p>{{ Str::words($launch['details'], 10) }}</p>
	                                </div>
	                            </div>
	                            <!-- Product actions-->
	                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
	                                <div class="text-center"><a class="btn btn-outline-info mt-auto" href="{{ route('launches-show', $launch['flight_number']) }}">View Details</a></div>
	                            </div>
	                        </div>
	                    </div>
                	@endforeach
                </div>

                <div class="page-links">
	            	{{ $data->onEachSide(2)->links() }}
                </div>
        	@else
                <div class="row gx-4 gx-lg-5 row-cols-1 row-cols-md-12 row-cols-xl-12 justify-content-center">
                	<div class="alert alert-info">
	                	<p>No matching records found.</p>
                	</div>
                </div>
            @endif
        </div>
    </section>
@endsection