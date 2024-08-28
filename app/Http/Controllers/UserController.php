<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$roles = DB::table("roles")->get();

		if (Auth::user()->hasRole('admin puskesmas')) {
			$users = User::where("name", "!=", "admin")
				->where("name", "!=", "admin puskesmas")
				->get();

			return view("pages.user-index", compact("users", "roles"));
		}

		// $users = User::all();
		// $users = User::where("name", "NOT LIKE", "%admin%")->get();
		$users = User::where("name", "!=", "admin")->get();

		return view("pages.user-index", compact("users", "roles"));
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$validation = $request->validate([
			"name" => ["required", "string", "min:1"],
			"email" => [
				"required",
				"email",
				"string",
				"min:1",
				"unique:users,email",
			],
			"password" => ["required", "string", "min:8"],
		]);

		$validation["password"] = Hash::make($validation["password"]);
		$validation["created_at"] = now();
		$validation["updated_at"] = now();

		$insertion = User::create($validation);

		if (!$insertion) {
			return redirect()
				->route("user.index")
				->with([
					"error" => "Gagal menambah data pengguna!",
				]);
		}

		$insertion->assignRole($request->role);

		return redirect()
			->route("user.index")
			->with([
				"success" => "Berhasil menambah data pengguna!",
			]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$user = User::find($id);
		$roles = DB::table("roles")->get();

		return view("pages.user-edit", compact("user", "roles"));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$user = User::find($id);

		$rules = [
			"name" => ["required", "string", "min:1"],
			"email" => [
				"required",
				"email",
				"string",
				"min:1",
			],
		];

		if ($user->email != $request->email) {
			$rules["email"] = [
				"unique:users,email",
			];
		}

		$validation = $request->validate($rules);

		$userCurrentRole = $user->roles[0]->name;

		$user->removeRole($userCurrentRole);
		$user->assignRole($request->role);

		$update = $user->update($validation);

		if (!$update) {
			return redirect()
				->route("user.edit", $id)
				->with([
					"error" => "Gagal mengubah data pengguna!",
				]);
		}

		return redirect()
			->route("user.edit", $id)
			->with([
				"success" => "Berhasil mengubah data pengguna!",
			]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$deletion = User::find($id)->delete();

		if (!$deletion) {
			return redirect()
				->route("user.index")
				->with([
					"error" => "Gagal menghapus data pengguna!",
				]);
		}

		return redirect()
			->route("user.index")
			->with([
				"success" => "Berhasil menghapus data pengguna!",
			]);
	}
}
