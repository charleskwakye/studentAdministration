<?php

namespace App\Http\Livewire\Admin;

//use App\Http\Livewire\Course;
use App\Models\Course;
use App\Models\Programme;
use Livewire\Component;

class Programmes extends Component
{
    public $courses;
    public $showModal = false;
    public $newProgramme;
    public $editProgramme = ['id' => null, 'name' => null];

    public $selectedProgramme;


    public $newCourse = [
        'id' => null,
        'programme_id' => null,
        'name' => null,
        'description' => null,
    ];

    //reset $newProgramme and validation
    public function resetNewCourse(){
        $this->reset('newCourse');
        $this->resetErrorBag();
    }

    public function viewProgrammeAndItCourses(Programme $programme){
        $this->resetErrorBag();
        $this->selectedProgramme = $programme;
        $this->showModal=true;
        //$this->selectedProgramme->toArray();
        //dump($this->selectedProgramme->name);
    }

    public function createCourse()
    {
        $this->validateOnly('newCourse.name');
        $this->validateOnly('newCourse.description');
        $course = Course::create([
            'name' => $this->newCourse['name'],
            'description' => $this->newCourse['description'],
            'programme_id' => $this->selectedProgramme['id'],
        ]);
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The course <b><i>{$course->name}</i></b> has been added to the programme <b><i>{$course->programme['name']}</i></b>",
        ]);
        //refresh the courses
        $this->selectedProgramme->refresh();
        $this->resetNewCourse();
    }


    // get all the genres from the database (runs only once)
    public function mount()
    {
        $this->courses = Course::orderBy('name')->get();
    }

    // validation rules
    public function rules()
    {
        return [
            'newProgramme' => 'required|min:3|max:30|unique:programmes,name',
            'editProgramme.name' => 'required|min:3|max:30|unique:programmes,name,' . $this->editProgramme['id'],
            'newCourse.name' => 'required|min:3|max:30|unique:courses,name',
            'newCourse.description' => 'required|min:6|max:110',
        ];
    }


// custom validation messages
    protected $messages = [
        'newProgramme.required' => 'Please enter a programme name.',
        'newProgramme.min' => 'The new name must contains at least 3 characters and no more than 30 characters.',
        'newProgramme.max' => 'The new name must contains at least 3 characters and no more than 30 characters.',
        'newProgramme.unique' => 'This name already exists.',
        'editProgramme.name.required' => 'Please enter a programme name.',
        'editProgramme.name.min' => 'This name is too short (must be between 3 and 30 characters).',
        'editProgramme.name.max' => 'This name is too long (must be between 3 and 30 characters)',
        'editProgramme.name.unique' => 'This name is already in use.',
        'newCourse.name.required' => 'Please enter a course name.',
        'newCourse.name.min'=> 'The new name must contains at least 3 characters and no more than 30 characters.',
        'newCourse.name.max' => 'The new name must contains at least 3 characters and no more than 30 characters.',
        'newCourse.name.unique' => 'This name already exists.',

        'newCourse.description.required' => 'Please enter a course description.',
        'newCourse.description.min'=> 'The description must contains at least 3 characters and no more than 30 characters.',
        'newCourse.description.max' => 'The description name must contains at least 3 characters and no more than 30 characters.',
    ];

    //sort properties
    public $orderBy = 'name';
    public $orderAsc = true;

    // resort the programmes by the given column
    public function resort($column)
    {
        if ($this->orderBy === $column) {
            $this->orderAsc = !$this->orderAsc;
        } else {
            $this->orderAsc = true;
        }
        $this->orderBy = $column;
    }

    // create a new Programme
    public function createProgramme()
    {
        // validate the new programme name
        $this->validateOnly('newProgramme');
        // create the programme
        $programme = Programme::create([
            'name' => trim($this->newProgramme),
        ]);
        //reset $newProgramme
        $this->resetNewProgramme();
        // toast
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The programme <b><i>{$programme->name}</i></b> has been added",
        ]);
    }


    // update an existing programme
    public function updateProgramme(Programme $programme)
    {
        $oldName = $programme->name;
        $newName = trim($this->editProgramme['name']);
        if ($oldName != $newName){
            $this->validateOnly('editProgramme.name');
            $programme->update([
                'name' => trim($this->editProgramme['name']),
            ]);
            $this->dispatchBrowserEvent('swal:toast', [
                'background' => 'success',
                'html' => "The programme <b><i>{$oldName}</i></b> has been updated to <b><i>{$programme->name}</i></b>",
            ]);
        }
        $this->resetEditProgramme();
    }



    // edit the value of $editProgramme (show inlined edit form)
    public function editExistingProgramme(Programme $programme)
    {
        $this->editProgramme = [
            'id' => $programme->id,
            'name' => $programme->name,
        ];
    }


    //reset $newProgramme and validation
    public function resetNewProgramme()
    {
        $this->reset('newProgramme');
        $this->resetErrorBag();
    }


    // reset $editProgramme and validation
    public function resetEditProgramme()
    {
        $this->reset('editProgramme');
        $this->resetErrorBag();
    }

    // delete a genre
    public function deleteProgramme(Programme $programme)
    {
        $programme->delete();
        $this->dispatchBrowserEvent('swal:toast', [
            'background' => 'success',
            'html' => "The programme <b><i>{$programme->name}</i></b> has been deleted",
        ]);
    }

    // listen to the delete-genre event
    protected $listeners = [
        'delete-programme' => 'deleteProgramme',
    ];


    public function render()
    {
        $programmes = Programme::withCount('courses')
            ->orderBy($this->orderBy,$this->orderAsc ? 'asc':'desc')
            ->get();
        $courses = Course::orderBy('id')->get();
        return view('livewire.admin.programmes', compact('programmes','courses'))
            ->layout('layouts.studentadministration', [
                'description' => 'Manage the programmes of your courses',
                'title' => 'Programmes',
            ]);
    }
}
