<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        // Mengambil semua data user yang rolenya 'customer' (baik baru maupun lama)
        $customers = User::where('role', 'customer')->latest()->get();

        // Tampilkan ke view admin
        return view('admin.customers.index', compact('customers'));
    }
}