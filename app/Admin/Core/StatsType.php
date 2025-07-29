<?php
namespace App\Admin\Core;

enum StatsType: string
{
    case SALES = 'SALES';
    case ORDERS = 'ORDERS';
    case CUSTOMERS = 'CUSTOMERS';
    case PRODUCTS = 'PRODUCTS';
    case REVENUE = 'REVENUE';
    case INVENTORY = 'INVENTORY';
}
