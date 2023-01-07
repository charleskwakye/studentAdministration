<div>
    <x-sta.section
        x-data="{ open: false }"
        class="p-0 mb-4 flex flex-col gap-2">
        <div class="p-4 flex justify-between items-start gap-4">
            <div class="relative w-64">
                <x-jet-input id="newProgramme" type="text" placeholder="New programme"
                             @keydown.enter="$el.setAttribute('disabled', true); $el.value = '';"
                             @keydown.tab="$el.setAttribute('disabled', true); $el.value = '';"
                             @keydown.esc="$el.setAttribute('disabled', true); $el.value = '';"
                             wire:model.defer="newProgramme"
                             wire:keydown.enter="createProgramme()"
                             wire:keydown.tab="createProgramme()"
                             wire:keydown.escape="resetNewProgramme()"
                             class="w-full shadow-md placeholder-gray-300"/>
                <x-phosphor-arrows-clockwise
                    wire:loading
                    wire:target="createProgramme"
                    class="w-5 h-5 text-gray-200 absolute top-3 right-2 animate-spin"/>
            </div>
            <x-heroicon-o-information-circle
                @click="open = !open"
                class="w-5 text-gray-400 cursor-help outline-0"/>
        </div>
        <x-jet-input-error for="newProgramme" class="m-4 -mt-4 w-full"/>
        <div
            x-show="open"
            style="display: none"
            class="text-sky-900 bg-sky-50 border-t p-4">
            <x-sta.list type="ul" class="list-outside mx-4 text-sm">
                <li>
                    <b>A new programme</b> can be added by typing in the input field and pressing <b>enter</b> or
                    <b>tab</b>. Press <b>escape</b> to undo.
                </li>
                <li>
                    <b>Edit a programme</b> by clicking the
                    <x-phosphor-pencil-line-duotone class="w-5 inline-block"/>
                    icon or by clicking on the programme name. Press <b>enter</b> to save, <b>escape</b> to undo.
                </li>
                <li>
                    Clicking the
                    <x-heroicon-o-information-circle class="w-5 inline-block"/>
                    icon will toggle this message on and off.
                </li>
            </x-sta.list>
        </div>
    </x-sta.section>

    <x-sta.section>
        <table class="text-center w-full border border-gray-300">
            <colgroup>
                <col class="w-16">
                <col class="w-60">
                <col class="w-">
                <col class="w-16">
            </colgroup>
            <thead>
            <tr class="bg-gray-100 text-gray-700 [&>th]:p-2 cursor-pointer">
                <th wire:click="resort('id')">
                    <span data-tippy-content="Order by id">#</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'id' ? 'inline-block' : 'hidden'}}
                    "/>
                </th>
                <th wire:click="resort('courses_count')">
                    <span data-tippy-content="Order by # courses">Amount of courses</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'courses_count' ? 'inline-block' : 'hidden'}}
                        "/>
                </th>

                <th wire:click="resort('name')"
                    class="text-left">
                    <span data-tippy-content="Order by programme">Programme</span>
                    <x-heroicon-s-chevron-up
                        class="w-5 text-slate-400
                            {{$orderAsc ?: 'rotate-180'}}
                            {{$orderBy === 'name' ? 'inline-block' : 'hidden'}}
                        "/>
                </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($programmes as $programme)
                <tr wire:key="programme_{{$programme->id}}"
                    class="border-t border-gray-300 [&>td]:p-2">
                    <td>{{$programme->id}}</td>
                    <td>{{$programme->courses_count}}</td>
                    @if($editProgramme['id'] !== $programme->id)
                        <td
                            wire:click="editExistingProgramme({{ $programme->id }})"
                            class="text-left cursor-pointer">{{$programme->name}}
                        </td>
                    @else
                        <td>
                            <div class="flex flex-col text-left">
                                <div class="relative w-64">
                                    <x-jet-input id="edit_{{ $programme->id }}" type="text"
                                                 x-data=""
                                                 x-init="$el.focus()"
                                                 @keydown.enter="$el.setAttribute('disabled', true);"
                                                 @keydown.tab="$el.setAttribute('disabled', true);"
                                                 @keydown.esc="$el.setAttribute('disabled', true);"
                                                 wire:model.defer="editProgramme.name"
                                                 wire:keydown.enter="updateProgramme({{ $programme->id }})"
                                                 wire:keydown.tab="updateProgramme({{ $programme->id }})"
                                                 wire:keydown.escape="resetEditProgramme()"
                                                 class="w-full"/>
                                    <x-jet-input-error for="editProgramme.name" class="mt-2"/>
                                    <x-phosphor-arrows-clockwise
                                        wire:loading
                                        wire:target="editProgramme"
                                        class="w-5 h-5 text-gray-200 absolute top-3 right-2 animate-spin"/>
                                </div>
                            </div>
                        </td>
                    @endif
                    <td x-data="">
                        @if($editProgramme['id'] !== $programme->id)
                            <div
                                class="flex gap-1 justify-center [&>*]:cursor-pointer [&>*]:outline-0 [&>*]:transition">
                                <x-phosphor-pencil-line-duotone
                                    wire:click="editExistingProgramme({{ $programme->id }})"
                                    class="w-5 text-gray-300 hover:text-green-600"/>
                                <x-phosphor-book-duotone
                                    wire:click="viewProgrammeAndItCourses({{$programme->id}})"
                                    class="w-5 text-gray-300 hover:text-blue-600"/>
                                <x-phosphor-trash-duotone
                                    @click="$dispatch('swal:confirm', {
                                    title: 'Delete {{ $programme->name }}?',
                                    icon: '{{ $programme->courses_count > 0 ? 'warning' : '' }}',
                                    background: '{{ $programme->courses_count > 0 ? 'error' : '' }}',
                                    cancelButtonText: 'NO!',
                                    confirmButtonText: 'YES DELETE THIS PROGRAMME',
                                    html: '{{ $programme->courses_count > 0 ? '<b>ATTENTION</b>: you are going to delete <b>' . $programme->courses_count . ' ' . Str::plural('course', $programme->courses_count) . '</b> at the same time!' :'' }}',
                                    color: '{{ $programme->courses_count > 0 ? 'red' : '' }}',
                                    next: {
                                        event: 'delete-programme',
                                        params: {
                                            id: {{ $programme->id }}
                                        }
                                    }
                                });"
                                    class="w-5 text-gray-300 hover:text-red-600"/>
                            </div>
                    </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-sta.section>
    <x-jet-dialog-modal id="programmeModal"
                        wire:model="showModal">
        <x-slot name="title">
            @isset($selectedProgramme)
                <h2>{{$selectedProgramme->name}}</h2>
            @endisset

        </x-slot>

        <x-slot name="content">
            @if ($errors->any())
                <x-sta.alert type="danger">
                    <x-sta.list>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </x-sta.list>
                </x-sta.alert>
            @endif
            <h3>Courses</h3>
            @isset($selectedProgramme->courses)
                <div class="border-b border-gray-200 pt-4 pd-4">
                    @foreach($selectedProgramme->courses as $eachCourse)
                        <p data-tippy-content="{{$eachCourse->description}}">{{$eachCourse->name}}</p>
                    @endforeach
                </div>
            @endisset
            @isset($selectedProgramme)
                <h3>Add a course to the {{$selectedProgramme->name}} programme</h3>
            @endisset
            <x-jet-label for="name" value="Name"/>
            <div class="flex flex-row gap-2 mt-2">
                <x-jet-input id="name" type="text" placeholder="Enter course name"
                             wire:model.defer="newCourse.name"
                             class="flex-1"/>
            </div>
            <x-jet-label for="description" value="Description"/>
            <div class="flex flex-row gap-2 mt-2">
                <x-jet-input id="description" type="text" placeholder="Enter course description"
                             wire:model.defer="newCourse.description"
                             class="flex-1"/>
            </div>

        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button
                @click="show = false">Cancel
            </x-jet-secondary-button>
            <x-jet-button
                class="ml-2"
                wire:click="createCourse()">Add new course
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
