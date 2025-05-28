<?php

namespace App\Interfaces;

interface SchoolRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function find(int $id);
    public function getSchoolByStudentId($studentId);
}