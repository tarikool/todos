<?php

namespace App\Http\Controllers;

use App\ToDos;
use Illuminate\Http\Request;

class ToDosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = ToDos::all();

//        return count($items);

        return view('todos.create', compact('items'));
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
         return ToDos::create( $request->all());
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
//        return $request->all();
        $item = ToDos::find($id);
        if ( $request->operation == 'update')
            $item->update($request->all());

        if ( $request->operation == 'status')
            $item->status ? $item->update(['status' => 0]) : $item->update(['status' => 1]);

        if ($request->operation == 'delete')
            $item->delete();

        if ( $request->operation == 'clear')
            ToDos::where('status', 1)->delete();

        $data['item'] = $item;
        $data['operation'] = $request->operation;
        return $data;
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
    }
}
