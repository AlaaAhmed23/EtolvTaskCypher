<?php

namespace App\Repositories;

use App\Interfaces\SubjectRepositoryInterface;
use Laudis\Neo4j\Client;

class SubjectRepository implements SubjectRepositoryInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll()
    {
        $result = $this->client->run("MATCH (s:Subject) RETURN COLLECT(s{.*, id: id(s)}) AS subjects");
        if ($result->count() === 0) {
            return null;
        }
        
        $Outputefinal = $result->first()->toRecursiveArray()['subjects'];
        return $Outputefinal;
    }
    public function getById($id)
    {
        $query = 'MATCH (s:Subject {id: $id}) RETURN s LIMIT 1';
        $result = $this->client->run($query, ['id' => $id]);

        if ($record = $result->first()) {
            return $record->get('s')->getProperties();
        }

        return null;
    }

    public function find(int $id)
    {
        $result = $this->client->run(
            "MATCH (s:Subject) WHERE ID(s) = \$id RETURN s{.*, id: ID(s)} AS subject",
            ['id' => (int) $id]
        );

        if ($result->count() === 0) {
            return null;
        }
    
        return $result->first()->get('subject')->toArray();
    }

    public function create(array $data)
    {
        $record = $this->client->run(
            "CREATE (s:Subject {name: \$name}) RETURN s, ID(s) as id",
            ['name' => $data['name']]
        )->first();
        return $record->get('s');
    }

    public function update(int $id, array $data)
    {
        $this->client->run(
            "MATCH (s:Subject) WHERE ID(s) = \$id SET s.name = \$name",
            ['id' => $id, 'name' => $data['name']]
        );
        return $this->find($id);
    }
    public function getSubjectByStudentId($studentId)
    {
        $query = 'MATCH (s:Student)-[:STUDIES]->(subject:Subject)
        WHERE ID(s) = \$id
        RETURN subject';
        $result = $this->client->run($query, ['id' => $studentId]);

        return iterator_to_array(
            (function () use ($result) {
                foreach ($result as $record) {
                    yield $record->get('subject')->toArray();
                }
            })()
        );
    }
        
    public function delete(int $id)
    {
        return $this->client->run(
            "MATCH (s:Subject) WHERE ID(s) = \$id DETACH DELETE s",
            ['id' => $id]
        );
    }
}