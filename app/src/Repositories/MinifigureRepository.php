<?php
namespace App\Repositories;
use App\Config;
use App\Models\MiniFigure;
use PDO;
use PDOException;

class MiniFigureRepository implements IMiniFigureRepository
{
    private PDO $connection;
    public function __construct()
    {
        $this->connection = $this->createConnection();
    }

    private function createConnection(): PDO
    {
        $dsn = 'mysql:host=' . Config::DB_SERVER_NAME .
               ';dbname=' . Config::DB_NAME .
               ';charset=utf8mb4';
        try {
            $pdo = new PDO(
                $dsn,
                Config::DB_USERNAME,
                Config::DB_PASSWORD
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    /** @return MiniFigure[] */
    public function getAll(): array
    {
        $sql = '
            SELECT
                m.id,
                m.name,
                m.priceCents,
                m.imageUrl,
                m.description,
                c.name AS categoryName
            FROM minifigures m
            LEFT JOIN categories c ON m.categoryId = c.id
            ORDER BY m.name
        ';

        $statement = $this->connection->query($sql);
        $rows = $statement->fetchAll(); // array of associative arrays

        $result = [];

        foreach ($rows as $row) {
            $result[] = new Minifigure(
                (int)$row['id'],
                (string)$row['name'],
                (int)$row['priceCents'],
                (string)$row['categoryName'],              // goes into $category
                (string)($row['imageUrl'] ?? ''),          // fallback empty string
                (string)($row['description'] ?? '')
            );
        }

        return $result;
    }

    
    public function getById(int $id): ?MiniFigure
    {
       $sql = '
            SELECT
                m.id,
                m.name,
                m.priceCents,
                m.imageUrl,
                m.description,
                c.name AS categoryName
            FROM minifigures m
            LEFT JOIN categories c ON m.categoryId = c.id
            WHERE m.id = :id
            LIMIT 1
        ';

        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

        return new Minifigure(
            (int)$row['id'],
            (string)$row['name'],
            (int)$row['priceCents'],
            (string)$row['categoryName'],
            (string)($row['imageUrl'] ?? ''),
            (string)($row['description'] ?? '')
        );
    }

    public function create(string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): int
    {
        $sql = 'INSERT INTO minifigures (name, priceCents, categoryId, imageUrl, description) VALUES (:name, :priceCents, :categoryId, :imageUrl, :description)';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'priceCents' => $priceCents,
            'categoryId' => $categoryId,
            'imageUrl' => $imageUrl,
            'description' => $description
        ]);
        return (int)$this->connection->lastInsertId();
    }

    public function update(int $id, string $name, int $priceCents, int $categoryId, string $imageUrl, string $description): bool
    {
        $sql = 'UPDATE minifigures SET name = :name, priceCents = :priceCents, categoryId = :categoryId, imageUrl = :imageUrl, description = :description WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'priceCents' => $priceCents,
            'categoryId' => $categoryId,
            'imageUrl' => $imageUrl,
            'description' => $description
        ]);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM minifigures WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() > 0;
    }
}

