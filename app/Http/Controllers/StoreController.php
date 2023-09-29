<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    //

    public function __construct(){
        $this->middleware("auth:api");
    }

    public function index(){

            $sort = \Request::get("sort");
            $list_num = \Request::get("list_num");
            $search = \Request::get("search");

            $store = Store::orderBy("id",$sort)
            ->where("name","LIKE","%{$search}%")
            ->paginate($list_num)
            ->toArray();

            return array_reverse($store);

            // $store = Store::orderBy("id",$sort)
            // ->get();

            // return $store;
    }

    public function add(Request $request){
        try{

            // ປະກາດເສັ້ນທາງ ບັນທຶກຮູບພາບ
            $upload_path = "assets/img";

            if($request->file("image")){

                $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                $request->image->move(public_path($upload_path),$generate_new_name);

            } else {
                $generate_new_name = "";
            }

            $store = new Store([
                "name" => $request->name,
                "image" => $generate_new_name,
                "amount" => $request->amount,
                "price_buy" => $request->price_buy,
                "price_sell" => $request->price_sell,
            ]);

            $store->save();


            $success = true;
            $message = "ບັນທຶກຂໍ້ມູນສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex){
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message
        ];

        return response()->json($response);
    }

    public function edit($id){
        $store = Store::find($id);
        $store = Store::Where("id",$id)->get()[0];
        return $store;
    }

    public function update($id,Request $request){

        try{

            $store = Store::find($id);
             // ປະກາດເສັ້ນທາງ ບັນທຶກຮູບພາບ
             $upload_path = "assets/img";

             // ກວດຊອບຮູບພາບທີ່ສົ່ງມາ
             if($request->file("image")){

                // ກວດຊອບຮູບເກົ່າ ຖ້າມີໃຫ້ທຳການລຶບ
                if($store->image){
                    if(file_exists($upload_path."/".$store->image)){
                        unlink($upload_path."/".$store->image);
                    }
                }

                // ອັບໂຫຼດຮູບໃໝ່ແທນ
                 $generate_new_name = time().".".$request->image->getClientOriginalExtension();
                 $request->image->move(public_path($upload_path),$generate_new_name);
                
                 $store->update([
                    "name" => $request->name,
                    "image" => $generate_new_name,
                    "amount" => $request->amount,
                    "price_buy" => $request->price_buy,
                    "price_sell" => $request->price_sell
                ]);

             } else {
                 $generate_new_name = "";
             }

            
            



            $success = true;
            $message = "ອັບເດດຂໍ້ມູນສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex){
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message
        ];

        return response()->json($response);

    }

    public function delete($id){
        try{

            $store = Store::find($id);
            $store->delete();

            $success = true;
            $message = "ລຶບຂໍ້ມູນສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex){
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            "success" => $success,
            "message" => $message
        ];

        return response()->json($response);
    }
}
