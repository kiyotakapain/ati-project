@extends('layouts.app')

@section('content')
<div class="studio-shell min-h-screen text-slate-100">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="studio-glow studio-glow-one"></div>
        <div class="studio-glow studio-glow-two"></div>
        <div class="studio-grid"></div>
    </div>

    <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
        <section class="glass-panel rounded-[2rem] p-6 sm:p-8 lg:p-10">
            <div class="max-w-4xl">
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-200">Help</p>
                <h1 class="mt-4 font-display text-4xl font-bold text-white sm:text-5xl">How to use the website</h1>
                <p class="mt-4 text-base leading-7 text-slate-300 sm:text-lg">
                    This website lets you upload an image, choose a processing function, run it on the server, and preview or download the result.
                    Use the top navigation to move between categories, and use the theme toggle to switch between dark and light mode.
                </p>
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-3">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 1</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Choose a category</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Open Basics, Histogram, Thresholding, Noise, Filtering, Segmentation, Morphology, or Enhancement from the navbar.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 2</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Upload and run</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Pick an image, select a tool, set the parameter if needed, and press Run processing.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 3</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Preview or download</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">The result appears in the processed preview, and you can download it after the operation finishes.</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Basics</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Core image changes</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Grayscale</strong> removes color information and keeps only brightness.</p>
                    <p><strong class="text-white">Invert</strong> flips the pixel values to create a negative effect.</p>
                    <p><strong class="text-white">Sepia</strong> applies a warm vintage tone.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Histogram</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Tone distribution</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Stretch</strong> expands the intensity range so dark and bright areas spread out more evenly.</p>
                    <p><strong class="text-white">Equalize</strong> redistributes tones to improve contrast.</p>
                    <p><strong class="text-white">Histogram</strong> draws the pixel-intensity distribution.</p>
                    <p><strong class="text-white">Analysis</strong> gives a quick summary of the image plus histogram-style inspection.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Thresholding</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Binary separation</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Threshold</strong> turns pixels above or below a chosen value into black and white.</p>
                    <p><strong class="text-white">Otsu</strong> finds an automatic threshold based on image statistics.</p>
                    <p><strong class="text-white">Adaptive</strong> calculates a local threshold for uneven lighting.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Noise</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Artificial corruption</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Gaussian noise</strong> adds random smooth variations across the image.</p>
                    <p><strong class="text-white">Salt & pepper</strong> flips random pixels to black or white.</p>
                    <p><strong class="text-white">Speckle</strong> adds multiplicative grain-like noise.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Filtering</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Smoothing and sharpening</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Blur</strong> softens details and reduces small variations.</p>
                    <p><strong class="text-white">Median</strong> removes impulse noise while keeping edges stronger than blur.</p>
                    <p><strong class="text-white">Sharpen</strong> increases edge contrast.</p>
                    <p><strong class="text-white">Bilateral</strong> smooths areas while preserving edges.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Segmentation</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Split the image into regions</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">K-means</strong> groups similar colors into a fixed number of clusters.</p>
                    <p><strong class="text-white">Watershed</strong> separates touching objects by using region boundaries.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Morphology</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Shape-based operations</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Erode</strong> shrinks bright regions.</p>
                    <p><strong class="text-white">Dilate</strong> expands bright regions.</p>
                    <p><strong class="text-white">Open</strong> removes small bright artifacts.</p>
                    <p><strong class="text-white">Close</strong> fills small dark gaps.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8 lg:col-span-2">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Enhancement</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Improve image appearance</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="space-y-3 text-sm leading-6 text-slate-300">
                        <p><strong class="text-white">Brightness</strong> changes the overall lightness.</p>
                        <p><strong class="text-white">Contrast</strong> makes light and dark areas more or less distinct.</p>
                        <p><strong class="text-white">Gamma</strong> changes how shadows and highlights are balanced.</p>
                    </div>
                    <div class="space-y-3 text-sm leading-6 text-slate-300">
                        <p><strong class="text-white">CLAHE</strong> improves local contrast in darker or flatter areas.</p>
                        <p><strong class="text-white">Resize</strong> scales the image size using a percentage.</p>
                        <p><strong class="text-white">Compress</strong> simulates JPEG-style compression quality.</p>
                    </div>
                </div>
            </article>
        </section>
    </main>
</div>
@endsection
