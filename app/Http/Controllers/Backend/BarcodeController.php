<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

class BarcodeController extends BaseController
{
    public function index(){
        return view('backend.barcode.index');
    }

}
