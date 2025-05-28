<?php
namespace App\Services;

use App\Interfaces\StudentRepositoryInterface;

class StudentService
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function getAll()
    {
        return $this->studentRepository->getAll();
    }
    

    public function getById($id)
    {
        return $this->studentRepository->getById($id);
    }

    public function find($id)
    {
        return $this->studentRepository->find($id);
    }

    public function create(array $data)
    {
        return $this->studentRepository->create($data);
    }

    public function update( $id, array $data)
    {
        return $this->studentRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->studentRepository->delete($id);
    }
    public function paginate(array $data)
    {
        return $this->studentRepository->paginate(
            $data['per_page'] ?? 5,
            $data['page'] ?? 1,
            $data['filters'] ?? []
        );
    }
    public function getStudentsWithSubjects(): array
    {
        return $this->studentRepository->getStudentsWithSubjects();
    }

    public function getStudentsWithSubjectsAndSchool($schoolFilter, $subjectFilter)
    {
        return $this->studentRepository->getStudentsWithSubjectsAndSchool($schoolFilter, $subjectFilter);
    }

}