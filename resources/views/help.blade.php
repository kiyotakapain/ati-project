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
                    This app lets you upload an image, run a single operation or a full chain of operations, preview the output, and download the latest result.
                    Use the top navigation to move between pages, and use the theme toggle to switch between dark and light mode.
                </p>
            </div>

            <div class="mt-8 grid gap-4 lg:grid-cols-4">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 1</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Choose a page</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Open Basics for one quick edit, Multi-Operation for chained work, or the other pages to browse the available tool groups.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 2</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Upload an image</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Choose an image from your computer, then upload it before running any tool.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 3</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Select the tool</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">Click an operation card to set it as the active tool. If the tool needs a number, edit the parameter field first.</p>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Step 4</p>
                    <h2 class="mt-2 text-lg font-semibold text-white">Preview and download</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-300">The processed image appears on the right, and the download button becomes available after the result is ready.</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Basics</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Quick one-step edits</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Grayscale</strong> removes color and keeps luminance only. Use it when you want a monochrome image.</p>
                    <p><strong class="text-white">Invert</strong> creates a negative of the image. It does not need any parameter.</p>
                    <p><strong class="text-white">Blur</strong> softens detail. Increase the value for a stronger blur; the value should stay odd.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Multi-Operation</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Build a chain of edits</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p>Upload one image, choose an operation, and click <strong class="text-white">Add operation</strong> to put it in the queue.</p>
                    <p>Keep adding steps as needed. When you click <strong class="text-white">Apply operations</strong>, each step runs on the previous result.</p>
                    <p>Use <strong class="text-white">Download latest result</strong> whenever you want to save the current output.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Histogram</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Inspect and reshape tones</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Show histogram</strong> displays the tone distribution of the image. No parameter is needed.</p>
                    <p><strong class="text-white">Stretching</strong> spreads the intensity range to improve contrast. No parameter is needed.</p>
                    <p><strong class="text-white">Equalization</strong> redistributes tones more evenly to reveal details in flat images. No parameter is needed.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Noise</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Add controlled corruption</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Gaussian</strong> adds smooth random noise. Increase the value to make it stronger.</p>
                    <p><strong class="text-white">Salt & Pepper</strong> flips random pixels to black or white. Use a smaller value for lighter noise.</p>
                    <p><strong class="text-white">Speckle</strong> adds grain-like multiplicative noise. It is also controlled by the numeric value.</p>
                    <p><strong class="text-white">Poisson</strong> behaves like a count-based noise effect in the current workflow. Use the numeric value as the strength control.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Filtering</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Reduce noise or refine detail</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Mean</strong> averages the neighborhood. Use an odd kernel size.</p>
                    <p><strong class="text-white">Gaussian</strong> smooths with a weighted average. Use an odd kernel size.</p>
                    <p><strong class="text-white">Median</strong> removes impulse noise while preserving edges better than mean blur.</p>
                    <p><strong class="text-white">KNN</strong> applies a neighborhood-based denoising step. Use the value as the neighborhood size.</p>
                    <p><strong class="text-white">Sigma</strong> behaves like another neighborhood-based smoothing variant. Use the value as the neighborhood size.</p>
                    <p><strong class="text-white">SNN</strong> is another kernel-based filter. Keep the value odd when possible.</p>
                    <p><strong class="text-white">Min/Max</strong> uses a kernel size to emphasize local extremes. Keep the value odd when possible.</p>
                    <p><strong class="text-white">NAGAO</strong> uses a larger neighborhood for edge-aware smoothing. Use the numeric value as the neighborhood size.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Edge Detection</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Highlight boundaries</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Roberts</strong> detects simple diagonal gradients. No parameter is needed.</p>
                    <p><strong class="text-white">Prewitt</strong> detects horizontal and vertical changes. No parameter is needed.</p>
                    <p><strong class="text-white">Sobel</strong> emphasizes edges with stronger smoothing than Prewitt. No parameter is needed.</p>
                    <p><strong class="text-white">Laplacian</strong> finds rapid intensity changes. No parameter is needed.</p>
                    <p><strong class="text-white">LoG</strong> combines smoothing and Laplacian-style edge detection. No parameter is needed.</p>
                    <p><strong class="text-white">Canny</strong> detects strong edges with a threshold value. Increase the value for stricter edge selection.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Segmentation</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Split the image into regions</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Thresholding (Simple)</strong> turns the image into foreground and background using the threshold value.</p>
                    <p><strong class="text-white">Thresholding (Double)</strong> uses a thresholding-style split with the same numeric control.</p>
                    <p><strong class="text-white">Thresholding (Otsu)</strong> chooses the threshold automatically. No parameter is needed.</p>
                    <p><strong class="text-white">Region Growing</strong> groups connected regions using the value as a cluster-count style control.</p>
                    <p><strong class="text-white">Split&amp;Merge</strong> segments the image with the same count-style control.</p>
                    <p><strong class="text-white">K-means</strong> groups similar pixels into the number of clusters you choose.</p>
                    <p><strong class="text-white">FCM</strong> performs fuzzy clustering with the same cluster count control.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Morphology</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Shape-based clean-up</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">Erosion</strong> shrinks bright areas. Use an odd kernel size.</p>
                    <p><strong class="text-white">Dilation</strong> expands bright areas. Use an odd kernel size.</p>
                    <p><strong class="text-white">Opening</strong> removes small bright noise by eroding then dilating. Use an odd kernel size.</p>
                    <p><strong class="text-white">Closing</strong> fills small holes by dilating then eroding. Use an odd kernel size.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8 lg:col-span-2">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Evaluation</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Compare results with a reference</h2>
                <div class="mt-4 space-y-3 text-sm leading-6 text-slate-300">
                    <p><strong class="text-white">MSE</strong> measures average squared error between the processed image and a reference image.</p>
                    <p><strong class="text-white">PSNR</strong> expresses reconstruction quality in decibels compared with a reference image.</p>
                    <p><strong class="text-white">IoU</strong> compares overlap between the processed image and a reference mask or target.</p>
                    <p><strong class="text-white">Dice</strong> also measures overlap, with stronger weight on shared content.</p>
                    <p class="text-slate-400">These tools are listed in the interface and should be used when you want to compare against a known reference image.</p>
                </div>
            </article>

            <article class="glass-panel rounded-[2rem] p-6 sm:p-8 lg:col-span-2">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Tips</p>
                <h2 class="mt-2 font-display text-2xl font-bold text-white">Practical usage notes</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="space-y-3 text-sm leading-6 text-slate-300">
                        <p>Operations that do not need a number can be applied immediately after upload.</p>
                        <p>Operations with numeric controls use the number field next to the tool buttons.</p>
                        <p>Multi-Operation keeps the latest result in the chain, so each new step starts from the previous output.</p>
                    </div>
                    <div class="space-y-3 text-sm leading-6 text-slate-300">
                        <p>If the download button is hidden, run an operation first.</p>
                        <p>Reset previews clears the current image and the current result.</p>
                        <p>Use the queue remove buttons in Multi-Operation if you want to reorder the chain by rebuilding it.</p>
                    </div>
                </div>
            </article>
        </section>
    </main>
</div>
@endsection
