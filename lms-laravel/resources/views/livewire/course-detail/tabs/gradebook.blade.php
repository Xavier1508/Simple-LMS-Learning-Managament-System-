<div class="animate-fade-in min-h-[600px]">
    @if(Auth::user()->role === 'student')
        {{-- TAMPILAN SISWA (LANGSUNG DETAIL NILAI) --}}
        @include('livewire.course-detail.tabs.gradebook.student')
    @else
        {{-- TAMPILAN DOSEN (LIST MAHASISWA / INPUT NILAI) --}}
        @if($gradebookState === 'list')
            @include('livewire.course-detail.tabs.gradebook.lecturer-list')
        @else
            @include('livewire.course-detail.tabs.gradebook.lecturer-detail')
        @endif
    @endif
</div>
