<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LaunchController extends Controller
{
    public function index()
    {
        $error = false;
        $data = [];
        $page = request()->get('page', 0);
        $pageSize = request()->get('size', 20);

        $queryParams = [
            'sort' => 'launch_date_unix',
            'order' => 'desc'
        ];

        $response = Http::get('https://api.spacexdata.com/v3/launches', $queryParams);

        if ($response->successful()) {
            $data = $this->paginate($response->json(), $pageSize, $page)
                ->appends(['size' => $pageSize]);
        } else {
            $error = true;
        }

        return view('launches', compact('error', 'data'));
    }

    public function show($flightNumber)
    {
        $error = false;
        $response = Http::get('https://api.spacexdata.com/v3/launches/' . $flightNumber);

        if ($response->successful()) {
            $data = $response->json();

            // dd($data);
            return view('launch', compact('error', 'data'));        
        } elseif ($response->status() == '404') {
            session()->flash('error', 'The requested launch was not found.');
        } else {
            session()->flash('error', 'Something went wrong');
        }

        return redirect()->route('launches');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 0);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
