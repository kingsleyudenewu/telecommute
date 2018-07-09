<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product.index');
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
        $this->validate($request, [
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ]);

        $inputData = $request->only(['name', 'qty', 'price']);
        $inputData['created_at'] = date('Y-m-d H:i:s');

        $file = date('Y-m-d: H-i-s').".json";
        $json_string = json_encode($inputData, JSON_PRETTY_PRINT);
        file_put_contents($file, $json_string, FILE_APPEND);

        $create_product = Product::create([ 'name'=>$request->name, 'qty'=>$request->qty, 'price'=>$request->price]);
        if($create_product){
            return response()->json('Operation successful');
        }
        else return response()->json('Operation failed, tyr again');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'required',
            'qty' => 'required',
            'price' => 'required'
        ]);

        $result = Product::where('id', $product->id)->update([
            'name'=> $request->name,
            'qty'=>$request->qty,
            'price'=>$request->price
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $delete =  Product::find ( $product->id )->delete ();
        if($delete){
            return response ()->json ('Operation successful');
        }
        else return response ()->json ('Operation failed, please try again');
    }

    public function getAllProduct(){
        $company = Product::select('id', 'name', 'qty', 'price')->get();
        return Datatables::of($company)
            ->addColumn('amount', function ($product){
                return $product->qty * $product->price;
            })
            ->addColumn('action', function ($product) {
                    return '<a data-edit-prod="'.$product->id.'" class="btn btn-xs btn-primary edit_prod"><i class="glyphicon glyphicon-edit"></i> Edit</a>'.'<a  data-delete-prod="'.$product->id.'"  class="btn btn-xs btn-danger del_prod"><i class="glyphicon glyphicon-edit"></i> Delete</a>';
            })
            ->make(true);
    }
}
