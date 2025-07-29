<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        // Ambil semua email yang sudah dapat promo (ada promo_code)
        $contacts = Contact::whereNotNull('promo_code')->get();
        return view('membership.index', compact('contacts'));
    }
}
