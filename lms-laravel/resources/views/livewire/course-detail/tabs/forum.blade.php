<div class="animate-fade-in">
    {{-- SWITCHER VIEW --}}
    @if($forumState === 'list')
        @include('livewire.course-detail.tabs.forum.list')

    @elseif($forumState === 'create')
        @include('livewire.course-detail.tabs.forum.create')

    @elseif($forumState === 'detail')
        @include('livewire.course-detail.tabs.forum.detail')
    @endif
</div>
