<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionBankCategory;

class BankSoalController extends Controller
{
    public function index()
    {
        $categories = QuestionBankCategory::with('tryouts')->get();

        return view('bank-soal.bank-soal-page', [
            'categories' => $categories
        ]);
    }
}
