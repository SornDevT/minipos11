<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Transection;
use setasign\Fpdi\Fpdi;

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

           

        $filePath = public_path("Bill.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath);

        return array_reverse($store);
          
        // return response()->file($outputFilePath);

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
            $product_id = $store->id;

            // ບັນທຶກການເຄື່ອນໄຫວ ທຸລະກຳ 

            $number='';
                    $read_tran = Transection::all()->sortByDesc('id')->take(1)->toArray();
                    foreach($read_tran as $new){
                        $number = $new['tran_id'];
                    }

                    if($number!=''){
                        $number1 = str_replace("INC","",$number); // INC00001 = 00001
                        $number2 = str_replace("EXP","",$number1);
                        $number3 = (int)$number2+1; // 1+1 = 2
                        $length = 5;
                        $number = substr(str_repeat(0,$length).$number3, - $length); //00002
                    } else {
                        $number3 = 1;
                        $length = 5;
                        $number = substr(str_repeat(0,$length).$number3, - $length); //00001
                    }

                    $tran = new Transection([
                        "tran_id" => "EXP".$number, // INC00001
                        "tran_type" => "expense",
                        "product_id" => $product_id,
                        "amount" => $request->amount,
                        "price" => $request->amount*$request->price_buy,
                        "tran_detail" => $request->name,
                    ]);
                    $tran->save();



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

                
                if($request->image){ 
                    // ບໍ່ມີການເລືອກຮູບພາບ

                    $store->update([
                        "name" => $request->name,
                        // "image" => $generate_new_name,
                        "amount" => $request->amount,
                        "price_buy" => $request->price_buy,
                        "price_sell" => $request->price_sell
                    ]);
    

                } else {
                     // ລຶບຮູບພາບອອກແລ້ວ

                     // ກວດຊອບຮູບເກົ່າ ຖ້າມີໃຫ້ທຳການລຶບ
                    if($store->image){
                        if(file_exists($upload_path."/".$store->image)){
                            unlink($upload_path."/".$store->image);
                        }
                    }

                    $store->update([
                        "name" => $request->name,
                        "image" => "",
                        "amount" => $request->amount,
                        "price_buy" => $request->price_buy,
                        "price_sell" => $request->price_sell
                    ]);


                }
     
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

            $upload_path = "assets/img";
            $store = Store::find($id);
             // ກວດຊອບຮູບເກົ່າ ຖ້າມີໃຫ້ທຳການລຶບ
             if($store->image){
                if(file_exists($upload_path."/".$store->image)){
                    unlink($upload_path."/".$store->image);
                }
            }

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

    public function fillPDFFile($file, $outputFilePath)
    {
        $fpdi = new FPDI;
          
        $count = $fpdi->setSourceFile($file);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->SetFont("helvetica", "", 15);
            $fpdi->SetTextColor(153,0,153);
  
            $left = 10;
            $top = 10;
            $text = "itsolutionstuff.com";
            $fpdi->Text($left,$top,$text);
            if($i==$count){
                 $fpdi->Image(public_path("1695996225.png"), 10, 50);
            }
           
        }
  
        return $fpdi->Output($outputFilePath, 'F');
    }
}
