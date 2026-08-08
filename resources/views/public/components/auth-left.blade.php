<div class="auth-left reveal">
    <h2 style="font-size: 36px; font-weight: 800; color: var(--text); margin-bottom: 24px; line-height: 1.2;">Solusi Bisnis <br><span class="gradient-text">Terintegrasi</span></h2>
    <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 32px; line-height: 1.6; max-width: 480px;">Kelola operasional, keuangan, dan hubungan pelanggan dalam satu platform cloud yang cepat dan aman. Ikuti update terbaru kami:</p>
    
    {{-- Blog List --}}
    <div style="display: flex; flex-direction: column; gap: 16px; max-width: 480px;">
        @php
            $authDemos = [
                ['title' => 'Panduan Menyiapkan Pembukuan Usaha Retail', 'date' => '24 Jul 2025', 'icon' => 'fa-book-open', 'color' => 'var(--primary)', 'slug' => ''],
                ['title' => '5 Fitur Wajib POS Restoran Modern 2025', 'date' => '22 Jul 2025', 'icon' => 'fa-cash-register', 'color' => 'var(--accent)', 'slug' => ''],
                ['title' => 'Keamanan Data: Keuntungan Cloud ERP vs Server Lokal', 'date' => '18 Jul 2025', 'icon' => 'fa-cloud', 'color' => '#10B981', 'slug' => ''],
            ];
            
            // Try to fetch actual posts if the BlogPost model exists
            $latestAuthPosts = collect();
            try {
                if (class_exists(\App\Models\BlogPost::class)) {
                    $latestAuthPosts = \App\Models\BlogPost::where('is_published', true)->orderBy('created_at', 'desc')->take(3)->get();
                }
            } catch (\Exception $e) {}
        @endphp

        @if($latestAuthPosts->count() > 0)
            @php
                $colors = ['var(--primary)', 'var(--accent)', '#10B981'];
                $icons = ['fa-newspaper', 'fa-fire', 'fa-bolt'];
            @endphp
            @foreach($latestAuthPosts as $index => $post)
                @php
                    $color = $colors[$index % count($colors)];
                    $icon = $icons[$index % count($icons)];
                @endphp
                <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" style="display: flex; gap: 16px; align-items: center; padding: 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; text-decoration: none; transition: transform .2s, border-color .2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='var(--border-strong)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border)'">
                    @if(isset($post->featured_image) && $post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; flex-shrink: 0; border: 1px solid var(--border);">
                    @else
                        <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $color }}15; color: {{ $color }}; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                            <i class="fa-solid {{ $icon }}"></i>
                        </div>
                    @endif
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.4;">{{ Str::limit($post->title, 55) }}</h4>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</div>
                    </div>
                </a>
            @endforeach
        @else
            @foreach($authDemos as $demo)
                <a href="{{ route('blog.index') }}" style="display: flex; gap: 16px; align-items: center; padding: 16px; background: var(--surface); border: 1px solid var(--border); border-radius: 16px; text-decoration: none; transition: transform .2s, border-color .2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);" onmouseover="this.style.transform='translateY(-2px)'; this.style.borderColor='var(--border-strong)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--border)'">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: {{ $demo['color'] }}15; color: {{ $demo['color'] }}; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                        <i class="fa-solid {{ $demo['icon'] }}"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 4px; line-height: 1.4;">{{ $demo['title'] }}</h4>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $demo['date'] }}</div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
