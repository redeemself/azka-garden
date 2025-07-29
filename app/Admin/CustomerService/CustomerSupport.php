<?php
namespace App\Admin\CustomerService;

class CustomerSupport
{
    public int $id;
    public int $customerId;
    public ?string $ticketNumber;
    public ?string $category;
    public ?string $subject;
    public ?string $description;
    public ?string $status;
    public ?string $priority;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->customerId = $data['customer_id'];
        $this->ticketNumber = $data['ticket_number'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->subject = $data['subject'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->priority = $data['priority'] ?? null;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
