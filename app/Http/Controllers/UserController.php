<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Models\User;


class UserController extends Controller
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileImportExport()
    {
       return view('file-import');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileImport(Request $request) 
    {   
        $data = array();
        $user = new User;
        $data['users'] = User::select('id','name','email')->get()->toArray();

        //  $data['users'] = DataTable::of($data['users'])->make(true);
       
    	if($request->file('file')!=null){
            $data['message'] = '';
    	    Excel::import(new UsersImport, $request->file('file')->store('temp'));
            return back();
    	}else{
    		$data['message'] = "File not set";
    		return view('file-import',$data);
    	}
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function fileExport() 
    {
    	$filename = "user_list_".date('Y-m-d_H_i_s').".xlsx";
        return Excel::download(new UsersExport, date('Y-m-d H:i:s').'users-collection.xlsx');
    }    
}