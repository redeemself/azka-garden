<?php
namespace App\Admin\ContentManagement;

class Promotion
{
    public int $id;
    public ?string $promoCode;
    public ?string $title;
    public ?string $description;
    public ?string $discountType;
    public ?float $discountValue;
    public ?\DateTime $startDate;
    public ?\DateTime $endDate;
    public bool $status;
    public \DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->promoCode = $data['promo_code'] ?? null;
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->discountType = $data['discount_type'] ?? null;
        $this->discountValue = isset($data['discount_value']) ? (float)$data['discount_value'] : null;
        $this->startDate = isset($data['start_date']) ? new \DateTime($data['start_date']) : null;
        $this->endDate = isset($data['end_date']) ? new \DateTime($data['end_date']) : null;
        $this->status = $data['status'] ?? true;
        $this->createdAt = new \DateTime($data['created_at'] ?? 'now');
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
