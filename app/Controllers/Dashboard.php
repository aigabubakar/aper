<?php namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        // quick check: ensure logged in
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/check-email')->with('errors', ['Please login first.']);
        }

        return view('pages/dashboard', ['user' => session()->get()]);
    }
}
