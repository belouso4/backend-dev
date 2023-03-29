<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function store(Request $request) {
//        return $request['to'];
        Mail::to('kirill.bielousov15151515@gmail.com')->send(new TestMail($request->all()));
    }
}
