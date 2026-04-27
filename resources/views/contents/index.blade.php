@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-700">Konten</p>
                    <h2 class="mt-2 text-3xl font-black text-slate-900">Kelola Konten</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Menu ini menampilkan daftar konten yang bisa ditambah dan diedit, dengan editor teks yang lebih nyaman untuk penulisan isi konten.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700">
                        Total: {{ $contents->count() }}
                    </div>
                    <a href="{{ route('contents.create') }}" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                        Tambah Konten
                    </a>
                </div>
            </div>
        </section>

        <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <div class="overflow-x-auto p-4">
                    <table id="contentsTable" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs uppercase tracking-[0.22em] text-slate-500">
                                <th class="px-5 py-4">Tanggal</th>
                                <th class="px-5 py-4">Jenis</th>
                                <th class="px-5 py-4">Isi Konten</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm text-slate-700">
                            @forelse ($contents as $contentItem)
                                <tr>
                                    <td class="px-5 py-4 text-slate-500">{{ $contentItem->published_at?->format('d M Y') }}</td>
                                    <td class="px-5 py-4 font-semibold text-slate-900">{{ $contentItem->type }}</td>
                                    <td class="px-5 py-4 text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($contentItem->content), 120) }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('contents.edit', $contentItem) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-slate-500">Belum ada konten.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        if (window.jQuery) {
            $('#contentsTable').DataTable({
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Belum ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    }
                }
            });
        }
    </script>
@endsection
