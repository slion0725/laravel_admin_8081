<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datatable;
use Illuminate\Support\Facades\DB;
use App\Account;
use Validator;
use Auth;
use App\Weblog;
use App\Menulink;
use App\Setting;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $table = [
            ['class' => 'all', 'text' => 'ID'],
            ['class' => 'all', 'text' => 'Name'],
            ['class' => 'desktop', 'text' => 'Email'],
            ['class' => 'desktop', 'text' => 'Created At'],
            ['class' => 'desktop', 'text' => 'Updated At'],
        ];
        $search = [
            ['text' => 'ID', 'db' => 'id'],
            ['text' => 'Name', 'db' => 'name'],
            ['text' => 'Email', 'db' => 'email'],
            ['text' => 'Created At', 'db' => 'created_at'],
            ['text' => 'Updated At', 'db' => 'updated_at'],
        ];
        $view = [
            ['text' => 'ID', 'class' => 'id'],
            ['text' => 'Name', 'class' => 'name'],
            ['text' => 'Email', 'class' => 'email'],
            ['text' => 'Created At', 'class' => 'created_at'],
            ['text' => 'Updated At', 'class' => 'updated_at'],
        ];

        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.accounts',[
            'table' => $table,
            'search' => $search,
            'view' => $view,
            'availablemenu' => $availablemenu
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name', 'email', 'password');

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails() === true) {
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        Account::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Weblog::create(['user_id'=>Auth::id(), 'level'=>'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data'=>
            json_encode(
              [
                  'name' => $data['name'],
                  'email' => $data['email'],
                  'password' => bcrypt($data['password']),
              ]
            )
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = Account::where('id', $id);

        if ($account->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $account->first(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::where('id', $id);

        if ($account->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $account->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        $account->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Weblog::create(['user_id'=>Auth::id(), 'level'=>'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data'=>
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

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $account = Account::where('id', $id);
        
        if (Setting::where('name','MasterAccountID')->where('data', $id)->count() == 1){
            return response()->json([
                'status' => false,
                'message' => 'N/A',
            ]);
        }

        if ($account->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $account->delete();

        Weblog::create(['user_id'=>Auth::id(), 'level'=>'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data'=>
            json_encode(
                [
                    'id' => $id,
                ]
            )
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }

    public function datatable(Request $request)
    {
        $table = 'users';

        $primaryKey = 'id';

        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
        ];

        $whereResult = null;
        $whereAll = null;

        $dt = new Datatable();
        $query = $dt->complex($request, $table, $primaryKey, $columns, $whereResult, $whereAll);

        // echo $rs['draw'];
        // echo $rs['recordsTotal'];
        // echo $rs['recordsFiltered'];
        // echo $rs['data'];

        $recordsTotal = DB::select($query['recordsTotal']);
        $recordsFiltered = DB::select($query['recordsFiltered']);
        $data = DB::select($query['data']);

        return response()->json([
            'draw' => $query['draw'],
            'recordsTotal' => intval($recordsTotal[0]->count),
            'recordsFiltered' => intval($recordsFiltered[0]->count),
            'data' => $dt->data_output($columns, $data),
            'query' => $query,
        ]);
    }
}
