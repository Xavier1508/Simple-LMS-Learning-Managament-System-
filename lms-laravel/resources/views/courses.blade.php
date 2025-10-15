<x-app-layout>
    <div class="p-8 bg-gray-50">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Courses</h1>

        <div class="mb-6 flex items-center space-x-4 text-sm">
            <label class="text-gray-600">Running Period</label>
            <div class="relative">
                <select class="appearance-none bg-white border border-gray-300 rounded-md py-2 pl-3 pr-10 focus:outline-none focus:ring-orange-500 focus:border-orange-500 cursor-pointer">
                    <option>2025, Odd Semester</option>
                    <option>2024, Even Semester</option>
                </select>
                <span data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></span>
            </div>
        </div>

        <div class="flex border-b border-gray-300 mb-6 text-sm font-medium">
            <button class="px-4 py-2 text-orange-600 border-b-2 border-orange-600">LAB</button>
            <button class="px-4 py-2 text-gray-500 hover:text-orange-600 hover:border-b-2 hover:border-orange-600 transition duration-150">LEC</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg-grid-cols-3 gap-6">
            @foreach ($courses as $course)
            <div class="course-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 cursor-pointer">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $course->title }}</h2>
                <p class="text-xs text-gray-600 mb-3">{{ $course->code }}</p>

                <div class="border-t border-gray-100 pt-4 mt-4">
                    <p class="text-sm text-gray-700">{{ $course->description }}</p>
                </div>
            </div>
            @endforeach

            <div class="course-card bg-white p-6 rounded-lg shadow hover:shadow-lg transition duration-300 border border-gray-200 cursor-pointer flex flex-col justify-center items-center h-full text-center">
                 <img class="w-8 mb-2" src="{{ asset('images/courses.png') }}" alt="More Courses">
                <h3 class="text-lg font-semibold text-gray-700">Find More Courses</h3>
                <p class="text-sm text-gray-500">Expand your skillset</p>
            </div>
        </div>
    </div>
</x-app-layout>
