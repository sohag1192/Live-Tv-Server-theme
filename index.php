<?php
// --- CONFIGURATION ---
const STREAM_BASE_URL = 'stream.php?stream=';

$streams = [
    'tsports_hd_1' => [
        'url_suffix' => 'tsports',
        'name' => 'T Sports HD',
        'desc' => 'Primary Stream'
    ],
    'tsports_hd_2' => [
        'url_suffix' => 'tsportshd',
        'name' => 'T Sports HD',
        'desc' => 'Backup Stream'
    ],
    'bas_live' => [
        'url_suffix' => '5000_baslive',
        'name' => 'Star Sports 1 Hd Live',
        'desc' => 'Event Stream 1'
    ],
    'red_live' => [
        'url_suffix' => '5001_redlive',
        'name' => 'Star Sports 2 Hd Live',
        'desc' => 'Event Stream 2'
    ],
    'speed_live' => [
        'url_suffix' => '5002_speedlive',
        'name' => 'Star Sports 3 Hd  Live',
        'desc' => 'High Bitrate'
    ],
];

$defaultStream = 'tsports_hd_1';

// --- HELPERS ---
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

if (isset($streams[$decryptedKey])) {
    $selectedStream = $decryptedKey;
} else {
    $selectedStream = $defaultStream;
}

$streamUrl = STREAM_BASE_URL . $streams[$selectedStream]['url_suffix'];
$streamName = $streams[$selectedStream]['name'];
$streamDesc = $streams[$selectedStream]['desc'];
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($streamName); ?> | Live Stream</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Player Aspect Ratio */
        .aspect-video-ratio { padding-bottom: 56.25%; }
        
        /* The "Ambilight" Glow Effect behind the player */
        .player-glow {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(59,130,246,0.15) 0%, rgba(59,130,246,0) 100%);
            transform: scale(1.05);
            filter: blur(40px);
            z-index: -1;
            border-radius: 20px;
        }

        .channel-card { transition: all 0.2s ease; }
        .channel-card:hover { transform: translateY(-2px); }
    </style>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-R3MY1J5DD2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-R3MY1J5DD2');
    </script>
</head>
<body class="bg-[#0b0f19] text-gray-200 min-h-screen flex flex-col">

    <nav class="border-b border-gray-800 bg-[#0f172a]/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-television-simple text-blue-500 text-2xl"></i>
                <span class="font-bold text-xl text-white tracking-tight">Stream<span class="text-blue-500">Hub</span></span>
            </div>
            <div class="flex items-center gap-2 px-3 py-1 bg-red-500/10 border border-red-500/20 rounded-full">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
                <span class="text-xs font-semibold text-red-400 uppercase tracking-widest">Live</span>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-4 md:p-8">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-4">
                
                <div class="relative w-full">
                    <div class="player-glow"></div>
                    <div class="relative aspect-video-ratio bg-black rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 group">
                        
                        <iframe
                            id="streamPlayer"
                            data-stream-src="<?php echo htmlspecialchars($streamUrl); ?>"
                            class="absolute top-0 left-0 w-full h-full border-0"
                            allow="autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>

                <div class="flex items-start justify-between bg-[#1e293b]/50 p-4 rounded-xl border border-gray-800">
                    <div>
                        <h1 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($streamName); ?></h1>
                        <p class="text-gray-400 text-sm mt-1"><?php echo htmlspecialchars($streamDesc); ?></p>
                    </div>
                    <button class="p-2 hover:bg-white/10 rounded-lg transition" title="Report Issue">
                        <i class="ph ph-warning-circle text-xl text-gray-400"></i>
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-[#1e293b]/30 rounded-2xl p-5 border border-gray-800 h-full">
                    <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes"></i> Available Channels
                    </h3>

                    <div class="flex flex-col gap-3">
                        <?php
                        foreach ($streams as $key => $stream) {
                            $isActive = ($key === $selectedStream);
                            $encryptedKey = encryptStreamKey($key);
                            
                            // Styling Logic
                            if ($isActive) {
                                // Active State Style
                                $cardClass = "bg-blue-600 border-blue-500 shadow-lg shadow-blue-900/20";
                                $textClass = "text-white";
                                $subTextClass = "text-blue-200";
                                $icon = '<i class="ph-fill ph-play-circle text-2xl"></i>';
                            } else {
                                // Inactive State Style
                                $cardClass = "bg-[#0f172a] border-gray-800 hover:border-gray-600 hover:bg-[#1e293b]";
                                $textClass = "text-gray-200";
                                $subTextClass = "text-gray-500";
                                $icon = '<i class="ph ph-television text-2xl text-gray-500"></i>';
                            }
                        ?>
                            <a href="?stream=<?php echo urlencode($encryptedKey); ?>" 
                               class="channel-card relative flex items-center gap-4 p-4 rounded-xl border <?php echo $cardClass; ?> group">
                                
                                <div class="shrink-0">
                                    <?php echo $icon; ?>
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-semibold text-sm <?php echo $textClass; ?>">
                                        <?php echo htmlspecialchars($stream['name']); ?>
                                    </h4>
                                    <p class="text-xs <?php echo $subTextClass; ?>">
                                        <?php echo htmlspecialchars($stream['desc']); ?>
                                    </p>
                                </div>
                                
                                <?php if ($isActive): ?>
                                    <div class="flex gap-0.5 items-end h-3">
                                        <div class="w-1 bg-white/70 rounded-full animate-[bounce_1s_infinite] h-2"></div>
                                        <div class="w-1 bg-white/70 rounded-full animate-[bounce_1.2s_infinite] h-3"></div>
                                        <div class="w-1 bg-white/70 rounded-full animate-[bounce_0.8s_infinite] h-1"></div>
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-t border-gray-800 bg-[#0f172a] mt-auto">
        <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                &copy; <?php echo date("Y"); ?> LiveStream Portal. 
                <span class="hidden md:inline mx-2">|</span> 
                <span class="text-xs">Secure Connection</span>
            </p>
            
            <div class="opacity-70 grayscale hover:grayscale-0 transition-all duration-500">
                <img src="https://hitscounter.dev/api/hit?url=http%3A%2F%2Flive.rootmaster.xyz%2F&label=Views&icon=eye&color=%231e293b&message=&style=flat-square&tz=UTC" alt="Stats">
            </div>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const iframe = document.getElementById('streamPlayer');
        if (iframe) {
            const streamSrc = iframe.getAttribute('data-stream-src');
            if (streamSrc) {
                iframe.src = streamSrc;
                iframe.removeAttribute('data-stream-src');
            }
        }
    });
    </script>
</body>
</html>