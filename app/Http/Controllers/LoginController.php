<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	public function login()
	{
		return view("pages.auth.login");
	}

	public function authenticate(Request $request)
	{
		$request->validate([
			"email" => "required|email",
			"password" => "required",
		]);

		$credentials = $request->only("email", "password");

		if (Auth::attempt($credentials)) {
			return redirect()
				->route("dashboard.index")
				->with([
					"success" => "Login successful!",
				]);
		}

		return redirect()
			->back()
			->with([
				"error" => "Invalid email or password.",
			]);
	}

	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect()
			->route("login")
			->with([
				"success" => "Berhasil keluar dari aplikasi!",
			]);
	}
}
