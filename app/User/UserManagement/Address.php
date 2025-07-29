<?php
namespace App\User\UserManagement;

class Address
{
    private int $id;
    private int $userId;
    private string $label;
    private string $recipient;
    private string $phoneNumber;
    private string $fullAddress;
    private string $city;
    private string $zipCode;
    private bool $isPrimary;

    public function __construct(
        int $id,
        int $userId,
        string $label,
        string $recipient,
        string $phoneNumber,
        string $fullAddress,
        string $city,
        string $zipCode,
        bool $isPrimary = false
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->label = $label;
        $this->recipient = $recipient;
        $this->phoneNumber = $phoneNumber;
        $this->fullAddress = $fullAddress;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->isPrimary = $isPrimary;
    }

    // Add getters and setters as needed
}
