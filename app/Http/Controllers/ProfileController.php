<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use Validator;
use Auth;
use App\Weblog;
use App\Menulink;

class ProfileController extends Controller
{
    public function index()
    {
        $id = Auth::id();
        $account = Account::where('id', $id)->first();

        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.profile', [
            'name' => $account->name,
            'email' => $account->email,
            'availablemenu' => $availablemenu
        ]);
    }
    public function update(Request $request)
    {
        $id = Auth::id();
        $account = Account::where('id', $id);

        if ($account->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $data = $request->only('name', 'email');

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
        ]);

        if ($validator->fails() === true) {
            $errors = $validator->errors()->all();

            return response()->json([
                'status' => false,
                'message' => implode('<br>', $errors),
            ]);
        }

        $account->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' =>
            json_encode(
              [
                  'name' => $data['name'],
                  'email' => $data['email'],
              ]
            )
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }
    public function password(Request $request)
    {
        $id = Auth::id();
        $account = Account::where('id', $id);

        if ($account->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $data = $request->only('password', 'password_confirmation');
        $validator = Validator::make($data, [
          'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails() === true) {
            $errors = $validator->errors()->all();

            return response()->json([
                'status' => false,
                'message' => implode('<br>', $errors),
            ]);
        }

        $account->update([
            'password' => bcrypt($data['password']),
        ]);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' =>
            json_encode(
              [
                  'password' => bcrypt($data['password']),
              ]
            )
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }
}
