<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expensetypes = ExpenseType::all();
        
        return response()->json([
            'expensetypes' => $expensetypes
        ]);
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
       $expenseTypeName=  $request->expenseType;

       $expenseType = new ExpenseType();
       $expenseType->expenseTypeName = $expenseTypeName;
       $expenseType->save();

       return response()->json([
            'Message' => "Expense Type Added Successfully.."
       ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExpenseType $expenseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExpenseType $expenseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExpenseType $expenseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExpenseType $expenseType)
    {
        //
    }
}
