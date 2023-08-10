<?php

namespace App\Http\Controllers\admin\links;

use App\Constant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = null;
        $providers = $request->input('is_trusted');
        $query = User::query();

        if ($providers) {
            $query->where(['is_trusted' => 1, 'role_id' => Constant::Provider]);
        } else {
            $query->where(['is_trusted' => 0, 'role_id' => Constant::Provider]);
        }

        $providers = $query->paginate(10);

        return view('admin.useful-links.trust.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
