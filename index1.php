<?php
// --- CONFIGURATION ---
const STREAM_BASE_URL = 'stream.php?stream=';

$streams = [
    'tsports_hd_1' => [
        'url_suffix' => 'tsports',
        'name' => 'T Sports HD',
        'category' => 'Sports',
        'status' => 'LIVE'
    ],
    'tsports_hd_2' => [
        'url_suffix' => 'tsportshd',
        'name' => 'T Sports Backup',
        'category' => 'Sports',
        'status' => 'LIVE'
    ],
    'bas_live' => [
        'url_suffix' => '5000_baslive',
        'name' => 'Bas Live',
        'category' => 'Events',
        'status' => 'LIVE'
    ],
    'red_live' => [
        'url_suffix' => '5001_redlive',
        'name' => 'Red Live',
        'category' => 'Exclusive',
        'status' => 'HD'
    ],
    'speed_live' => [
        'url_suffix' => '5002_speedlive',
        'name' => 'Speed Live',
        'category' => 'Racing',
        'status' => '4K'
    ],
];

$defaultStream = 'tsports_hd_1';

// --- ENCRYPTION HELPERS ---
function encryptStreamKey(string $key): string {
    return strtr(base64_encode($key), '+/=', '-_,');
}

function decryptStreamKey(?string $encryptedKey): ?string {
    if (!$encryptedKey) return null;
    return base64_decode(strtr($encryptedKey, '-_,', '+/=') , true);
}

// --- LOGIC ---
$encryptedStream = filter_input(INPUT_GET, 'stream', FILTER_DEFAULT);
$decryptedKey = decryptStreamKey($encryptedStream);

// Validate selection
$activeKey = isset($streams[$decryptedKey]) ? $decryptedKey : $defaultStream;

$streamData = $streams[$activeKey];
$streamUrl = STREAM_BASE_URL . $streamData['url_suffix'];
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($streamData['name']); ?> - StreamHub</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #020617; /* Slate 950 */
            color: #e2e8f0;
        }
        
        /* Subtle Gradient Background */
        .bg-ambient {
            background: radial-gradient(circle at 50% 0%, rgba(30, 58, 138, 0.15) 0%, rgba(2, 6, 23, 0) 60%);
        }

        /* Video Container */
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255,255,255,0.05);
        }

        .video-wrapper iframe {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; z-index: 10;
        }

        /* Loading Spinner Overlay */
        .loader-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            background: #0f172a; z-index: 5;
            transition: opacity 0.5s ease;
        }

        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Animations */
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px -3px rgba(59, 130, 246, 0.3); }
            50% { box-shadow: 0 0 25px -3px rgba(59, 130, 246, 0.6); }
        }
        .active-glow { animation: pulse-glow 3s infinite; }
    </style>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R3MY1J5DD2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-R3MY1J5DD2');
    </script>
