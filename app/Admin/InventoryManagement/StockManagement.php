<?php
namespace App\Admin\InventoryManagement;

class StockManagement
{
    public int $id;
    public int $productId;
    public int $quantity;
    public ?string $type;
    public ?string $notes;
    public ?int $createdBy;
    public int $interfaceId;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->productId = $data['product_id'];
        $this->quantity = $data['quantity'];
        $this->type = $data['type'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->createdBy = $data['created_by'] ?? null;
        $this->interfaceId = $data['interface_id'] ?? 8;
    }
}
