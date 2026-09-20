@extends('layout_utama')

@section('lubang_konten')
    <div class="max-w-xl w-full mx-auto px-6 py-12">

        <h1 class="text-3xl font-bold text-blue-400 border-b border-slate-700 pb-3 mb-4">
            Hosea
        </h1>

        <p class="mb-6 leading-relaxed">
            I'm a sysadmin-devops enthusiast who also builds software.
            I like understanding how things run under the hood — not just writing code,
            but also setting up and maintaining the environment it runs in.
        </p>

        <h2 class="text-xl font-semibold text-yellow-300 mb-3">My Stack</h2>
        <div class="flex flex-wrap gap-2 mb-8">
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">PostgreSQL</span>
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">FastAPI</span>
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">WSL</span>
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">Docker Compose</span>
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">React&Next</span>
            <span class="bg-slate-800 text-green-400 px-3 py-1 rounded text-sm">Tailwind</span>
        </div>

        <h2 class="text-xl font-semibold text-yellow-300 mb-3">Current Projects</h2>

<div class="mb-4 border border-slate-700 rounded-lg p-4">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-blue-300 font-semibold">🏠 Homelab</h3>
                <span class="text-xs bg-yellow-900 text-yellow-300 px-2 py-0.5 rounded-full">Ongoing</span>
            </div>
            <p class="text-sm text-slate-300 leading-relaxed">
                Setting up my own homelab from scratch. I've registered a domain and
                configured a tunnel to it, though I haven't deployed any actual services yet.
                Next step is figuring out what to self-host first.
            </p>
        </div>

        <div class="mb-8 border border-slate-700 rounded-lg p-4">
            <div class="flex items-center justify-between mb-1">
                <h3 class="text-blue-300 font-semibold">📈 Stock Market Simulator</h3>
                <span class="text-xs bg-yellow-900 text-yellow-300 px-2 py-0.5 rounded-full">Ongoing</span>
            </div>
            <p class="text-sm text-slate-300 leading-relaxed">
                A web app to simulate stock market trading, built with FastAPI on the backend.
                I've finished the full ERD covering 9 tables, and so far have implemented
                CRUD operations for 1 of them (~10% into the backend). Still early, but the
                data model is solid and the rest is just execution from here.
            </p>
        </div>

        <h2 class="text-xl font-semibold text-yellow-300 mb-3">Skills</h2>
        <ul class="list-disc list-inside text-sm text-slate-300 space-y-1 mb-8">
            <li>Linux fundamentals — daily driver via WSL, comfortable with the CLI</li>
            <li>System monitoring with tools like <code class="text-green-400">htop</code></li>
            <li>Containerized environments using Docker Compose (Apache, Postgres, pgAdmin)</li>
            <li>Backend development with FastAPI</li>
            <li>Database design (ERDs, relational schema design with PostgreSQL)</li>
            <li>Basic networking / tunneling for self-hosted services</li>
        </ul>

        <h2 class="text-xl font-semibold text-yellow-300 mb-3">Contact</h2>
        <ul class="text-sm space-y-1 mb-8">
            <li>
                GitHub:
                <a href="https://github.com/hosea-joveldy" class="text-blue-400 hover:underline">
                    hosea-joveldy
                </a>
            </li>
            <li>
                Email:
                <a href="mailto:lumi@luminus.dpdns.org" class="text-blue-400 hover:underline">
                    lumi@luminus.dpdns.org
                </a>
            </li>
        </ul>

        <p>
            <a href="./beranda" class="text-blue-400 hover:underline">← Back to home</a>
        </p>

    </div>
@endsection