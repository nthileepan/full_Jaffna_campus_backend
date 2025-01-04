<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\studentPaymentsDetail;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function search($id)
    {
        $student = DB::table('students')
            ->join('student_payments_details', 'student_payments_details.student_id', '=', 'students.id')
            ->where('zoho_no', $id)
            ->orWhere('nic_no', $id)
            ->select('students.*', 'student_payments_details.*')
            ->first();

        if ($student) {
            return response()->json([
                'studentId' => $student->student_id,
                'studentName' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name,
                'zohoNo' => $student->zoho_no,
                'department' => $student->department,
                'batch' => $student->batch,
                'totalPayment' => $student->full_amount,
                'paidPayment' => $student->total_paid,
                'arrearsPayment' => $student->arrears,
                'outstandingPayment' => $student->balance,
            ]);
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function payPayment(Request $request)
    {
        if ($request->has('formData')) {

            $formData = $request->input('formData');

            $validated = $request->validate([
                'formData.studentId' => 'required|integer',
                'formData.amount' => 'required|numeric|min:0',
            ]);

            $transaction = Transaction::create([
                'student_id' => $formData['studentId'],
                'payment_type_id' => 'MonthlyInstallment',
                'date' => now()->format('Y-m-d'),
                'amount' => $formData['amount'],
                'payment_by' => 'Cash',
                'status' => 'Completed',
            ]);

            $transaction->invoice_no = 'BPAY' . $transaction->id;
            $transaction->save();

            $studentPaymentDetails = studentPaymentsDetail::where('student_id', $transaction->student_id)->first();
            $studentPaymentDetails->last_payment_date = now()->format('Y-m-d');
            $studentPaymentDetails->last_paid_amount = $transaction->amount;
            $studentPaymentDetails->arrears = (float)$studentPaymentDetails->arrears - (float)$transaction->amount;
            $studentPaymentDetails->total_paid = (float)$studentPaymentDetails->total_paid + (float)$transaction->amount;
            $studentPaymentDetails->balance = (float)$studentPaymentDetails->balance - (float)$transaction->amount;
            $studentPaymentDetails->save();

            return response()->json(['success' => true, 'data' => $transaction], 201);
        } else {
            return response()->json(['error' => 'formData is missing'], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
