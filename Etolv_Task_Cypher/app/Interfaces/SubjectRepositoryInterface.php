<?php

namespace App\Interfaces;

interface SubjectRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getSubjectByStudentId($studentId);
}