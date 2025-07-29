<?php
namespace App\Admin\CustomerService;

class Feedback
{
    public int $id;
    public ?int $customerId;
    public ?string $jenis;
    public ?string $content;
    public ?int $rating;
    public ?string $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->customerId = $data['customer_id'] ?? null;
        $this->jenis = $data['jenis'] ?? null;
        $this->content = $data['content'] ?? null;
        $this->rating = isset($data['rating']) ? (int)$data['rating'] : null;
        $this->status = $data['status'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
