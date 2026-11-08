<?php
declare(strict_types=1);
$meta['title'] = 'QED';
?>
<section class="relative p-10 w-[100vw] h-[100vh] flex flex-col items-center justify-center">
    <div class="w-max bg-[rgba(87,87,87,0.21)] border border-[rgb(119,119,119)] rounded-[5px] px-[10px] py-[5px] text-[rgb(245,242,253)] text-[13px] font-bold mb-[10px]">
        QED + Tailwinds
    </div>
    <h1 class="text-[calc(35px+0.5vw+0.5vh)] font-black [text-shadow:2px_2px_5px_black] mt-[5px] mb-[10px]">
        Get Started With QED
    </h1>
    <p class="text-[20px] text-[rgba(255,255,255,0.737)] max-w-[70vw] mx-auto mt-[5px] mb-[10vh] text-center">
        Start Editing the <code class="px-1">apps/app.php</code> to see the changes
    </p>
    <div class="mt-5 flex flex-row items-center justify-center gap-[10px] flex-wrap">
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="px-[15px] py-[10px] bg-[var(--accent)] hover:text-[rgb(4,132,155)] rounded-[12px] text-zinc-800 font-black text-[20px] relative shadow-[2px_2px_5px_rgba(0,0,0,0.527)] overflow-hidden cursor-pointer transition-all duration-200">
            Github
        </a>
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/docs" class="px-[15px] py-[10px] bg-[rgba(92,92,92,0.08)] hover:text-[rgb(39,223,255)] rounded-[12px] text-white font-black text-[20px] relative shadow-[2px_2px_5px_rgba(0,0,0,0.527)] overflow-hidden cursor-pointer transition-all duration-200">
            Open Docs
        </a>
    </div>
</section>