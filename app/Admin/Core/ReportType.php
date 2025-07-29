<?php
namespace App\Admin\Core;

enum ReportType: string
{
    case DAILY_SALES = 'DAILY_SALES';
    case MONTHLY_REVENUE = 'MONTHLY_REVENUE';
    case INVENTORY_STATUS = 'INVENTORY_STATUS';
    case USER_ACTIVITY = 'USER_ACTIVITY';
    case ORDER_SUMMARY = 'ORDER_SUMMARY';
    case FINANCE_STATEMENT = 'FINANCE_STATEMENT';
}
