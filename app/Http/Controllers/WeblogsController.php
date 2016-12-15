<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Datatable;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Weblog;
use App\Menulink;

class WeblogsController extends Controller
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
            ['class' => 'all', 'text' => 'User ID'],
            ['class' => 'all', 'text' => 'Name'],
            ['class' => 'desktop', 'text' => 'Level'],
            ['class' => 'desktop', 'text' => 'Method'],
            ['class' => 'desktop', 'text' => 'Created At'],
        ];
        $search = [
            ['text' => 'ID', 'db' => 'id'],
            ['text' => 'User ID', 'db' => 'user_id'],
            ['text' => 'Name', 'db' => 'name'],
            ['text' => 'Level', 'db' => 'level'],
            ['text' => 'Method', 'db' => 'method'],
            ['text' => 'Created At', 'db' => 'created_at'],
        ];
        $view = [
            ['text' => 'ID', 'class' => 'id'],
            ['text' => 'User ID', 'class' => 'user_id'],
            ['text' => 'Name', 'class' => 'name'],
            ['text' => 'Level', 'class' => 'level'],
            ['text' => 'Method', 'class' => 'method'],
            ['text' => 'Created At', 'class' => 'created_at'],
        ];

        $menulink = Menulink::distinct()->select('menu_id')->where('user_id',Auth::id())->with('menuitem')->get();
        $availablemenu = [];
        foreach($menulink as $d){
          $availablemenu[$d['menuitem']['url']] = $d['menuitem']['name'];
        }

        return view('admin.features.weblogs',[
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
        //
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
        $r = Weblog::where('id', $id)->with('account');
        if ($r->count() !== 1) {
            return response()->json([
                'status' => false,
                'message' => 'Not found',
            ]);
        }

        $a = $r->first();

        $d['id'] = $a->id;
        $d['user_id'] = $a->user_id;
        $d['name'] = isset($a->account->name) ? $a->account->name:null;
        $d['level'] = $a->level;
        $d['method'] = $a->method;
        $d['require_data'] = nl2br(htmlspecialchars(urldecode(http_build_query(json_decode($a->require_data),null,"\n")),ENT_IGNORE));
        $d['created_at'] = $a->created_at->format('Y-m-d H:i:s');

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
        //
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
        //
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
        //
    }
    public function datatable(Request $request)
    {
        $table = 'weblogs AS a left join users AS b ON a.user_id = b.id';

        $primaryKey = 'a.id';

        $columns = [
            ['db' => 'a.id', 'dt' => 'id'],
            ['db' => 'a.user_id', 'dt' => 'user_id'],
            ['db' => 'b.name', 'dt' => 'name'],
            ['db' => 'a.level', 'dt' => 'level'],
            ['db' => 'a.method', 'dt' => 'method'],
            ['db' => 'a.created_at', 'dt' => 'created_at'],
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
