<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use App\Mail\TestowyMail;

class PagesController extends Controller
{







    public function sendmail()
    {
        Mail::to('ryszardzielinski49@o2.pl')->queue(new TestowyMail());
    }
}
