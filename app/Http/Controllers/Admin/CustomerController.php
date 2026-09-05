<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('q')) {
            $search = trim($request->q);
            $terms = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($sub) use ($search, $terms) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%");

                foreach ($terms as $term) {
                    $sub->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('whatsapp_number', 'like', "%{$term}%");
                }
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.data_pelanggan', compact('customers', 'totalCustomers'));
    }
}
