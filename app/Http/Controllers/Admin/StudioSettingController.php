<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioSetting;
use Illuminate\Http\Request;

class StudioSettingController extends Controller
{
    public function index()
    {
        $settings = StudioSetting::pluck('value', 'key');
        return view('admin.pengaturan', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            StudioSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan Studio berhasil diperbarui!');
    }
}