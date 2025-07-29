<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|max:25',
            'message' => 'required|max:2000',
        ]);

        // Kirim ke email admin penerima (dari email user, reply-to user)
        Mail::to('redeemself0@gmail.com')->send(new ContactFormMail(
            $validated['name'],
            $validated['email'],
            $validated['phone'],
            $validated['message']
        ));

        // Redirect dengan flash message sukses
        return back()->with('success', 'Pesan Anda telah dikirim ke admin. Terima kasih!');
    }
}