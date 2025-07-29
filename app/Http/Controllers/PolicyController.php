<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\PolicyAcceptance;
use Carbon\Carbon;

class PolicyController extends Controller
{
    /**
     * Tampilkan halaman notice (pemberitahuan kebijakan).
     */
    public function notice()
    {
        return view('policies.notice');
    }

    /**
     * Tampilkan form untuk menerima kebijakan.
     */
    public function showAcceptForm()
    {
        return view('policies.accept'); // buat view policies/accept.blade.php
    }

    /**
     * Proses penyimpanan acceptance (DB atau cookie).
     */
    public function accept(Request $request)
    {
        $policy = $request->input('policy_name', 'privacy_policy');

        if ($user = $request->user()) {
            // Simpan atau perbarui di DB
            $user->policyAcceptances()->updateOrCreate(
                ['policy_name' => $policy],
                ['accepted_at'  => Carbon::now()]
            );
        } else {
            // Simpan cookie 1 tahun (nilai '1' agar middleware mengenali)
            Cookie::queue('policy_accepted', '1', 60 * 24 * 365);
        }

        return redirect()->route('home')
                         ->with('success', 'Terima kasih, kebijakan telah diterima.');
    }

    /**
     * Reset acceptance kebijakan (hapus DB record & cookie).
     */
    public function resetPolicyAcceptance(Request $request)
    {
        if ($user = $request->user()) {
            $user->policyAcceptances()
                 ->where('policy_name', 'privacy_policy')
                 ->delete();
        }

        Cookie::queue(Cookie::forget('policy_accepted'));

        return redirect()->route('home')
                         ->with('success', 'Penerimaan kebijakan telah direset.');
    }
}
