<?php

namespace App\Repositories;

use Illuminate\Support\Facades\App;
use App\Interfaces\SchoolRepositoryInterface;
class SchoolRepository implements SchoolRepositoryInterface
{
    protected $client;

    public function __construct()
    {
    $this->client = App::make('Neo4jClient');
    }

    public function getById($id)
    {
        $query = 'MATCH (s:School) WHERE ID(s) = $id RETURN s{.*, id: ID(s)} AS school';
        $result = $this->client->run($query, ['id' => (int) $id]);
        if ($result->count() === 0) {
            return null;
        }
        return $result->first()->get('school')->toArray();
    }
    

    public function create(array $data)
    {
        $query = "CREATE (s:School {name: \$name}) RETURN s{.*, id: ID(s)} AS newSchool";
        $result = $this->client->run($query, ['name' => $data['name']]);
        return $result->first()->get('newSchool')->toArray();
    }


    public function find(int $id)
    {
        $query = 'MATCH (s:School) 
                WHERE ID(s) = \$id  
                RETURN s';
        $result = $this->client->run($query, ['id' => $id]);
        return $result->first()?->get('s')->getProperties();
    }
    
    public function getAll()
    {
        $query = "MATCH (s:School) RETURN collect(s{.*,id:ID(s)}) As schools ";
        $result = $this->client->run($query);
        return $result->first()->toRecursiveArray()['schools'];
    }

    public function update(int $id, array $data)
    {
        $query = 'MATCH (s:School) WHERE ID(s) = $id SET s.name = $name RETURN s{.*, id: ID(s)} AS school';
        $result = $this->client->run($query, [
            'id' =>  $id,
            'name' => $data['name']
        ]);
        if ($result->count() === 0) {
            return null;
        }
        return $result->first()->get('school')->toArray();
    }

    public function getSchoolByStudentId($studentId)
    {
        $query = 'MATCH (s:Student)-[:ENROLLED_IN]->(school:School)
                  WHERE ID(s) = $id
                  RETURN school';
        $result = $this->client->run($query, ['id' => $studentId]);

        foreach ($result as $record) {
            return $record->get('school')->toArray();
        }

        return null;
    }
    public function delete(int $id)
    {
        $this->client->run(
            'MATCH (s:School) WHERE ID(s) = $id DETACH DELETE s',
            ['id' => $id]
        );
    }

}

?>