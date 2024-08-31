<?php

class QueryBuilder {
    #private object $pdo;
    private string $query;
    private array $bindings = [];
    
    public function __construct(array $config) {
        $dsn = $config['type'] . ':host=' . $config['host'] . ';dbname=' . $config['dbname'];
        # тут должен быть нью PDO($dsn, $config['user'], $config['password']), но пхпизе ругается(;
    }

    public function select($columns = '*') : QueryBuilder {
        $this->query = "SELECT " . implode(', ', (array)$columns) . " ";
        return $this;
    }

    public function from(string $table) : QueryBuilder {
        $this->query .= "FROM " . $table . " ";
        return $this;
    }

    public function where(string $column, string $operator, string $value) : QueryBuilder {
        $this->query .= "WHERE " . $column . " " . $operator . " ? ";
        $this->bindings[] = $value;
        return $this;
    }

    public function limit(string $limit) : QueryBuilder {
        $this->query .= "LIMIT " . $limit . " ";
        return $this;
    }

    public function insert(string $table, array $data) : QueryBuilder {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $this->query = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $placeholders . ")";
        $this->bindings = array_values($data);
        return $this;
    }

    public function update(string $table, array $data) : QueryBuilder {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $this->bindings[] = $value;
        }
        $this->query = "UPDATE " . $table . " SET " . implode(', ', $set) . " ";
        return $this;
    }

    public function delete(string $table) : QueryBuilder {
        $this->query = "DELETE FROM " . $table . " ";
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC') : QueryBuilder {
        $this->query .= "ORDER BY " . $column . " " . $direction . " ";
        return $this;
    }

    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER') : QueryBuilder {
        $this->query .= $type . " JOIN " . $table . " ON " . $first . " " . $operator . " " . $second . " ";
        return $this;
    }

    public function execute() : array {
        global $pdo;
        $stmt = $pdo->prepare($this->query);
        $stmt->execute($this->bindings);
        $this->bindings = [];
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}