<?php
declare(strict_types=1);
$meta['title'] = 'About QED';
?>
<header class="fixed top-0 left-0 w-full z-20 flex backdrop-blur-md bg-zinc-900/80 border-b border-zinc-800 shadow-md">
    <nav class="w-full flex items-center justify-center gap-3 px-[2vw] py-2.5 z-30">
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/" class="px-3 py-2 text-base rounded-md text-white hover:bg-zinc-800 hover:text-purple-400 transition">
            Home
        </a>
        <a href="#" class="px-3 py-2 text-base rounded-md text-purple-400 font-semibold hover:bg-zinc-800 hover:text-purple-400 transition">
            About
        </a>
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="px-3 py-2 text-base rounded-md text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
            GitHub
        </a>
    </nav>
</header>
<section class="relative w-[88%] min-h-[20vh] h-max flex flex-col items-center justify-center mt-[10vh] p-[5vw] text-center">
    <div class="mb-[10px] bg-[rgba(174,103,255,0.466)] border border-[rgb(65,59,78)] rounded-[5px] px-[10px] py-[5px] text-[rgb(182,153,250)] text-[13px] font-bold">
        Kinda true but also exaggerated
    </div>
    <h1 class="text-[calc(35px+0.5vw+0.5vh)] font-black [text-shadow:2px_2px_5px_black] mt-[5px] mb-[10px]">
        Architectural Optimization in <span class="text-[calc(35px+0.5vw+0.5vh)] text-[rgb(182,153,250)]">Q.E.D.</span>
    </h1>
    <p class="text-[20px] text-[rgba(255,255,255,0.737)] max-w-[70vw] mx-auto mt-[5px] text-center">
        An overview of what makes Q.E.D differ from basic PHP and NextJS
    </p>
    <div class="py-[10px] text-[13px] font-bold text-[rgb(128,128,128)]">
        idk why I'd even bother making this
    </div>
</section>
<section class="px-[15px] w-[88%] min-h-[20vh] h-max flex flex-col">
    <div class="mt-3 w-full flex flex-row portrait:flex-wrap justify-between gap-[10px]">
        <div class="relative w-[33%] min-w-[20vw] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[#818181] rounded-[12px] p-[15px] shadow-[2px_2px_5px_rgba(0,0,0,0.527)]">
            <div class="mb-[10px] px-[10px] py-[5px] bg-[red] rounded-[5px] w-max">
                <span class="text-[13px] font-black text-white">Traditional PHP</span>
            </div>
            <h3 class="text-[20px] font-black text-white">Spaghetti Routing</h3>
            <ul class="mt-[10px] pl-[15px] text-[13px] text-[rgba(255,255,255,0.737)] flex flex-col gap-[10px]">
                <li>Manual <span class="text-[rgb(182,153,250)]">require 'header.php'</span> in every file</li>
                <li>Frequent "Headers already sent" redirect failures</li>
                <li>Global variable collisions and leaking database credentials</li>
                <li>Fragile relative asset paths (<span class="text-[rgb(182,153,250)]">../../image.png</span>)</li>
                <li>Verbose, error-prone <span class="text-[rgb(182,153,250)]">bind_param()</span> ceremonies</li>
            </ul>
        </div>
        <div class="relative w-[33%] min-w-[20vw] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(2,183,228)] rounded-[12px] p-[15px] shadow-[2px_2px_5px_rgba(0,0,0,0.527)]">
            <div class="mb-[10px] px-[10px] py-[5px] bg-[rgb(0,162,255)] rounded-[5px] w-max">
                <span class="txt-s bold c-white">Next.js</span>
            </div>
            <h3 class="text-[20px] font-black text-white">Heavy Complexity</h3>
            <ul class="mt-[10px] pl-[15px] text-[13px] text-[rgba(255,255,255,0.737)] flex flex-col gap-[10px]">
                <li>Massive <span class="text-[rgb(182,153,250)]">node_modules</span> and heavy container images</li>
                <li>Complex build pipelines (Webpack, Turbopack, Babel)</li>
                <li>Cold-start serverless latencies and hydration mismatches</li>
                <li>Incompatible with simple shared hosting or standard LAMP</li>
            </ul>
        </div>
        <div class="relative w-[33%] min-w-[20vw] bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] p-[15px] shadow-[2px_2px_5px_rgba(0,0,0,0.527)]">
            <div class="px-[10px] py-[5px] bg-[rgb(111,82,177)] rounded-[5px] w-max mb-[10px]">
                <span class="text-[13px] font-black text-white">this project</span>
            </div>
            <h3 class="text-[20px] font-black text-white">Best of Both Worlds(maybe)</h3>
            <ul class="mt-[10px] pl-[15px] text-[13px] text-[rgba(255,255,255,0.737)] flex flex-col gap-[10px]">
                <li>Zero dependencies: No Node, npm, Composer, or external CLI</li>
                <li>Inside-out output buffering: dynamic metadata & redirects</li>
                <li>Global CSRF defense with co-located Server Actions</li>
                <li>Runs instantly on standard Apache/Nginx + PHP 8.2+</li>
            </ul>
        </div>
    </div>
</section>