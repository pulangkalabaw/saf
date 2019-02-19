<?php

namespace App\Http\Controllers;

use Validator;
use App\Product;
use App\Application;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Model
        $product = new Product();

        // Sorting
        // params: sort_in & sort_by
        if (!empty($request->get('sort_in') && !empty($request->get('sort_by')))) $product = $product->sort($request);

        // Search
        if (!empty($request->get('search_string'))) $product = $product->search($request->get('search_string'));

        // Count all before paginate
        $total = $product->count();

        // Insert pagination
        $product = $product->paginate((!empty($request->show) ? $request->show : 10));
        return view('app.product.index', ['product' => $product, 'product_total' => $total]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('app.product.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        // Once validated
        $request['product_id'] = rand(111, 99999);
        if (Product::create($request->except('_token'))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Added successful!',
            ]); 
        }

        else { 
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'Failed to add',
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = Product::where('product_id', $id)->firstOrFail();
        return view('app.product.edit', ['product' => $product]);
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
        //
        $product = Product::where('product_id', $id)->firstOrFail();
        
        $request->validate([
            'product_name' => 'required|string|max:255',
        ]);

        // Once validated
        if ($product->update($request->only('product_name'))) {
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Update successful!',
            ]); 
        }

        else { 
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => 'Failed to update',
            ]); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::where('product_id', $id)->firstOrFail();

        $application = Application::where('product_type', $id);
        if ($application->first()) {
            return back()->with([
                'notif.style' => 'danger',
                'notif.icon' => 'times-circle',
                'notif.message' => "Total of ". $application->count(). " application(s) that uses this product, you must update those application to another product before you remove",
            ]); 
        }
        else {
            $product->delete();
            return back()->with([
                'notif.style' => 'success',
                'notif.icon' => 'plus-circle',
                'notif.message' => 'Delete successful!',
            ]); 
        }
    }
}
