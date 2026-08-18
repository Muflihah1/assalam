<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 1. Model didefinisikan di sini
class StudioSetting extends Model
{
    use HasFactory;

    protected $table = 'studio_settings';
    protected $fillable = ['key', 'value'];
}

// 2. Controller didefinisikan di bawahnya dalam satu file yang sama
class StudioSettingController extends Controller
{
    public function index()
    {
        // Ambil data pengaturan dari database
        $settings = StudioSetting::pluck('value', 'key');
        return view('admin.studio-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        // Simpan atau update pengaturan yang diubah admin
        foreach ($request->except('_token') as $key => $value) {
            StudioSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan Studio berhasil diperbarui!');
    }
}