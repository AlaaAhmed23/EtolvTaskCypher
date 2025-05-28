<?php

namespace App\Services;

use App\Interfaces\SchoolRepositoryInterface;

class SchoolService
{
    protected $schoolRepository;

    public function __construct(SchoolRepositoryInterface $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    public function getAll()
    {
        return $this->schoolRepository->getAll();
    }
    

    public function getById($id)
    {
        return $this->schoolRepository->getById($id);
    }

    public function create(array $data)
    {
        return $this->schoolRepository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->schoolRepository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->schoolRepository->delete($id);
    }

    public function getSchoolByStudentId($Sid)
    {
        return $this->schoolRepository->getSchoolByStudentId($Sid);
    }
    
    
}