<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        return view('contents.index', [
            'contents' => Content::query()->latest('published_at')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('contents.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateContent($request);

        Content::create($data);

        return redirect()->route('contents.index')->with('status', 'Konten berhasil ditambahkan.');
    }

    public function edit(Content $content): View
    {
        return view('contents.edit', [
            'contentItem' => $content,
        ]);
    }

    public function update(Request $request, Content $content): RedirectResponse
    {
        $data = $this->validateContent($request);

        $content->update($data);

        return redirect()->route('contents.index')->with('status', 'Konten berhasil diperbarui.');
    }

    private function validateContent(Request $request): array
    {
        return $request->validate([
            'published_at' => ['required', 'date'],
            'type' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ], [
            'published_at.required' => 'Tanggal wajib diisi.',
            'type.required' => 'Jenis wajib diisi.',
            'content.required' => 'Isi konten wajib diisi.',
        ]);
    }
}
