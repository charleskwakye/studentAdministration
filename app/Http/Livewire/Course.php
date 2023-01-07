<?php

namespace App\Http\Livewire;

use App\Models\Programme;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Course extends Component
{
    use WithPagination;

    //public properties
    public $perPage = 6;
    public $loading = "Loading";
    public $programme = '%';
    public $courseNameOrDescription;
    public $selectedCourse;
    public $showModal = false;



    public function showStudents(\App\Models\Course $course)
    {
        $this->selectedCourse = $course;
        $this->showModal = true;
        //$studentsEnrolled = $this->selectedCourse->student_courses->toArray();
        //@dump($this->selectedCourse->student_courses->toArray());

    }

    public function updated($propertyName, $propertyValue)
    {
        // dd($propertyName, $propertyValue);
        if (in_array($propertyName, ['perPage', 'courseNameOrDescription', 'programme']))
            $this->resetPage();
    }


    public function render()
    {
        $courses = \App\Models\Course::orderBy('name')
            ->searchCourseNameOrProgramme($this->courseNameOrDescription)
            ->where('programme_id', 'like', "$this->programme")
            ->paginate($this->perPage);
        $programmes = Programme::orderBy('name')->get();
        $students = Student::orderBy('id')->get();

        return view('livewire.course', compact('courses', 'programmes','students'))
            ->layout('layouts.studentadministration', [
                'description' => 'Course',
                'title' => 'Course'
            ]);
    }
}