</head>
<body class="min-h-screen flex flex-col bg-ambient overflow-x-hidden">

    <nav class="h-16 border-b border-white/5 bg-[#020617]/80 backdrop-blur-md sticky top-0 z-50 px-4 md:px-8 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 rounded-lg p-1.5">
                <i class="ph-fill ph-play text-white"></i>
            </div>
            <span class="font-bold text-lg tracking-tight text-white">Stream<span class="text-blue-500">Hub</span></span>
        </div>
        
        <div class="flex items-center gap-4">
             <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800/50 border border-white/10">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-medium text-slate-300">System Normal</span>
            </div>
            <a href="#" class="p-2 text-slate-400 hover:text-white transition"><i class="ph ph-user-circle text-2xl"></i></a>
        </div>
    </nav>

    <main class="flex-grow p-4 md:p-6 lg:p-8 max-w-[1600px] mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        <div class="lg:col-span-8 xl:col-span-9 flex flex-col gap-5">
            
            <div class="video-wrapper active-glow group">
                <div id="videoLoader" class="loader-overlay">
                    <div class="flex flex-col items-center gap-3">
                        <i class="ph ph-spinner-gap animate-spin text-4xl text-blue-500"></i>
                        <span class="text-xs text-slate-500 font-medium uppercase tracking-widest">Loading Stream</span>
                    </div>
                </div>
                
                <iframe
                    id="streamPlayer"
                    data-src="<?php echo htmlspecialchars($streamUrl); ?>"
                    allow="autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                    allowfullscreen
                    onload="document.getElementById('videoLoader').style.opacity = '0'; setTimeout(() => document.getElementById('videoLoader').style.display = 'none', 500);"
                ></iframe>
            </div>

            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="px-2 py-0.5 rounded bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-wider">
                            <?php echo htmlspecialchars($streamData['category']); ?>
                        </span>
                        <?php if($streamData['status'] === 'LIVE'): ?>
                            <span class="px-2 py-0.5 rounded bg-red-500 text-white text-[10px] font-bold uppercase tracking-wider animate-pulse">
                                LIVE
                            </span>
                        <?php else: ?>
                             <span class="px-2 py-0.5 rounded bg-slate-700 text-slate-300 text-[10px] font-bold uppercase tracking-wider">
                                <?php echo htmlspecialchars($streamData['status']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white leading-tight">
                        <?php echo htmlspecialchars($streamData['name']); ?>
                    </h1>
                    <p class="text-slate-400 text-sm mt-2 max-w-2xl">
                        Watching live secure stream via StreamHub. High definition feed with adaptive bitrate streaming enabled.
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-sm font-medium transition border border-white/5">
                        <i class="ph ph-share-network"></i> Share
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-sm font-medium transition border border-white/5">
                        <i class="ph ph-warning-circle"></i> Report
                    </button>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-4 xl:col-span-3 flex flex-col h-full min-h-[400px]">
            <div class="bg-slate-900/50 border border-white/5 rounded-2xl overflow-hidden flex flex-col h-full backdrop-blur-sm">
                
                <div class="p-4 border-b border-white/5 bg-slate-900/80 flex items-center justify-between">
                    <h2 class="font-semibold text-white">Live Channels</h2>
                    <span class="text-xs text-slate-500 bg-slate-800 px-2 py-1 rounded"><?php echo count($streams); ?> Online</span>
                </div>

                <div class="flex-grow overflow-y-auto custom-scroll p-2 space-y-2">
                    <?php
                    foreach ($streams as $key => $data) {
                        $isActive = ($key === $activeKey);
                        $encryptedKey = encryptStreamKey($key);
                        
                        // Dynamic Classes
                        if ($isActive) {
                            $containerClass = "bg-blue-600/10 border-blue-500/50 shadow-[inset_4px_0_0_0_#3b82f6]";
                            $titleClass = "text-blue-400";
                            $iconColor = "text-blue-500";
                        } else {
                            $containerClass = "bg-transparent border-transparent hover:bg-slate-800/50 hover:border-slate-700";
                            $titleClass = "text-slate-200 group-hover:text-white";
                            $iconColor = "text-slate-500 group-hover:text-slate-400";
                        }
                    ?>
                    
                    <a href="?stream=<?php echo urlencode($encryptedKey); ?>" class="group block p-3 rounded-xl border transition-all duration-200 <?php echo $containerClass; ?>">
                        <div class="flex items-center gap-3">
                            <div class="relative w-12 h-12 rounded-lg bg-slate-800 flex items-center justify-center shrink-0 overflow-hidden">
                                <i class="ph-fill ph-television-simple text-2xl <?php echo $iconColor; ?>"></i>
                                <?php if($isActive): ?>
                                    <div class="absolute bottom-0 left-0 w-full h-0.5 bg-blue-500 animate-pulse"></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-grow min-w-0">
                                <h3 class="text-sm font-bold truncate <?php echo $titleClass; ?>">
                                    <?php echo htmlspecialchars($data['name']); ?>
                                </h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                        <?php echo htmlspecialchars($data['category']); ?>
                                    </span>
                                    <?php if($isActive): ?>
                                        <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                        <span class="text-[10px] text-blue-500">Playing</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if($data['status'] === 'LIVE' && !$isActive): ?>
                                <span class="shrink-0 w-2 h-2 rounded-full bg-red-500"></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <?php } ?>
                </div>
                
                <div class="p-4 border-t border-white/5 bg-slate-900/80 text-center">
                    <img src="https://hitscounter.dev/api/hit?url=http%3A%2F%2Flive.rootmaster.xyz%2F&label=Total%20Views&icon=eye&color=%231e293b&message=&style=flat-square&tz=UTC" class="opacity-50 grayscale hover:grayscale-0 transition mx-auto" alt="Counter">
                </div>
            </div>
        </aside>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const iframe = document.getElementById('streamPlayer');
        if (iframe) {
            const streamSrc = iframe.getAttribute('data-src');
            if (streamSrc) {
                iframe.src = streamSrc;
                // Note: The loader is hidden via the iframe's onload attribute
                // but we also remove the attribute for cleanliness
                iframe.removeAttribute('data-src');
            }
        }
    });
    </script>
</body>
</html>