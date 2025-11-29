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

// --- ENCRYPTION HELPERS (UNCHANGED) ---
function encryptStreamKey(string $key): string {
    return strtr(base64_encode($key), '+/=', '-_,');
}

function decryptStreamKey(?string $encryptedKey): ?string {
    if (!$encryptedKey) return null;
    return base64_decode(strtr($encryptedKey, '-_,', '+/=') , true);
}

// --- LOGIC (UNCHANGED) ---
$encryptedStream = filter_input(INPUT_GET, 'stream', FILTER_DEFAULT);
$decryptedKey = decryptStreamKey($encryptedStream);

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
        /* Base Colors */
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #0d0d0d; /* Near Black for True Contrast */
            color: #e4e4e7; /* Neutral 200 */
        }
        
        /* Subtle Ambient Glow Effect */
        .bg-ambient {
            background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.1) 0%, rgba(13, 13, 13, 0) 70%);
        }

        /* Player Wrapper */
        .video-wrapper {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 */
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        }

        .video-wrapper iframe {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0; z-index: 10;
        }

        /* Loading Spinner */
        .loader-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            background: #171717; z-index: 5;
            transition: opacity 0.5s ease;
        }

        /* Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #52525b; }
        
        /* Active Focus Ring for the Stream Card */
        .card-active-ring {
            box-shadow: 0 0 0 2px #3b82f6; /* Blue ring effect */
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-ambient overflow-x-hidden">

    <nav class="h-14 bg-[#0a0a0a]/80 backdrop-blur-md sticky top-0 z-50 px-4 md:px-8 flex items-center border-b border-white/5">
        <div class="flex items-center gap-2">
            <div class="text-blue-500 text-2xl"><i class="ph-fill ph-monitor"></i></div>
            <span class="font-extrabold text-xl tracking-tighter text-white">STREAM<span class="text-blue-500">HUB</span></span>
        </div>
    </nav>

    <main class="flex-grow p-4 md:p-6 lg:p-8 max-w-[1500px] mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
        
        <div class="lg:col-span-9 flex flex-col gap-5">
            
            <div class="video-wrapper ring-1 ring-blue-500/30">
                <div id="videoLoader" class="loader-overlay">
                    <div class="flex flex-col items-center gap-3">
                        <i class="ph ph-circle-notch animate-spin text-4xl text-blue-500"></i>
                        <span class="text-xs text-zinc-500 font-medium uppercase tracking-widest">Awaiting Stream Data...</span>
                    </div>
                </div>
                
                <iframe
                    id="streamPlayer"
                    data-src="<?php echo htmlspecialchars($streamUrl); ?>"
                    class="absolute top-0 left-0 w-full h-full border-0"
                    allow="autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                    allowfullscreen
                    onload="document.getElementById('videoLoader').style.opacity = '0'; setTimeout(() => document.getElementById('videoLoader').style.display = 'none', 500);"
                ></iframe>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 bg-[#171717] rounded-xl border border-white/5">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded-full bg-blue-600/10 text-blue-400 text-[10px] font-bold uppercase">
                            <?php echo htmlspecialchars($streamData['category']); ?>
                        </span>
                        <?php if($streamData['status'] === 'LIVE'): ?>
                            <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-600/20 text-red-400 text-[10px] font-bold uppercase animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> LIVE
                            </span>
                        <?php else: ?>
                             <span class="px-2 py-0.5 rounded-full bg-zinc-700/50 text-zinc-300 text-[10px] font-bold uppercase">
                                <?php echo htmlspecialchars($streamData['status']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white leading-tight">
                        <?php echo htmlspecialchars($streamData['name']); ?>
                    </h1>
                </div>
                
                <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-700/50 hover:bg-zinc-600/50 text-sm font-medium transition border border-white/5 text-zinc-300">
                    <i class="ph ph-arrow-square-out text-lg"></i> Open Fullscreen
                </button>
            </div>
        </div>

        <aside class="lg:col-span-3 flex flex-col h-full min-h-[400px]">
            <div class="bg-black/30 border border-white/10 rounded-xl overflow-hidden flex flex-col h-full backdrop-blur-md">
                
                <div class="p-4 border-b border-white/5 bg-black/50">
                    <h2 class="font-semibold text-white uppercase tracking-wider text-sm flex items-center gap-2">
                        <i class="ph ph-broadcast text-blue-500"></i> Active Streams
                    </h2>
                </div>

                <div class="flex-grow overflow-y-auto custom-scroll p-3 space-y-2">
                    <?php
                    foreach ($streams as $key => $data) {
                        $isActive = ($key === $activeKey);
                        $encryptedKey = encryptStreamKey($key);
                        
                        // Dynamic Classes
                        if ($isActive) {
                            $containerClass = "bg-blue-600/10 card-active-ring";
                            $titleClass = "text-blue-400";
                            $iconColor = "text-blue-500";
                        } else {
                            $containerClass = "bg-transparent border-transparent hover:bg-zinc-800/50";
                            $titleClass = "text-zinc-200 group-hover:text-white";
                            $iconColor = "text-zinc-500 group-hover:text-zinc-400";
                        }
                    ?>
                    
                    <a href="?stream=<?php echo urlencode($encryptedKey); ?>" class="group block p-3 rounded-lg border border-transparent transition-all duration-200 <?php echo $containerClass; ?>">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md bg-zinc-800 flex items-center justify-center shrink-0">
                                <i class="ph-fill ph-monitor-play text-xl <?php echo $iconColor; ?>"></i>
                            </div>
                            
                            <div class="flex-grow min-w-0">
                                <h3 class="text-sm font-medium truncate <?php echo $titleClass; ?>">
                                    <?php echo htmlspecialchars($data['name']); ?>
                                </h3>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="text-[10px] text-zinc-500 uppercase font-medium">
                                        <?php echo htmlspecialchars($data['category']); ?>
                                    </span>
                                    <?php if($isActive): ?>
                                        <span class="w-1 h-1 rounded-full bg-blue-500"></span>
                                        <span class="text-[10px] text-blue-500 font-bold">NOW PLAYING</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                    <?php } ?>
                </div>
                
                <div class="p-4 border-t border-white/5 bg-black/50 text-center">
                    <p class="text-xs text-zinc-500">Secure Live Feed.</p>
                </div>
            </div>
        </aside>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const iframe = document.getElementById('streamPlayer');
        const loader = document.getElementById('videoLoader');

        if (iframe) {
            const streamSrc = iframe.getAttribute('data-src');
            if (streamSrc) {
                // Set the source after the DOM is ready
                iframe.src = streamSrc;
                iframe.removeAttribute('data-src');
            }
        }
    });
    </script>
</body>
</html>