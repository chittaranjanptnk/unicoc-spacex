<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LaunchController extends Controller
{
    protected $api_url = NULL;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->api_url = config('spacex.api_url');
    }

    /**
     * Show launches from api
     * @return view
     */
    public function index()
    {
        $error = false;
        $data = [];
        $page = request()->get('page', 0);
        $pageSize = request()->get('size', config('spacex.page_size'));

        $queryParams = [
            'sort' => 'launch_date_unix',
            'order' => 'desc'
        ];

        try {
            $response = Http::get($this->api_url . 'launches', $queryParams);

            if ($response->successful()) {
                $data = $this->paginate($response->json(), $pageSize, $page)
                    ->withPath('launches')
                    ->appends(['size' => $pageSize]);
            } else {
                $error = true;
            }
        } catch(Exception $ex) {
            $error = true;
        }

        return view('launches.index', compact('error', 'data'));
    }

    /**
     * Show launch details
     * @param  int $flightNumber
     * @return view
     */
    public function show($flightNumber)
    {
        $error = false;

        try {
            $response = Http::get($this->api_url . 'launches/' . $flightNumber);

            if ($response->successful()) {
                $data = $response->json();

                return view('launches.item', compact('error', 'data'));        
            } elseif ($response->status() == '404') {
                session()->flash('error', 'The requested launch was not found.');
            } else {
                session()->flash('error', 'Something went wrong');
            }
        } catch(Exception $ex) {
            session()->flash('error', 'There is some issue with the API. Please check back soon.');
        }

        return redirect()->route('launches');
    }

    /**
     * Generate pagination
     * @return object
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 0);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
