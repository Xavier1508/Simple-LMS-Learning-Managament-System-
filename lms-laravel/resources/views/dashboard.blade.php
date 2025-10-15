<x-app-layout>
    <div class="px-8 py-6">
        <h2 class="text-2xl font-bold text-gray-800">Hello {{ Auth::user()->name }} 👋</h2>
        <p class="text-gray-500">Let's learn something new today!</p>
    </div>

    <div class="grid grid-cols-4 gap-6 px-8 pb-8">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold mb-3">Recent enrolled course</h3>
            <div class="flex items-center gap-3">
                <div class="p-3 bg-orange-100 rounded-lg text-orange-500">📐</div>
                <div>
                    <p class="font-semibold text-gray-800">Product Design Course</p>
                    <div class="h-2 bg-gray-200 rounded-full mt-2 mb-1">
                        <div class="h-2 bg-orange-500 rounded-full" style="width: 40%;"></div>
                    </div>
                    <p class="text-sm text-gray-500">14/30 class</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm col-span-1">
            <h3 class="font-semibold mb-3">Your Informations</h3>
            <div class="flex flex-col gap-3">
                <div>
                    <p class="font-semibold text-gray-800">Breaking News Christopher Attack Kominfo</p>
                    <p class="text-xs text-gray-400">11 Sep 2025</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">Xavier found a vulnerability</p>
                    <p class="text-xs text-gray-400">25 Sep 2025</p>
                </div>
            </div>
        </div>

        </div>
</x-app-layout>
