<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use Laudis\Neo4j\Client;

class StudentRepository implements StudentRepositoryInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll()
    {
        $result = $this->client->run("MATCH (s:Student) RETURN COLLECT(s{.*, id: id(s)}) AS students");
        $Outputefinal = $result->first()->toRecursiveArray()['students'];
        return $Outputefinal;
    }
    public function getById($id)
    {
        $id = (int) $id;
        $query = <<<CYPHER
        MATCH (s:Student) WHERE ID(s) = $id
        OPTIONAL MATCH (s)-[:ENROLLED_IN]->(school:School)
        OPTIONAL MATCH (s)-[:STUDIES]->(subject:Subject)
        RETURN s, school, collect(subject) AS subjects
        CYPHER;

        $result = $this->client->run($query, ['id' => $id]);
        if ($result->isEmpty()) {
            return null;
        }
       
        $record = $result->first();
        $studentNode = $record->get('s');
        $schoolNode = $record->get('school');
        $subjectsList = $record->get('subjects');
        return [
            'id' => $id,
            'name' => $studentNode->getProperty('name'),
            'school' => $schoolNode ? [
                'id' => $schoolNode->toArray()['id'],
                'name' => $schoolNode->getProperty('name')
            ] : null,
            'subjects' => array_map(function ($subjectNode) {
                return [
                    'id' => $subjectNode->getId(),
                    'name' => $subjectNode->getProperty('name')
                ];
            }, $subjectsList->toArray())
        ];
    }

    public function find(int $id)
    {
        $id = (int) $id;
        $query = <<<CYPHER
        MATCH (s:Student)
        WHERE ID(s) = \$id
        OPTIONAL MATCH (s)-[:ENROLLED_IN]->(school:School)
        OPTIONAL MATCH (s)-[:STUDIES]->(subject:Subject)
        RETURN s{.*, id: ID(s)} AS student, 
            ID(school) AS school_id,
            collect(ID(subject)) AS subject_ids
        CYPHER;

        $result = $this->client->run($query, ['id' => $id]); 
        if ($result->count() === 0) {
            return null;
        }
        $student = $result->first()->get('student')->toArray();
        $student['school_id'] = $result->first()->get('school_id');
        $student['subject_ids'] = $result->first()->get('subject_ids')->toArray();
        return $student;
    }

    public function create(array $data)
    {
        $data['school_id'] = (int) $data['school_id'];
        $data['subject_ids'] = array_map('intval', $data['subject_ids']);
        $result = $this->client->run(
            'CREATE (s:Student {name: $name})
             WITH s, $school_id as school_id, $subject_ids as subject_ids
             MATCH (school:School)
             WHERE ID(school) = school_id
             CREATE (s)-[:ENROLLED_IN]->(school)
             WITH s, subject_ids
             UNWIND subject_ids as subject_id
             MATCH (sub:Subject)
             WHERE ID(sub) = subject_id
             CREATE (s)-[:STUDIES]->(sub)
             RETURN ID(s) as id',
            [
                'name' => $data['name'],
                'school_id' => $data['school_id'],
                'subject_ids' => !empty($data['subject_ids']) ? $data['subject_ids'] : []
            ]
        );
        
        return ['id' => (int) $result->first()->get('id')];
    }
    
    public function update(int $id, array $data)
    {
        $id = (int) $id;
        $data['school_id'] = (int) $data['school_id'];        
        $data['subject_ids'] = array_map('intval', $data['subject_ids']);

        $this->client->run("MATCH (s:Student) 
            WHERE ID(s) = $id
            SET s.name = \$name
            WITH s, \$schoolId AS schoolId
            OPTIONAL MATCH (s)-[oldSchoolRel:ENROLLED_IN]->()
            DELETE oldSchoolRel
            WITH s, schoolId
            WHERE schoolId IS NOT NULL
            MATCH (school:School)
            WHERE ID(school) = schoolId
            MERGE (s)-[:ENROLLED_IN]->(school)
            
            WITH s, \$subjectIds AS subjectIds
            OPTIONAL MATCH (s)-[oldSubjectRel:STUDIES]->()
            DELETE oldSubjectRel
            WITH s, subjectIds
            WHERE size(subjectIds) > 0
            UNWIND subjectIds AS subjectId
            MATCH (subject:Subject)
            WHERE ID(subject) = subjectId
            // Using MERGE instead of CREATE to prevent duplicates
            MERGE (s)-[:STUDIES]->(subject)
            RETURN s, ID(s) AS id", [
                    'id' => $id,
                    'name' => (string)$data['name'],
                    'schoolId' => $data['school_id'],
                    'subjectIds' => $data['subject_ids']
                ]);
       
        return $this->find($id);
    }
    
    public function delete(int $id)
    {
        return $this->client->run(
            "MATCH (s:Student) WHERE ID(s) = \$id
             DETACH DELETE s",
            ['id' => (int) $id]
        );
    }

    public function paginate(int $perPage = 5, int $page = 1, array $filters = [])
    {
        $skip = ($page - 1) * $perPage;
        $query = <<<CYPHER
        MATCH (s:Student)
        WHERE s.name CONTAINS \$search
        OPTIONAL MATCH (s)-[:ENROLLED_IN]->(school:School)
        OPTIONAL MATCH (s)-[:STUDIES]->(subject:Subject)
        WITH s, school, collect(subject) AS subjects
        RETURN s{.*, id: id(s)}, school{.*, id: id(school)}, subjects
        SKIP \$skip
        LIMIT \$limit
        CYPHER;
        
        $parameters = [
            'search' => $filters['search'] ?? '',
            'skip' => $skip,
            'limit' => $perPage
        ];
            
        $result = $this->client->run($query, $parameters);
        $totalQuery = <<<CYPHER
        MATCH (s:Student)
        WHERE s.name CONTAINS \$search
        RETURN count(s) as total
        CYPHER;
        
        $totalResult = $this->client->run($totalQuery, ['search' => $parameters['search']]);
        $total = $totalResult->first()->get('total');
        
        $students = collect($result)->map(function ($record) {
            $student = $record->get('s')->toArray();
            $school = $record->get('school') ? $record->get('school')->toArray() : null;
            $subjects = $record->get('subjects')->toArray();
            
            return [
                'id' => $student['id'],
                'name' => $student['name'],
                'school' => $school ? [
                    'id' => $school['id'],
                    'name' => $school['name']
                ] : null,
                'subjects' => array_map(function ($subject) {
                    return [
                        'id' => $subject->getId(),
                        'name' => $subject->getProperty('name')
                ];
                }, $subjects)
            ];
        });
        
        return [
            'data' => $students,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page
        ];
    }
    public function getStudentsWithSubjects(): array
    {
        $query = <<<CYPHER
        MATCH (s:Student)-[:STUDIES]->(sub:Subject)
        WITH s, collect(sub { .id, .name }) AS subjects
        ORDER BY s.name
        RETURN {
            id: s.id,
            name: s.name,
            subjects: subjects
        } AS student
        CYPHER;
    
        $result = $this->client->run($query);
        return array_map(fn($record) => $record->get('student'), $result->toArray());
    }
    public function getStudentsWithSubjectsAndSchool($schoolFilter = null, $subjectFilter = null)
    {
        $query = <<<CYPHER
        MATCH (s:Student)
        OPTIONAL MATCH (s)-[:ENROLLED_IN]->(school:School)
        OPTIONAL MATCH (s)-[:STUDIES]->(sub:Subject)
        WITH s, school, collect(DISTINCT sub) AS allSubjects
        WHERE 
            (\$school IS NULL OR school.name = \$school) AND
            (\$subject IS NULL OR ANY(sub IN allSubjects WHERE sub.name = \$subject))
        WITH s, school, [sub IN allSubjects | {name: sub.name}] AS subjects
        RETURN {
            name: s.name,
            school: { name: school.name },
            subjects: subjects
        } AS student
        ORDER BY s.name
        CYPHER;

        $result = $this->client->run($query, [
            'school' => $schoolFilter,
            'subject' => $subjectFilter,
        ]);

        return array_map(fn($record) => $record->get('student'), $result->toArray());
    }



}
