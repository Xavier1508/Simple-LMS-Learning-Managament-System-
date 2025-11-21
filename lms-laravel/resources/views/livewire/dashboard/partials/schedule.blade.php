<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-lg text-gray-800">Today's Schedule</h3>
        <a href="{{ route('schedule') }}" class="text-xs font-bold text-orange-600 hover:underline">View Full Calendar &rarr;</a>
    </div>

    <div class="p-6">
        @if($todaysClasses->count() > 0)
            <div class="space-y-4">
                @foreach($todaysClasses as $session)
                    <div class="flex group">
                        {{-- Time Column --}}
                        <div class="w-24 flex-shrink-0 text-center pt-2">
                            <p class="font-bold text-gray-800">{{ $session->start_time->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $session->end_time->format('H:i') }}</p>
                        </div>

                        {{-- Card --}}
                        <a href="{{ route('courses.detail', ['id' => $session->course_class_id, 'tab' => 'session', 'session_id' => $session->id]) }}" class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-100 hover:border-orange-200 hover:bg-orange-50 transition relative overflow-hidden">
                            <div class="absolute left-0 top-0 w-1 h-full bg-orange-500 rounded-l-xl"></div>

                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-600">
                                            {{ $session->class->course->code }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-gray-200 text-gray-600">
                                            {{ $session->class->type }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-gray-800 text-sm group-hover:text-orange-700">{{ $session->class->course->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ $session->title }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="inline-flex items-center text-xs text-gray-500 bg-white px-2 py-1 rounded border border-gray-200">
                                        {{ $session->delivery_mode }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-gray-800 font-bold">No Classes Today</h4>
                <p class="text-sm text-gray-500">Enjoy your free time!</p>
            </div>
        @endif
    </div>
</div>
