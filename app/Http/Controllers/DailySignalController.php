<?php

namespace App\Http\Controllers;

use App\Models\DailySignal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DailySignalController extends Controller
{
    private function normalizeSignalTime(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        $time = trim((string) $value);
        $time = str_replace('.', ':', $time);

        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $time) === 1) {
            $time = substr($time, 0, 5);
        }

        return $time;
    }

    public function index(string $type): View
    {
        abort_unless(in_array($type, ['forex', 'saham'], true), 404);

        return view('daily-signals.index', [
            'type' => $type,
            'signals' => DailySignal::query()
                ->where('type', $type)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ]);
    }

    public function update(Request $request, string $type): RedirectResponse
    {
        abort_unless(in_array($type, ['forex', 'saham'], true), 404);

        $signals = collect($request->input('signals', []))
            ->filter(function ($signal) {
                if (!is_array($signal)) {
                    return false;
                }

                return collect($signal)
                    ->except(['type', 'sort_order', 'position'])
                    ->contains(fn ($value) => filled($value));
            })
            ->map(function ($signal) use ($type) {
                $signal['type'] = $signal['type'] ?? $type;
                $signal['position'] = $signal['position'] ?? 'buy';
                $signal['signal_time'] = $this->normalizeSignalTime($signal['signal_time'] ?? null);

                return $signal;
            })
            ->all();

        Log::info('Daily signals update payload received.', [
            'count' => count($signals),
            'signals' => $signals,
        ]);

        if (empty($signals)) {
            return back()
                ->withErrors(['signals' => 'Data sinyal tidak terbaca saat disimpan. Isi minimal satu sinyal yang lengkap lalu coba simpan lagi.'])
                ->withInput();
        }

        $request->merge(['signals' => $signals]);

        $validated = $request->validate([
            'signals' => ['nullable', 'array'],
            'signals.*.type' => ['required', 'in:' . $type],
            'signals.*.symbol' => ['required', 'string', 'max:50'],
            'signals.*.pair_name' => ['required', 'string', 'max:255'],
            'signals.*.position' => ['required', 'in:buy,sell'],
            'signals.*.signal_date' => ['required', 'date'],
            'signals.*.signal_time' => ['required', 'date_format:H:i'],
            'signals.*.entry_value' => ['required', 'string', 'max:50'],
            'signals.*.target_value' => ['required', 'string', 'max:50'],
            'signals.*.stop_value' => ['required', 'string', 'max:50'],
            'signals.*.description' => ['nullable', 'string'],
            'signals.*.sort_order' => ['nullable', 'integer'],
        ], [
            'signals.*.symbol.required' => 'Symbol wajib diisi.',
            'signals.*.pair_name.required' => 'Nama pair atau emiten wajib diisi.',
            'signals.*.position.required' => 'Posisi wajib dipilih.',
            'signals.*.position.in' => 'Posisi harus Buy atau Sell.',
            'signals.*.signal_date.required' => 'Tanggal sinyal wajib diisi.',
            'signals.*.signal_date.date' => 'Tanggal sinyal tidak valid.',
            'signals.*.signal_time.required' => 'Jam sinyal wajib diisi.',
            'signals.*.signal_time.date_format' => 'Jam sinyal harus berformat HH:MM.',
            'signals.*.entry_value.required' => 'Nilai entry wajib diisi.',
            'signals.*.target_value.required' => 'Nilai target atau TP wajib diisi.',
            'signals.*.stop_value.required' => 'Nilai stop, support, atau SL wajib diisi.',
        ]);

        $signals = collect($validated['signals'] ?? [])->values();

        DB::transaction(function () use ($signals, $type) {
            DailySignal::query()->where('type', $type)->delete();

            if ($signals->isEmpty()) {
                return;
            }

            DailySignal::query()->insert(
                $signals->map(function (array $signal, int $index) {
                    return [
                        'type' => $signal['type'],
                        'symbol' => $signal['symbol'],
                        'pair_name' => $signal['pair_name'],
                        'position' => $signal['position'],
                        'signal_date' => $signal['signal_date'],
                        'signal_time' => $signal['signal_time'],
                        'entry_value' => $signal['entry_value'],
                        'target_value' => $signal['target_value'],
                        'stop_value' => $signal['stop_value'],
                        'description' => $signal['description'] ?? null,
                        'sort_order' => $signal['sort_order'] ?? ($index + 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->all()
            );
        });

        return redirect()->route('daily-signals.index', ['type' => $type])->with('status', 'Sinyal harian berhasil disimpan.');
    }
}
