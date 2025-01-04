<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;

class BatchController extends Controller
{
    public function showall()
    {
        $data = Batch::all();

        return response()->json($data);
    }

    public function showbyid($id)
    {
        $databyid = Batch::find($id);

        if (!$databyid) {
            return response()->json(['message' => 'Batch not found'], 404);
        }

        return response()->json($databyid);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
          
            'department_name' => 'required|string|max:100',
            'batch_name' => 'required|string|max:255',
            'batch_short_date' => 'required|date',
            'batch_end_date' => 'required|date',
            'batch_status' => 'required|string|max:255',
        ]);

        $batch = Batch::create($data);

        return response()->json($batch, 201);
    }
     // Update an existing batch
     public function update(Request $request, $id)
     {
         $batch = Batch::find($id);
 
         if (!$batch) {
             return response()->json(['message' => 'Batch not found'], 404);
         }
 
         $data = $request->validate([
            
             'department_name' => 'required|string|max:100',
             'batch_name' => 'required|string|max:255',
             'batch_short_date' => 'required|date',
             'batch_end_date' => 'required|date',
             'batch_status' => 'required|string|max:255',
         ]);
 
         $batch->update($data);
 
         return response()->json($batch);
     }
 
     // Delete a batch
     public function destroy($id)
     {
         $batch = Batch::find($id);
 
         if (!$batch) {
             return response()->json(['message' => 'Batch not found'], 404);
         }
 
         $batch->delete();
 
         return response()->json(['message' => 'Batch deleted successfully']);
     }
 }

