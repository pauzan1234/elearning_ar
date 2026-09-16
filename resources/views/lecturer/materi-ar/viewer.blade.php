<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $materiAr->judul }} — AR Viewer</title>

    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #1E1B4B;
        }

        model-viewer {
            --poster-color: transparent;
            width: 100%;
            height: 100vh;
        }

        model-viewer::part(default-ar-button) {
            bottom: 24px;
        }
    </style>
</head>

<body class="text-white">

    <model-viewer
        src="{{ asset('storage/' . $materiAr->file_model) }}"
        alt="{{ $materiAr->judul }}"
        ar
        ar-modes="scene-viewer quick-look webxr"
        camera-controls
        auto-rotate
        shadow-intensity="1"
        exposure="1">

        <button slot="ar-button" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-white text-[#1E1B4B]
            font-semibold px-6 py-3.5 rounded-full shadow-xl flex items-center gap-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20a8 8 0 100-16 8 8 0 000 16z" />
                <path d="M12 8v8M8 12h8" />
            </svg>
            Lihat di Ruang Nyata
        </button>

    </model-viewer>

    {{-- Info panel --}}
    <div class="fixed top-0 left-0 right-0 p-5 bg-gradient-to-b from-black/60 to-transparent">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-white/80 hover:text-white">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="font-semibold text-lg mt-3">{{ $materiAr->judul }}</h1>
        @if ($materiAr->deskripsi)
        <p class="text-sm text-white/60 mt-1 max-w-md">{{ $materiAr->deskripsi }}</p>
        @endif
    </div>

</body>

</html>