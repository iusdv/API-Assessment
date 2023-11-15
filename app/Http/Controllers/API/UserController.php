<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

// class UserController extends Controller
// {
//     public function index()
//     {
//         $users = User::all();

//         return response()->json(['data' => $users]);
//     }

//     public function show($id)
//     {
//         $user = User::findOrFail($id);

//         return response()->json(['data' => $user]);
//     }

//     public function store(Request $request)
//     {
//         $validatedData = $request->validate([
//             'name' => 'required|string',
//             'email' => 'required|email|unique:users',
//             'password' => 'required|string|min:6',
//         ]);

//         $user = User::create($validatedData);

//         return response()->json(['data' => $user], 201);
//     }

//     public function update(Request $request, $id)
//     {
//         $validatedData = $request->validate([
//             'name' => 'required|string',
//             'email' => 'required|email|unique:users,email,' . $id,
//             'password' => 'required|string|min:6',
//         ]);

//         $user = User::findOrFail($id);
//         $user->update($validatedData);

//         return response()->json(['data' => $user]);
//     }

//     public function destroy($id)
//     {
//         $user = User::findOrFail($id);
//         $user->delete();

//         return response()->json(['message' => 'User deleted successfully']);
//     }
// }