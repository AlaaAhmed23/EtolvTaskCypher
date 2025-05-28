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
        $record = $result->first();
        $student = $record->get('student')->toArray();
        $student['school_id'] = $record->get('school_id');
        $student['subject_ids'] = $record->get('subject_ids')->toArray();
        return $student;
    }

    public function create(array $data)
    {
        $data['school_id'] = (int) $data['school_id'];
        $data['subject_ids'] = array_map('intval', $data['subject_ids']);
        $id = $this->client->run(
            'CREATE (s:Student {name: $name}) Return ID(s) as id',
            [ 'name' => $data['name']] 
        )->first()->get("id");
        $id = (int)$id;
        // Create ENROLLED_IN relationship
        $this->client->run(
            'MATCH (s:Student), (school:School) 
            where ID(s) = $id and ID(school)= $school_id
            CREATE (s)-[:ENROLLED_IN]->(school)',
            [
                'id' => $id,
                'school_id' => $data['school_id']
            ]
            
        );
        // Create STUDIES relationships
        if (!empty($data['subject_ids']) && is_array($data['subject_ids'])) {
                $this->client->run(
                    'MATCH (s:Student), (sub:Subject )
                    where ID(s) = $id and ID(sub) in $subject_ids
                    CREATE (s)-[:STUDIES]->(sub)',
                    [
                        'id' => $id,
                        'subject_ids' => $data['subject_ids']
                    ]
                );
        }
        return ['id' => $id];
    }

    public function update(int $id, array $data)
    {
        $id = (int) $id;
        $data['school_id'] = (int) $data['school_id'];        
        $data['subject_ids'] = array_map('intval', $data['subject_ids']);
        // Update student name
        $this->client->run(
            "MATCH (s:Student) WHERE ID(s) = \$id
            SET s.name = \$name",
            ['id' => $id, 'name' => (string) $data['name']] 
        );
        // Remove old ENROLLED_IN relationship
        $this->client->run(
            "MATCH (s:Student)-[r:ENROLLED_IN]->()
            WHERE ID(s) = \$id
            DELETE r",
            ['id' => $id]
        );
        // Create new ENROLLED_IN relationship
        if (!empty($data['school_id'])) {
            $this->client->run(
                "MATCH (s:Student), (school:School)
                WHERE ID(s) = \$studentId AND ID(school) = \$schoolId
                CREATE (s)-[:ENROLLED_IN]->(school)",
                [
                    'studentId' => $id,
                    'schoolId' => $data['school_id']  
                ]
            );
        }
        // Remove old STUDIES relationships
        $this->client->run(
            "MATCH (s:Student)-[r:STUDIES]->()
            WHERE ID(s) = \$id
            DELETE r",
            ['id' => $id]
        );
        // Create new STUDIES relationships
        if (!empty($data['subject_ids'])) {
            foreach ($data['subject_ids'] as $subjectId) {
                $this->client->run(
                    "MATCH (s:Student), (subject:Subject)
                    WHERE ID(s) = \$studentId AND ID(subject) = \$subjectId
                    CREATE (s)-[:STUDIES]->(subject)",
                    [
                        'studentId' => $id,
                        'subjectId' => $subjectId
                    ]
                );
            }
        }
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