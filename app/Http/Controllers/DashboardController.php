<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('dashboard');
    }

   public function usersData(Request $request){
    $users = User::select(['id', 'name', 'email', 'created_at']);

    return DataTables::of($users)
        ->editColumn('created_at', function ($user) {
            return $user->created_at->format('d M Y, H:i');
        })
        ->addColumn('actions', function ($user) {
            return '<button class="btn btn-sm btn-primary edit-btn" data-id="'.$user->id.'">Edit</button>';
        })
        ->rawColumns(['actions'])
        ->make(true);
}


    // Update user data via AJAX
   public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'User updated successfully.'
        ]);
    }
}
