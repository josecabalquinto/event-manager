<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
class NewPasswordController extends Controller {
    public function create(Request $request) {
        return Inertia::render('Auth/ResetPassword', ['token' => $request->route('token')]);
    }
    public function store(Request $request) {
        return redirect()->route('login');
    }
}
