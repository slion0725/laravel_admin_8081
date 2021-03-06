<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datatable;
use Illuminate\Support\Facades\DB;
use App\Menuitem;
use Validator;
use Auth;
use App\Weblog;
use App\Menulink;

class MenuitemsController extends Controller
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
            ['class' => 'all', 'text' => 'Url'],
            ['class' => 'desktop', 'text' => 'Description'],
            ['class' => 'desktop', 'text' => 'Created At'],
            ['class' => 'desktop', 'text' => 'Updated At'],
        ];
        $search = [
            ['text' => 'ID', 'db' => 'id'],
            ['text' => 'Name', 'db' => 'name'],
            ['text' => 'Url', 'db' => 'url'],
            ['text' => 'Description', 'db' => 'description'],
            ['text' => 'Created At', 'db' => 'created_at'],
            ['text' => 'Updated At', 'db' => 'updated_at'],
        ];
        $view = [
            ['text' => 'ID', 'db' => 'id'],
            ['text' => 'Name', 'db' => 'name'],
            ['text' => 'Url', 'db' => 'url'],
            ['text' => 'Description', 'db' => 'description'],
            ['text' => 'Created At', 'db' => 'created_at'],
            ['text' => 'Updated At', 'db' => 'updated_at'],
        ];

        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.menuitems', [
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
        $data = $request->only('name', 'url', 'description');

        $validator = Validator::make($data, [
            'name' => 'required|max:100|unique:menuitems,name',
            'url' => 'required|max:100|unique:menuitems,url',
            'description' => 'required|max:100',
        ]);

        if ($validator->fails() === true) {
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        Menuitem::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'description' => $data['description'],
        ]);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' => json_encode(
                [
                    'name' => $data['name'],
                    'url' => $data['url'],
                    'description' => $data['description'],
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $r = Menuitem::where('id', $id);
        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $a = $r->first();

        $d['id'] = $a->id;
        $d['name'] = $a->name;
        $d['url'] = $a->url;
        $d['description'] = $a->description;
        $d['created_at'] = $a->created_at->format('Y-m-d H:i:s');
        $d['updated_at'] = $a->updated_at->format('Y-m-d H:i:s');

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $d,
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
        $r = Menuitem::where('id', $id);
        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $a = $r->first();

        $d['id'] = $a->id;
        $d['name'] = $a->name;
        $d['url'] = $a->url;
        $d['description'] = $a->description;

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $d,
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
        $r = Menuitem::where('id', $id);

        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $data = $request->only('name', 'url', 'description');

        $validator = Validator::make($data, [
            'name' => 'required|max:100|unique:menuitems,name,'.$id,
            'url' => 'required|max:100|unique:menuitems,url,'.$id,
            'description' => 'required|max:100',
        ]);

        if ($validator->fails() === true) {
            return response()->json([
                'status' => false,
                'message' => implode('<br>', $validator->errors()->all()),
            ]);
        }

        $r->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'description' => $data['description'],
        ]);

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' => json_encode(
                [
                    'name' => $data['name'],
                    'url' => $data['url'],
                    'description' => $data['description'],
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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $r = Menuitem::where('id', $id);

        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $r->delete();

        Weblog::create(['user_id' => Auth::id(), 'level' => 'Info', 'method' => substr(strrchr(__METHOD__,'\\'),1), 'require_data' => json_encode(
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
        $table = 'menuitems';

        $primaryKey = 'id';

        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'url', 'dt' => 'url'],
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
