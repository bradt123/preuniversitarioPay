<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BecaRequest;
use App\Models\Beca;

class BecaController extends Controller
{
    public function view ()
	{
        return view('modules.Beca.view');
    }
    
    public function index () 
    {
        $tabla = Beca::select('id','Beca');

        return Datatables::of($tabla)
            ->addColumn('action', function ($p) {
                return '<a href="#" @click.prevent="showBeca('. $p->id . ')" class="btn btn-info btn-xs"><i class="fa fa-bars"></i> '. trans('labels.actions.details') .'</a> &nbsp;';
            })
        ->editColumn('id', '{{$id}}')
        ->make(true);
    }

    public function store (BecaRequest $request) 
    {
    	
        if($request->id) {
            $item = Beca::findOrFail($request->id);
            $msg = trans('messages.updated');
        } 
        else {
            $item = new Beca();
            $item->CreatorUserName = \Auth::user()->email;
            $item->CreatorFullUserName = \Auth::user()->Usuario;
            $item->CreatorIP = $request->ip();
            $msg = trans('messages.added');
        }
        
        $item->Beca = $request->Beca;
        $item->UpdaterUserName = \Auth::user()->email;
        $item->UpdaterFullUserName = \Auth::user()->Usuario;
        $item->UpdaterIP = $request->ip();
        $item->save();

        $result = array (
            'success' => true,
            'data' => $item,
            'msg' => $msg
        );

        return response()->json($result);
    }

    public function show (Request $request) {
        try {
            $item = Beca::findOrFail($request->id);
            $data = array(
                'success' => true,
                'data' => $item,
                'msg' => trans('messages.listed')
            );
        } catch(\Exception $e) {
            $data = array(
                'success' => false,
                'data' => null,
                'msg' => trans('mesagges.error')
            );
        } finally {
            return response()->json($data);
        }
    }  
 
    public function destroy(Request $request) {
        Beca::where('id', $request->id)->delete();
        $result = array (
            'success' => true,
            'data' => null,
            'msg' => trans('messages.deleted')
        );
        
        return response()->json($result);
    }

    public function list(Request $request) {

        $item = new Beca();
        $objeto = null;

        $objeto = $item->orderBy('Beca', 'asc')->get();
      
        $data = array(
            'success' => true,
            'data' => $objeto,
            'msg' => trans('messages.listed')
        );

        return response()->json($data);
    }
}
