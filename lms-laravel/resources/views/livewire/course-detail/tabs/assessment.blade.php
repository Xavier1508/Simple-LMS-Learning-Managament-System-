<div class="animate-fade-in min-h-[600px]">
    @if($assessmentState === 'list')
        @include('livewire.course-detail.tabs.assessment.list')

    @elseif($assessmentState === 'create')
        @include('livewire.course-detail.tabs.assessment.create')

    @elseif($assessmentState === 'detail')
        @include('livewire.course-detail.tabs.assessment.detail')
    @endif
</div>
