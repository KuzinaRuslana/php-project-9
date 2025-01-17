<?php

namespace Hexlet\Code\Repositories;

use Carbon\Carbon;

class ChecksRepository
{
    private \PDO $conn;

    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }

    public function addCheck(int $urlId, int $statusCode): void
    {
        $sql = 'INSERT INTO url_checks (url_id, status_code, created_at) VALUES (:url_id, :status_code, :created_at)';
        $stmt = $this->conn->prepare($sql);
        $date = Carbon::now();
        $stmt->execute([
            'url_id' => $urlId,
            'status_code' => $statusCode,
            'created_at' => $date,
        ]);
    }

    public function getChecks(int $urlId): array
    {
        $sql = 'SELECT * FROM url_checks WHERE url_id = :url_id ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['url_id' => $urlId]);
        return $stmt->fetchAll();
    }

    public function getLastCheckData(int $urlId): ?array
    {
        $sql = 'SELECT status_code, created_at FROM url_checks WHERE url_id = :url_id ORDER BY created_at DESC LIMIT 1';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['url_id' => $urlId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
}
