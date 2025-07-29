<?php
namespace App\Admin\Core;

enum AdminRole: string
{
    case SUPER_ADMIN = 'SUPER_ADMIN';
    case PRODUCT_ADMIN = 'PRODUCT_ADMIN';
    case ORDER_ADMIN = 'ORDER_ADMIN';
    case CUSTOMER_SERVICE = 'CUSTOMER_SERVICE';
    case CONTENT_ADMIN = 'CONTENT_ADMIN';
    case FINANCE_ADMIN = 'FINANCE_ADMIN';
}
