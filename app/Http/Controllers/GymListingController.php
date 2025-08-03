<?php

namespace App\Http\Controllers;
use App\Models\GymListing;

use Illuminate\Http\Request;

class GymListingController extends Controller
{
    public function index()
    {
        return view('gym-listings.index');
    }

    public function create()
    {
        return view('gym-listings.create');
    }

    public function edit($id)
    {
        return view('gym-listings.edit', ['id' => $id]);
    }

    public function show($id)
    {
        return view('gym-listings.show', ['id' => $id]);
    }

    public function sidebarPromotions()
    {
        return view('gym-listings.sidebar-promotions');
    }

    public function gymOffers()
    {
        return view('gym-listings.gym-offers');
    }

    public function gymGuides()
    {
        return view('gym-listings.gym-guides');
    }

}
