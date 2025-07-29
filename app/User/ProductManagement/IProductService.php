<?php
namespace App\User\ProductManagement;

interface IProductService
{
    public function listProducts(array $filters = []): array;
    public function getProduct(int $id): ?Product;
    public function createProduct(array $data): Product;
    public function updateProduct(int $id, array $data): bool;
    public function deleteProduct(int $id): bool;
}
