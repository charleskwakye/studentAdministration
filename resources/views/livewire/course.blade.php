<div>
    {{-- show preloader while fetching data in the background --}}
    <div class="fixed top-8 left-1/2 -translate-x-1/2 z-50 animate-pulse"
         wire:loading>
        <x-sta.preloader class="bg-lime-700/60 text-white border border-lime-700 shadow-2xl">
            {{ $loading }}
        </x-sta.preloader>
    </div>


    {{-- filter section: course or coursename, description and couse per page --}}
    <div class="grid lg:grid-cols-3 grid-cols-1 gap-8">
        <div>
            <x-jet-label for="courseNameOrDescription" value="Filter"/>
            <div
                x-data="{courseNameOrDescription:@entangle('courseNameOrDescription')}"
                class="relative">
                <x-jet-input id="courseNameOrDescription" type="text"
                             class="block mt-1 w-full"
                             placeholder="Filter on course name or description"
                             wire:model.debounce.500ms="courseNameOrDescription"/>
                <div
                    x-show="courseNameOrDescription"
                    @click="courseNameOrDescription='';"
                    class="w-5 absolute right-4 top-3 cursor-pointer">
                    <x-phosphor-x-duotone/>
                </div>

            </div>
        </div>
        <div>
            <x-jet-label for="programme" value="Programme"/>
            <x-sta.form.select id="programme"
                               class="block mt-1 w-full" wire:model.debounce.500ms="programme">
                <option value="%">All Programmes</option>
                @foreach($programmes as $programme)
                    <option value="{{$programme->id}}">
                        {{$programme->name}}
                    </option>
                @endforeach
            </x-sta.form.select>
        </div>
        <div>
            <x-jet-label for="perPage" value="Course per page"/>
            <x-sta.form.select id="perPage"
                               class="block mt-1 w-full" wire:model="perPage">
                <option value="3">3</option>
                <option value="6">6</option>
                <option value="9">9</option>
                <option value="12">12</option>
                <option value="15">15</option>
                <option value="18">18</option>
                <option value="24">24</option>
            </x-sta.form.select>
        </div>
    </div>

    {{-- master section: cards with paginationlinks --}}

    <div class="my-4">{{ $courses->links() }}</div>
    <div class="grid grid-cols-1 lg:grid-cols-3 2xl:grid-cols-3 gap-8 mt-8">
        @foreach($courses as $course)
            <div wire:key="course-{{$course->id}}"
                 class="flex bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">

                <div class="flex-1 flex flex-col">
                    <div class="text-center border-b border-gray-200 px-4 py-2">{{$course->programme->name}}</div>
                    <div class="flex-1 p-4">
                        <p class="text-lg font-bold">{{$course['name']}}</p>
                    </div>
                    <div class="p-4">
                        <p>{{$course['description']}}</p>
                    </div>
                    @auth
                    <div class="flex justify-center border-t border-gray-200 p-7">
                        @if($course->student_courses->toArray() == null)
                        <button
                            disabled
                            type="button"
                            class="w-3/4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 focus:outline-none disabled:opacity-25">
                            Manage Students
                        </button>
                        @else
                            <button
                                wire:click="showStudents({{ $course->id }})"
                                type="button"
                                class="w-3/4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 focus:outline-none ">
                                Manage Students
                            </button>
                        @endif

                    </div>
                    @endauth


                </div>
            </div>
        @endforeach
    </div>
    <div class="my-4">{{ $courses->links() }}</div>
    {{--No  courses found--}}
    @if($courses->isEmpty())
        <x-sta.alert type="danger" class="w-full">
            Can't find any course with <b>'{{ $courseNameOrDescription }}'</b> in the <b> '{{$programme->name}}'</b>
            programme.
        </x-sta.alert>
    @endif
    {{-- Detail section --}}
    {{-- Detail section --}}
    <div x-data="{ open: @entangle('showModal') }"
         x-cloak
         x-show="open"
         x-transition.duration.500ms
         class="fixed z-40 inset-0 p-8 grid h-screen place-items-center backdrop-blur-sm backdrop-grayscale-[.7]
            bg-slate-100/70">
        <div
            @click.away="open = false;"
            @keyup.enter.window="open = false;"
            @keyup.esc.window="open = false;"
            class="flex bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
            <div class="flex-1 flex flex-col p-8">
                <div class="flex-1 pb-4">
                    <p class="text-lg font-bold">{{$selectedCourse->name ?? 'CourseName'}}</p>
                </div>
                <div class="pb-4">
                    <p>{{$selectedCourse->description ?? 'CourseDescription'}}</p>

                </div>
                @isset($selectedCourse->student_courses)
                <div class="border-t border-gray-200 pt-4">
                    @foreach($selectedCourse->student_courses as $eachStudent)
                        <p>{{$eachStudent->student['first_name']}} {{$eachStudent->student['last_name']}} (semester {{$eachStudent['semester']}})</p>
                    @endforeach
                    <p>{{$selectedCourse->student_courses}}</p>
                </div>
                @endisset


            </div>
        </div>

    </div>
</div>
