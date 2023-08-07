<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function registerview(Request $request)
    {
        return view('registerview');
    }

    function getPartial(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'partial' => 'required'
        ]);

        if (method_exists($this, $method = $request->input('partial'))) {
            // $method = 'customerData'
            return app()->call([$this, $method]);
            //
        } else {
            return response()->json(['error' => 'invalid patial provided!'], 403);
        }
    }

    function customerData(Request $request)
    {
        // dd(1, $request->input('filter'));
        $options = collect($request->input('filters', [
            'orderBy' => 'created_at',
            'sortBy' => 'asc',
        ]));

        $users = User::query()->orderBy($options->get('orderBy', 'created_at'), $options->get('sortBy'))->get();

        return view('customerData', ['users' => $users]);
    }

    public function addcust(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',

        ]);

        $users = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return true;
    }

    public function referupdate(Request $request)
    {
        $users = User::find($request->input('userid'));
        $users->referencing()->updateOrCreate(['user_id' => $request->input('userid')], ['reference_id' => $request->input('referVal')]);
        return redirect()->back()->with('status', 'Name Updated Successfully');
    }



    public function showchaining()
    {

        $chains = User::all();

        foreach ($chains as $chain) {
            $first = $chain->name;
            $tmp = $chain;
            $i = 1;
            $children = [];
            while ($tmp->referencing) {
                $abc = $tmp->referencing->chainreference;
                $children[] = $abc->name;
                $tmp = $abc;
                $i++;
            }
            $input[] = ['first' => $first, 'children' =>  $children, 'count' =>  $i];
        }

        session()->put('reference', $input);

        return view('showchaining', [
            'chains' => $input,
        ]);
    }



    public function split(Request $request)
    {
        $amount =  $request->input('amount');
        $inputs = session()->get('reference');

        return view('split', [
            'chains' => $inputs,
            'amount' => $amount,
        ]);
    }

    // public function show()
    // {
    //     if (session()->has('input')) {
    //         $outputs = session()->get('input');
    //         dd($outputs);
    //         return view('split', compact('outputs'));
    //     }
    // }

    public function abcd()
    {
        'abcd';
    }

    public function bcd()
    {
        'abcd';
    }
}
