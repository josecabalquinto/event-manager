<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail,
            'status' => session('status'),
            'user' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'student_id' => $request->user()->student_id,
                'course' => $request->user()->course,
                'year_level' => $request->user()->year_level,
                'section' => $request->user()->section,
            ],
            'courses' => \App\Models\User::getAvailableCourses(),
            'year_levels' => \App\Models\User::getAvailableYearLevels(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'student_id' => 'nullable|string|regex:/^\d{2}-\d{4}$/|unique:users,student_id,' . $request->user()->id,
            'course' => 'nullable|in:BSIT,BSIS,BLIS,BSEMC',
            'year_level' => 'nullable|in:1st Year,2nd Year,3rd Year,4th Year',
            'section' => 'nullable|string|max:10',
        ]);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
