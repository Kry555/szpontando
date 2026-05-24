<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\EmailChangeNewMail;
use App\Mail\EmailChangeOldMail;
class EmailChangeController extends Controller

{
    // STEP 1
    public function requestChange(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Złe hasło'
            ]);
        }

        $token = Str::random(64);

        DB::table('email_change_requests')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'old_email' => $user->email,
                'old_email_token' => Hash::make($token),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $link = url('/change-email/verify-old?token=' . $token . '&email=' . $user->email);

Mail::to($user->email)->send(new EmailChangeOldMail($link));

return back()->with('status', 'Email wysłany na stary adres.');
    }

    // STEP 2
    public function verifyOldEmail(Request $request)
    {
        $record = DB::table('email_change_requests')
            ->where('old_email', $request->email)
            ->first();

        if (!$record) {
            return "Nieprawidłowy link";
        }

        if (!Hash::check($request->token, $record->old_email_token)) {
            return "Nieprawidłowy token";
        }

        DB::table('email_change_requests')
            ->where('id', $record->id)
            ->update([
                'old_email_verified_at' => now()
            ]);

        return view('change-email-new', [
            'request_id' => $record->id
        ]);
    }

    // STEP 3
    public function sendNewEmailVerification(Request $request)
    {
        $request->validate([
            'request_id' => 'required',
            'new_email' => 'required|email|unique:users,email'
        ]);

        $record = DB::table('email_change_requests')
            ->where('id', $request->request_id)
            ->first();

        if (!$record || !$record->old_email_verified_at) {
            return "Brak autoryzacji";
        }

        $token = Str::random(64);

        DB::table('email_change_requests')
            ->where('id', $record->id)
            ->update([
                'new_email' => $request->new_email,
                'new_email_token' => Hash::make($token)
            ]);

        $link = url('/change-email/confirm-new?token=' . $token . '&request_id=' . $record->id);

        Mail::to($request->new_email)->send(new EmailChangeNewMail($link));
        return back()->with('status', 'Email potwierdzający został wysłany na nowy email!');
        }

    // STEP 4
    public function confirmNewEmail(Request $request)
    {
        $record = DB::table('email_change_requests')
            ->where('id', $request->request_id)
            ->first();

        if (!$record) {
            return "Nie znaleziono";
        }

        if (!Hash::check($request->token, $record->new_email_token)) {
            return "Nieprawidłowy token";
        }

        DB::table('users')
            ->where('id', $record->user_id)
            ->update([
                'email' => $record->new_email
            ]);

        DB::table('email_change_requests')
            ->where('id', $record->id)
            ->delete();

        return redirect('/profil')
            ->with('status', 'Email zmieniony!');
    }
}