@extends('layouts.app')

@section('content')
@php
    $operationGroups = $operationGroups ?? [];
    $stats = $stats ?? [];
@endphp

<div class="studio-shell min-h-screen text-slate-100">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="studio-glow studio-glow-one"></div>
        <div class="studio-glow studio-glow-two"></div>
        <div class="studio-grid"></div>
    </div>

    <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-6 sm:px-6 lg:px-8 lg:py-10" data-multi-workflow data-default-operation="grayscale">
        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-6">
                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Category</p>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">Apply Multi Operation</h2>
                        </div>
                        <div class="text-sm text-slate-300">Queue-ready</div>
                    </div>

                    <form class="mt-6 flex w-full flex-col gap-3 sm:w-auto sm:flex-row" data-upload-form>
                        <label class="group flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-dashed border-cyan-400/30 bg-slate-950/40 px-4 py-3 text-sm text-slate-300 transition hover:border-cyan-300 hover:bg-slate-950/60 sm:min-w-72">
                            <span class="truncate" data-file-label>Choose an image</span>
                            <span class="rounded-full bg-cyan-400/15 px-3 py-1 text-xs font-semibold text-cyan-200">Browse</span>
                            <input class="hidden" type="file" name="image" accept="image/*" data-image-input>
                        </label>

                        <button class="rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300 disabled:cursor-not-allowed disabled:opacity-40" type="submit">
                            Upload
                        </button>
                    </form>

                    <div class="mt-6 rounded-[1.75rem] border border-white/10 bg-slate-950/40 p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Selected operation</p>
                                <h3 class="mt-2 font-display text-2xl font-bold text-white" data-operation-title>Grayscale</h3>
                                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300" data-operation-help>Convert the image to grayscale.</p>
                            </div>
                            <span class="sr-only" data-operation-key>grayscale</span>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-200" for="operation-value" data-value-label>Parameter</label>
                                <input id="operation-value" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20" type="number" value="0" data-value-input>
                                <p class="text-xs text-slate-400" data-value-help>Set a value only when the selected operation needs one.</p>
                            </div>

                            <div class="flex flex-col gap-3 md:items-end md:justify-end">
                                <button class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-reset-button>
                                    Reset previews
                                </button>
                                <button class="rounded-2xl border border-cyan-300/30 bg-cyan-400/10 px-5 py-3 text-sm font-semibold text-cyan-100 transition hover:border-cyan-200 hover:bg-cyan-400/15 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-add-operation-button>
                                    Add operation
                                </button>
                                <button class="rounded-2xl bg-gradient-to-r from-cyan-400 to-emerald-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:from-cyan-300 hover:to-emerald-300 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-process-button disabled>
                                    Apply operations
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Operation catalog</p>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">Choose from the full list</h2>
                        </div>
                        <div class="text-sm text-slate-300">All available tools</div>
                    </div>

                    <div class="mt-6 space-y-6">
                        @foreach ($operationGroups as $group)
                            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ $group['title'] }}</p>
                                        <h3 class="mt-2 font-display text-xl font-bold text-white">{{ $group['title'] }}</h3>
                                    </div>
                                    <div class="text-sm text-slate-300">{{ count($group['items'] ?? []) }} tools</div>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($group['items'] ?? [] as $item)
                                        <button
                                            class="operation-button rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-cyan-300/50 hover:bg-slate-950/80"
                                            type="button"
                                            data-operation="{{ $item['key'] }}"
                                            data-label="{{ $item['label'] }}"
                                            data-default="{{ $item['default'] }}"
                                            data-min="{{ $item['min'] ?? '' }}"
                                            data-max="{{ $item['max'] ?? '' }}"
                                            data-step="{{ $item['step'] ?? '' }}"
                                            data-help="{{ $item['help'] }}"
                                        >
                                            <span class="block text-sm font-semibold text-white">{{ $item['label'] }}</span>
                                            <span class="mt-1 block text-xs leading-5 text-slate-400">{{ $item['help'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Queue</p>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">Pending operations</h2>
                        </div>
                        <button class="rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-clear-queue-button>
                            Clear queue
                        </button>
                    </div>

                    <div class="mt-5 rounded-[1.5rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Steps to apply</p>
                        <ul class="mt-4 space-y-3" data-operation-queue></ul>
                    </div>
                </div>
            </div>

            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Live previews</p>
                        <h2 class="mt-2 font-display text-2xl font-bold text-white">Track the current result</h2>
                    </div>
                </div>

                <div class="mt-6 space-y-5">
                    <figure class="preview-card">
                        <div class="preview-head">
                            <h3>Current image</h3>
                            <span data-original-status>Waiting for upload</span>
                        </div>
                        <div class="preview-body">
                            <img alt="Current preview" data-original-preview>
                            <div class="preview-placeholder" data-original-placeholder>
                                Upload an image to see the starting point here.
                            </div>
                        </div>
                    </figure>

                    <figure class="preview-card">
                        <div class="preview-head">
                            <h3>Latest result</h3>
                            <span data-processed-status>Waiting for processing</span>
                        </div>
                        <div class="preview-body">
                            <img alt="Processed preview" data-processed-preview>
                            <div class="preview-placeholder" data-processed-placeholder>
                                Apply a queued operation to render the latest output here.
                            </div>
                        </div>
                    </figure>

                    <a class="hidden inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-300" data-download-button download>
                        Download latest result
                    </a>

                    <div class="rounded-[1.5rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300" data-status>
                        Upload an image, add one or more operations, and apply the queue to build on the previous result.
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection