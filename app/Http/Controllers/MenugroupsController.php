<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Datatable;
use Illuminate\Support\Facades\DB;
use App\Menugroup;
use Validator;
use Auth;
use App\Weblog;
use App\Account;
use App\Menuitem;
use App\Menulink;
use App\Setting;

class MenugroupsController extends Controller
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
            ['class' => 'desktop', 'text' => 'Description'],
            ['class' => 'desktop', 'text' => 'Created At'],
            ['class' => 'desktop', 'text' => 'Updated At'],
        ];
        $search = [
            ['text' => 'ID', 'db' => 'id'],
            ['text' => 'Name', 'db' => 'name'],
            ['text' => 'Description', 'db' => 'description'],
            ['text' => 'Created At', 'db' => 'created_at'],
            ['text' => 'Updated At', 'db' => 'updated_at'],
        ];
        $view = [
            ['text' => 'ID', 'class' => 'id'],
            ['text' => 'Name', 'class' => 'name'],
            ['text' => 'Accounts', 'class' => 'accounts'],
            ['text' => 'Menuitems', 'class' => 'menuitems'],
            ['text' => 'Description', 'class' => 'description'],
            ['text' => 'Created At', 'class' => 'created_at'],
            ['text' => 'Updated At', 'class' => 'updated_at'],
        ];

        $accounts = Account::select('id','name')->orderBy('name','asc')->get();
        $menuitems = Menuitem::select('id','name')->orderBy('name','asc')->get();
        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.menugroups',[
            'table' => $table,
            'search' => $search,
            'view' => $view,
            'accounts' => $accounts,
            'menuitems' => $menuitems,
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name', 'description', 'accounts', 'menuitems');

        $validator = Validator::make($data, [
            'name' => 'required|max:100|unique:menugroups,name',
            'description' => 'required|max:100',
            'accounts' => 'required|array',
            'menuitems' => 'required|array'
        ]);

        if ($validator->fails() === true) {
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        $mg = new Menugroup;
        $mg->name = $data['name'];
        $mg->description = $data['description'];
        $mg->save();

        $ml = [];
        foreach($data['accounts'] as $a){
            foreach($data['menuitems'] as $b){
                $ml[] = new Menulink(['user_id' => $a,'menu_id' => $b]);
            }
        }
        $mg->menulink()->saveMany($ml);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' =>
            json_encode(
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'accounts' => $data['accounts'],
                    'menuitems' => $data['menuitems'],
                ]
            ),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mg = Menugroup::where('id', $id);

        $ml = $mg->with('menulink');
        if ($ml->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $a = $mg->first();

        $d['id'] = $a->id;
        $d['name'] = $a->name;
        $d['description'] = $a->description;
        $d['created_at'] = $a->created_at->format('Y-m-d H:i:s');
        $d['updated_at'] = $a->updated_at->format('Y-m-d H:i:s');

        $account = [];
        $menuitem = [];
        foreach($a['menulink'] as $ml){
            $account[] = $ml['user_id'];
            $menuitem[] = $ml['menu_id'];
        }
        $account = array_unique($account);
        $menuitem = array_unique($menuitem);

        $d['accounts'] = [];
        $accounts = Account::select('name')->whereIn('id', $account)->orderBy('name','asc')->get();
        foreach($accounts as $as){
          $d['accounts'][] = $as['name'];
        }
        $d['accounts'] = implode('<br>',$d['accounts']);

        $d['menuitems'] = [];
        $menuitems = Menuitem::select('name')->whereIn('id', $menuitem)->orderBy('name','asc')->get();
        foreach($menuitems as $ms){
          $d['menuitems'][] = $ms['name'];
        }
        $d['menuitems'] = implode('<br>',$d['menuitems']);

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $d,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $r = Menugroup::where('id', $id)->with('menulink');
        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $a = $r->first();

        $d['id'] = $a->id;
        $d['name'] = $a->name;
        $d['description'] = $a->description;

        $d['accounts'] = [];
        $d['menuitems'] = [];
        foreach($a->menulink as $m){
            $d['accounts[]'][] = $m['user_id'];
            $d['menuitems[]'][] = $m['menu_id'];
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $d,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $r = Menugroup::where('id',$id);

        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $data = $request->only('name', 'description', 'accounts', 'menuitems');

        $validator = Validator::make($data, [
            'name' => 'required|max:100|unique:menugroups,name,'.$id,
            'description' => 'required|max:100',
            'accounts' => 'required|array',
            'menuitems' => 'required|array'
        ]);

        if ($validator->fails() === true) {
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        Menulink::where('group_id',$id)->delete();

        $r->update([
          'name' => $data['name'],
          'description' => $data['description'],
        ]);

        $ml = [];
        foreach($data['accounts'] as $a){
            foreach($data['menuitems'] as $b){
                $ml[] = new Menulink(['user_id' => $a,'menu_id' => $b]);
            }
        }
        $r->first()->menulink()->saveMany($ml);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' =>
            json_encode(
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'accounts' => $data['accounts'],
                    'menuitems' => $data['menuitems'],
                ]
            ),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r = Menugroup::where('id', $id);

        if (Setting::where('name','MasterGroupID')->where('data', $id)->count() == 1){
            return response()->json([
                'status' => false,
                'message' => 'N/A',
            ]);
        }

        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $r->delete();

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' =>
            json_encode(
                [
                    'id' => $id,
                ]
            ),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Success',
        ]);
    }
    public function datatable(Request $request)
    {
        $table = 'menugroups';

        $primaryKey = 'id';

        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'description', 'dt' => 'description'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
        ];

        $whereResult = null;
        $whereAll = null;

        $dt = new Datatable();
        $query = $dt->complex($request, $table, $primaryKey, $columns, $whereResult, $whereAll);

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
