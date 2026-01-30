@props(['activities' => [], 'username' => 'User'])

@if(count($activities) === 0)
    <div class="bg-bg-secondary/50 backdrop-blur-sm border border-white/5 rounded-3xl p-6 h-fit sticky top-24">
        <h3 class="text-xl font-bold text-white font-rubik mb-6 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-primary rounded-full"></span>
            Son Aktiviteler
        </h3>
        <p class="text-[#e0e0e0]/50 text-sm text-center py-8">
            Henüz aktivite yok
        </p>
    </div>
@else
    <div class="bg-bg-secondary/50 backdrop-blur-sm border border-white/5 rounded-3xl p-6 h-fit sticky top-24">
        <h3 class="text-xl font-bold text-white font-rubik mb-6 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-primary rounded-full"></span>
            Son Aktiviteler
        </h3>

        <div class="space-y-6">
            @foreach($activities as $activity)
                <x-profile.activity-item :activity="$activity" :username="$username" :index="$loop->index" />
            @endforeach
        </div>

        @if(count($activities) >= 20)
            <button
                class="w-full mt-6 flex items-center justify-center group bg-white/5 hover:bg-white/10 text-white/50 hover:text-white border border-transparent hover:border-white/10 rounded-xl py-2.5 transition-all font-medium text-sm">
                Daha Fazla Göster
                <x-icons.chevron-down class="w-4 h-4 ml-2 group-hover:translate-y-1 transition-transform" />
            </button>
        @endif
    </div>
@endif