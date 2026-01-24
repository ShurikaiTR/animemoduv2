<div class="pt-8 border-t border-white/5 animate-pulse">
    <div class="w-40 h-8 bg-white/5 rounded-lg mb-8"></div>
    <div class="space-y-6">
        @foreach(range(1, 3) as $i)
            <div class="flex gap-4">
                <div class="w-12 h-12 rounded-full bg-white/5"></div>
                <div class="flex-1 space-y-3">
                    <div class="w-1/4 h-4 bg-white/5 rounded"></div>
                    <div class="w-full h-24 bg-white/5 rounded-2xl"></div>
                </div>
            </div>
        @endforeach
    </div>
</div>