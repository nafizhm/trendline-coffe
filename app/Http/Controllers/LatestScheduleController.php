<?php

namespace App\Http\Controllers;

use App\Models\LatestSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LatestScheduleController extends Controller
{
    public function index(): View
    {
        return view('latest-schedules.index', [
            'latestSchedules' => LatestSchedule::query()->latest('event_date')->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateLatestSchedule($request);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('latest-schedules', 'public');
        }

        LatestSchedule::create($data);

        return redirect()->route('latest-schedules.index')->with('status', 'Jadwal terbaru berhasil ditambahkan.');
    }

    public function update(Request $request, LatestSchedule $latestSchedule): RedirectResponse
    {
        $data = $this->validateLatestSchedule($request);

        if ($request->hasFile('attachment')) {
            $this->deleteStoredFile($latestSchedule->attachment_path);
            $data['attachment_path'] = $request->file('attachment')->store('latest-schedules', 'public');
        }

        $latestSchedule->update($data);

        return redirect()->route('latest-schedules.index')->with('status', 'Jadwal terbaru berhasil diperbarui.');
    }

    public function destroy(LatestSchedule $latestSchedule): RedirectResponse
    {
        $this->deleteStoredFile($latestSchedule->attachment_path);
        $latestSchedule->delete();

        return redirect()->route('latest-schedules.index')->with('status', 'Jadwal terbaru berhasil dihapus.');
    }

    private function validateLatestSchedule(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'event_time' => ['required', 'date_format:H:i'],
            'description' => ['required', 'string'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'event_date.required' => 'Tanggal kegiatan wajib diisi.',
            'event_time.required' => 'Jam kegiatan wajib diisi.',
            'event_time.date_format' => 'Jam kegiatan harus berformat HH:MM.',
            'description.required' => 'Deskripsi kegiatan wajib diisi.',
            'attachment.mimes' => 'Lampiran atau pamflet harus berupa JPG, PNG, WEBP, atau PDF.',
            'attachment.max' => 'Lampiran atau pamflet maksimal 4 MB.',
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
