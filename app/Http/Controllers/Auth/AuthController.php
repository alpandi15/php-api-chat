<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\FriendResources;
use App\Models\User;
use App\Models\Contact;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if(!$token = auth()->attempt($request->only('username', 'password'))){
            return abort(401, 'maaf coba cek lagi username atau password anda');
        }

        return response()->json([
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'username' => 'required|unique:users,username',
            'password' => 'required'
        ]);

        DB::beginTransaction();
        try{
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);
            DB::commit();
            return response()->json([
                'message' => 'user berhasil di buat'
            ]);
        }catch(Exception $e){
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }

    public function logout()
    {
        auth()->user()->update([
            'updated_at' => Carbon::now()
        ]);
        auth()->logout();

        return response()->json(['message' => 'user berhasil logout']);
    }

    public function user()
    {
        return response()->json([
            'data' => auth()->user()
        ]);
    }

    public function all_user()
    {
        $users = User::get();

        return UserResource::collection($users);
    }

    public function get_all_user()
    {
        $user = auth()->user();
        $friends = User::with('friendd', 'mee')
        ->where('id', '!=', $user->id)
        ->get();

        return FriendResources::collection($friends);
        // return response(['data' => $friends], 200);
    }

}
