<?php

namespace App\Http\Controllers;

use App\Models\ReferralLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReferralLinkController extends Controller
{
    public function publicIndex(string $type): View
    {
        abort_unless(in_array($type, ['forex', 'saham'], true), 404);

        return view('referral-links.public-index', [
            'type' => $type,
            'referralLinks' => ReferralLink::query()
                ->where('type', $type)
                ->where('status', 'aktif')
                ->latest()
                ->get(),
        ]);
    }

    public function index(): View
    {
        return view('referral-links.index', [
            'referralLinks' => ReferralLink::query()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateReferralLink($request);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('referral-logos', 'public');
        }

        ReferralLink::create($data);

        return redirect()->route('referral-links.index')->with('status', 'Link referal berhasil ditambahkan.');
    }

    public function update(Request $request, ReferralLink $referralLink): RedirectResponse
    {
        $data = $this->validateReferralLink($request);

        if ($request->hasFile('logo')) {
            $this->deleteStoredFile($referralLink->logo_path);
            $data['logo_path'] = $request->file('logo')->store('referral-logos', 'public');
        }

        $referralLink->update($data);

        return redirect()->route('referral-links.index')->with('status', 'Link referal berhasil diperbarui.');
    }

    public function destroy(ReferralLink $referralLink): RedirectResponse
    {
        $this->deleteStoredFile($referralLink->logo_path);
        $referralLink->delete();

        return redirect()->route('referral-links.index')->with('status', 'Link referal berhasil dihapus.');
    }

    private function validateReferralLink(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:forex,saham'],
            'link' => ['required', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'name.required' => 'Nama referal wajib diisi.',
            'type.required' => 'Jenis referal wajib dipilih.',
            'type.in' => 'Jenis referal harus Forex atau Saham.',
            'link.required' => 'Link referal wajib diisi.',
            'link.url' => 'Link referal harus berupa URL yang valid.',
            'logo.image' => 'Logo referal harus berupa gambar.',
            'logo.max' => 'Logo referal maksimal 4 MB.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus aktif atau non aktif.',
        ]);
    }

    private function deleteStoredFile(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
