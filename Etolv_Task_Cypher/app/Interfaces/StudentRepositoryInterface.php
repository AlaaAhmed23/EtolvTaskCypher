<?php

namespace App\Interfaces;

interface StudentRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function paginate(int $perPage = 5, int $page = 1, array $filters = []);
    public function getStudentsWithSubjects(): array;
    public function getStudentsWithSubjectsAndSchool($schoolFilter = null, $subjectFilter = null);
}