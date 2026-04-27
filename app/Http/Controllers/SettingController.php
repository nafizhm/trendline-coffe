<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit', [
            'setting' => $this->setting(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = $this->setting();

        $data = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'direct_wa_number' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'operational_hours' => ['nullable', 'string', 'max:255'],
            'reservation_info' => ['nullable', 'string'],
            'google_maps_embed' => ['nullable', 'string'],
            'owner_photo' => ['nullable', 'image', 'max:4096'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'forex_referral_link' => ['nullable', 'url', 'max:255'],
            'ihsg_stock_referral_link' => ['nullable', 'url', 'max:255'],
            'wa_group_link' => ['nullable', 'url', 'max:255'],
            'telegram_group_link' => ['nullable', 'url', 'max:255'],
            'instagram_link' => ['nullable', 'url', 'max:255'],
            'tiktok_link' => ['nullable', 'url', 'max:255'],
        ], [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'direct_wa_number.required' => 'No Direct WA wajib diisi.',
            'operational_hours.max' => 'Jam operasional maksimal 255 karakter.',
            'owner_photo.image' => 'Foto owner harus berupa gambar.',
            'owner_photo.max' => 'Foto owner maksimal 4 MB.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.max' => 'Logo maksimal 4 MB.',
            'forex_referral_link.url' => 'Link referal Forex harus berupa URL yang valid.',
            'ihsg_stock_referral_link.url' => 'Link referal Saham IHSG harus berupa URL yang valid.',
            'wa_group_link.url' => 'Link Group WA harus berupa URL yang valid.',
            'telegram_group_link.url' => 'Link Group Telegram harus berupa URL yang valid.',
            'instagram_link.url' => 'Link akun IG harus berupa URL yang valid.',
            'tiktok_link.url' => 'Link akun Tiktok harus berupa URL yang valid.',
        ]);

        if ($request->hasFile('owner_photo')) {
            $this->deleteStoredFile($setting->owner_photo_path);
            $data['owner_photo_path'] = $request->file('owner_photo')->store('settings', 'public');
        }

        if ($request->hasFile('logo')) {
            $this->deleteStoredFile($setting->logo_path);
            $data['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        unset($data['owner_photo'], $data['logo']);

        $setting->fill($data)->save();

        return redirect()
            ->route('settings.edit')
            ->with('status', 'Pengaturan berhasil disimpan.')
            ->with('status_alert', 'Pengaturan berhasil disimpan.');
    }

    public function showFile(string $field): StreamedResponse
    {
        $setting = $this->setting();
        $path = $setting->{$this->validatedFileField($field)};

        abort_if(blank($path), 404);

        try {
            return Storage::disk('public')->response($path);
        } catch (FileNotFoundException) {
            abort(404);
        }
    }

    public function destroyFile(string $field): RedirectResponse
    {
        $setting = $this->setting();
        $column = $this->validatedFileField($field);

        $this->deleteStoredFile($setting->{$column});
        $setting->forceFill([$column => null])->save();

        return redirect()
            ->route('settings.edit')
            ->with('status', 'File berhasil dihapus.');
    }

    private function setting(): AppSetting
    {
        return AppSetting::query()->firstOrCreate([]);
    }

    private function validatedFileField(string $field): string
    {
        return match ($field) {
            'owner-photo' => 'owner_photo_path',
            'logo' => 'logo_path',
            default => abort(404),
        };
    }

    private function deleteStoredFile(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
