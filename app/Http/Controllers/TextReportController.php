<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextReportController extends Controller
{
    /**
     * Display the text report page.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function showTextReport(Request $request)
    {
        return view('pages.textreport');
    }
}
