<?php

namespace App\Services;

use App\Interfaces\SubjectRepositoryInterface;

class SubjectService
{
    protected $subjectRepository;

    public function __construct(SubjectRepositoryInterface $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getAll()
    {
        return $this->subjectRepository->getAll();
    }
    public function find($id)
    {
        return $this->subjectRepository->find($id);
    }
    public function getById($id)
    {
        return $this->subjectRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->subjectRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->subjectRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->subjectRepository->delete($id);
    }

    public function getSubjectByStudentId($studentId)
    {
        return $this->subjectRepository->getSubjectByStudentId($studentId);
    }
}