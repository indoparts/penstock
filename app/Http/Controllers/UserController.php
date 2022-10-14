<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::latest()->simplePaginate(5);
        if (request()->has('cari')) {
            $data = User::where('name', 'like', request()->cari)->simplePaginate(5);
        }
        return view('users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|unique:users|max:100',
            'password' => 'required|min:6',
        ]);
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            User::create($input);
            return redirect('users/create')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect('users/create')->with('error', 'Data gagal ditambahkan, error ' . $th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = User::find($id);
        return view('users.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|max:100|unique:users,email,' . $id,
            'password' => 'required|min:6',
        ]);
        try {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            User::find($id)->update($input);
            return redirect('users/' . $id . '/edit')->with('success', 'Data berhasil diperbaharui');
        } catch (\Throwable $th) {
            return redirect('users/create')->with('error', 'Data gagal diperbaharui, error ' . $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            User::find($id)->delete();
            return redirect('users')->with('success', 'Data berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect('users')->with('error', 'Data gagal dihapus, error ' . $th);
        }
    }
}
