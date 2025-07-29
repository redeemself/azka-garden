<?php
namespace App\Admin\CustomerService;

class FAQ
{
    public int $idFaq;               // sesuai UML: idFaq
    public ?string $category;
    public ?string $question;
    public ?string $answer;
    public bool $status;
    public ?int $order;              // urutan di UML bernama 'order', di PHP sebaiknya 'order' jangan 'urutan' agar konsisten
    public ?\DateTime $createdAt;
    public int $interfaceId;

    public function __construct(array $data = [])
    {
        $this->idFaq = $data['idFaq'] ?? 0;
        $this->category = $data['category'] ?? null;
        $this->question = $data['question'] ?? null;
        $this->answer = $data['answer'] ?? null;
        $this->status = isset($data['status']) ? (bool)$data['status'] : true;
        $this->order = $data['order'] ?? null;
        $this->createdAt = isset($data['created_at']) ? new \DateTime($data['created_at']) : new \DateTime();
        $this->interfaceId = $data['interface_id'] ?? 8;
    }

    // Method signatures sesuai UML (tanpa isi method)
    public function createFAQ(): void {}
    public function updateFAQ(): void {}
    public function deleteFAQ(): void {}
    public function reorderFAQ(): void {}
}
