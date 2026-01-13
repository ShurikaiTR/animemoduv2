@props(['activeTab'])

<div
    class="py-20 text-center flex flex-col items-center justify-center bg-bg-secondary/20 rounded-3xl border border-white/5">
    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
        @if($activeTab === 'comments')
            <x-heroicon-o-chat-bubble-bottom-center-text class="w-8 h-8 text-white/20" />
        @else
            <x-heroicon-o-star class="w-8 h-8 text-white/20" />
        @endif
    </div>
    <h3 class="text-xl font-bold text-white mb-2">
        {{ $activeTab === 'comments' ? 'Henüz Yorum Yok' : 'Henüz İnceleme Yok' }}
    </h3>
    <p class="text-white/40 max-w-xs mx-auto">
        {{ $activeTab === 'comments'
    ? 'Bu yapım hakkındaki düşüncelerini ilk paylaşan sen ol ve tartışmayı başlat!'
    : 'Bu animeyi inceleyen ilk kişi sen ol!' }}
    </p>
</div>