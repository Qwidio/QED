<?php
declare(strict_types=1);
$meta['title'] = 'QED';
?>
<header class="fixed top-0 left-0 w-full z-20 flex backdrop-blur-md bg-zinc-900/80 border-b border-zinc-800 shadow-md">
    <nav class="w-full flex items-center justify-center gap-3 px-[2vw] py-2.5 z-30">
        <a href="#" class="px-3 py-2 text-base rounded-md text-purple-400 font-semibold hover:bg-zinc-800 hover:text-purple-400 transition">
            Home
        </a>
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/about" class="px-3 py-2 text-base rounded-md text-white hover:bg-zinc-800 hover:text-purple-400 transition">
            About
        </a>
        <a href="https://github.com/Qwidio/QED" target="_blank" rel="noopener noreferrer" class="px-3 py-2 text-base rounded-md text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
            GitHub
        </a>
    </nav>
</header>
<section class="w-[88%] min-h-[20vh] h-max flex flex-col items-center justify-center mt-[10vh] p-[5vw] text-center relative">
    <div class="bg-[rgba(174,103,255,0.466)] border border-[rgb(65,59,78)] rounded-[5px] px-[10px] py-[5px] text-[rgb(182,153,250)] text-[13px] font-bold mb-[10px]">
        Kinda Next.js • Pure PHP • With Bloated CSS
    </div>
    <h1 class="text-[calc(35px+0.5vw+0.5vh)] font-black [text-shadow:2px_2px_5px_black] mt-[5px] mb-[10px]">
        Recreation of NextJS
    </h1>
    <p class="text-[20px] text-[rgba(255,255,255,0.737)] max-w-[70vw] mx-auto mt-[5px] mb-[10vh] text-center">
        An LFI-safe, zero-dependency App Router delivering file-based routing, nested layout trees, 
        and co-located server actions with standard Apache/Nginx simplicity.
    </p>
    <div class="flex flex-row items-center justify-center gap-[10px] mt-[5vh] flex-wrap">
        <a href="<?= e($base_path, ENT_QUOTES, 'UTF-8') ?>/about" class="px-[15px] py-[10px] bg-[rgb(111,82,177)] hover:text-[rgb(255,143,39)] rounded-[12px] text-white font-black text-[20px] relative shadow-[2px_2px_5px_rgba(0,0,0,0.527)] overflow-hidden cursor-pointer transition-all duration-200">
            More about this
        </a>
        <button type="button" onclick="popMsg('Told you so!')" class="px-[15px] py-[10px] bg-[rgba(105,105,105,0.466)] hover:bg-[rgba(255,255,255,0.249)] border border-[#e6e6e6] rounded-[12px] text-white font-bold text-[20px] cursor-pointer transition-all duration-200">
            totally safe button
        </button>
    </div>
</section>
<section class="w-[88%] min-h-[20vh] h-max flex flex-col items-center justify-center mt-[5vh] p-[15px]">
    <div class="relative p-[15px] w-full bg-gradient-to-b from-[rgb(28,30,31)] to-[rgb(41,40,43)] border border-[rgb(65,59,78)] rounded-[12px] shadow-[2px_2px_5px_rgba(0,0,0,0.527)] flex flex-wrap justify-between">
        <div class="flex flex-col items-center justify-center w-[20%] min-w-[15vw] p-[10px]">
            <span class="relative text-[29px] font-black text-[rgb(255,164,61)]">0</span>
            <p class="relative text-[13px] text-[rgba(255,255,255,0.737)] text-center mt-[5px]">External Dependencies</p>
            <p class="relative text-[11px] text-[rgb(128,128,128)] text-center">Idk if that's a good thing or not</p>
        </div>
        <div class="flex flex-col items-center justify-center w-[20%] min-w-[15vw] p-[10px]">
            <span class="relative text-[29px] font-black text-[rgb(71,145,255)]">&lt; 12ms</span>
            <p class="relative text-[13px] text-[rgba(255,255,255,0.737)] text-center mt-[5px]">Average TTFB Latency</p>
            <p class="relative text-[11px] text-[rgb(128,128,128)] text-center">the real loading is longer because php</p>
        </div>
        <div class="flex flex-col items-center justify-center w-[20%] min-w-[15vw] p-[10px]">
            <span class="relative text-[29px] font-black text-[rgb(71,145,255)]">3-Layer</span>
            <p class="relative text-[13px] text-[rgba(255,255,255,0.737)] text-center mt-[5px]">LFI Defense Confinement</p>
            <p class="relative text-[11px] text-[rgb(128,128,128)] text-center">because yes</p>
        </div>
        <div class="flex flex-col items-center justify-center w-[20%] min-w-[15vw] p-[10px]">
            <span class="relative text-[29px] font-black text-[rgb(255,164,61)]">100%</span>
            <p class="relative text-[13px] text-[rgba(255,255,255,0.737)] text-center mt-[5px]">Prepared Statement Queries</p>
            <p class="relative text-[11px] text-[rgb(128,128,128)] text-center">Like what I'd mostly do on other projects</p>
        </div>
    </div>
  </section>
  <p class="text-[11px] text-[rgb(128,128,128)]">holy ai looking site ain't it? don't worry it ain't ai</p>
    
  <div id="alertcard">
      <div id="alertcontent">Alert Message Placeholder</div>
      <div id="borderanimate"></div>
  </div>
<script>
function popMsg(msg) {
    const card = document.getElementById('alertcard');
    const content = document.getElementById('alertcontent');
    if (!card || !content) return;
    
    content.innerText = msg;
    card.style.transform = 'translateX(0)';
    
    const border = document.getElementById('borderanimate');
    if (border) {
        border.style.animation = 'none';
        border.offsetHeight;
        border.style.animation = null;
    }
    
    setTimeout(() => {
        card.style.transform = 'translateX(100vw)';
    }, 4500);
}
</script>