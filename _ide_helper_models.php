<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * Address Model
 * 
 * Last updated: 2025-07-30 07:31:30
 * Updated by: mulyadafa
 *
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $recipient
 * @property string $phone_number
 * @property string $full_address
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $postal_code
 * @property bool $is_primary
 * @property int $interface_id
 * @property float|null $latitude
 * @property float|null $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read InterfaceModel|null $interface
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereFullAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereRecipient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereZipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Address whereLongitude($value)
 * @mixin \Eloquent
 * @property string|null $address
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Address whereAddress($value)
 */
	class Address extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int    $id          <-- tambahkan baris ini
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int    $role_id
 * @property int    $status_id
 * @property int    $interface_id
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUsername($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $admin_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AdminLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $admin_id
 * @property string $module
 * @property bool $can_view
 * @property bool $can_create
 * @property bool $can_edit
 * @property bool $can_delete
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanCreate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanDelete($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanEdit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AdminPermission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_admin_role_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $admins
 * @property-read int|null $admins_count
 * @property-read \App\Models\EnumAdminRole $enumRole
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole whereEnumAdminRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminRole whereId($value)
 * @mixin \Eloquent
 */
	class AdminRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $admin_id
 * @property \Illuminate\Support\Carbon|null $login_time
 * @property \Illuminate\Support\Carbon|null $logout_time
 * @property int $interface_id
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminSession whereLogoutTime($value)
 * @mixin \Eloquent
 */
	class AdminSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_admin_status_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Admin> $admins
 * @property-read int|null $admins_count
 * @property-read \App\Models\EnumAdminStatus $enumStatus
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus whereEnumAdminStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminStatus whereId($value)
 * @mixin \Eloquent
 */
	class AdminStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $endpoint_id
 * @property string|null $version
 * @property string|null $content
 * @property array<array-key, mixed>|null $examples
 * @property int|null $updated_by
 * @property string $created_at
 * @property int $interface_id
 * @property-read ApiEndpoint $endpoint
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read Developer|null $updater
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereEndpointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereExamples($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiDocumentation whereVersion($value)
 * @mixin \Eloquent
 */
	class ApiDocumentation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $path
 * @property string $method
 * @property string|null $version
 * @property string|null $description
 * @property bool $auth_required
 * @property int|null $rate_limit
 * @property string $created_at
 * @property int $interface_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ApiDocumentation> $documentations
 * @property-read int|null $documentations_count
 * @property-read InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ApiMetric> $metrics
 * @property-read int|null $metrics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereAuthRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereRateLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiEndpoint whereVersion($value)
 * @mixin \Eloquent
 */
	class ApiEndpoint extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $endpoint_id
 * @property \Illuminate\Support\Carbon $timestamp
 * @property int $response_time
 * @property int $status_code
 * @property numeric|null $error_rate
 * @property int $interface_id
 * @property-read ApiEndpoint $endpoint
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereEndpointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereErrorRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereResponseTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiMetric whereTimestamp($value)
 * @mixin \Eloquent
 */
	class ApiMetric extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $recorded_by
 * @property string|null $action
 * @property string|null $details
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Admin|null $recorder
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuditLog whereRecordedBy($value)
 * @mixin \Eloquent
 */
	class AuditLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $title
 * @property string|null $image
 * @property string|null $link
 * @property string|null $position
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereTitle($value)
 * @mixin \Eloquent
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $status
 * @property int|null $assigned_to
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $assignee
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BugReport whereTitle($value)
 * @mixin \Eloquent
 */
	class BugReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusinessException whereMessage($value)
 * @mixin \Eloquent
 */
	class BusinessException extends \Eloquent {}
}

namespace App\Models{
/**
 * Enhanced Cart Model
 * 
 * Updated: 2025-08-01 12:36:10 UTC by DenuJanuari
 * - CRITICAL FIX: Added missing 'name' field for compatibility
 * - Fixed decimal type casting to prevent number_format errors
 * - Enhanced calculation methods with type safety
 * - Added comprehensive validation and helper methods
 * - Improved documentation and error handling
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $name Added for compatibility
 * @property string|null $note
 * @property int $interface_id
 * @property float|null $discount Fixed casting
 * @property float $price Fixed casting
 * @property string|null $promo_code
 * @property array|null $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @property-read float $subtotal
 * @property-read float $final_price
 * @property-read string $formatted_price
 * @property-read string $formatted_subtotal
 * @author mulyadafa, enhanced by DenuJanuari
 * @updated 2025-08-01 12:36:10 UTC
 * @property-read int $available_stock
 * @property-read float $discount_amount
 * @property-read float $discount_percentage
 * @property-read string $formatted_final_price
 * @property-read string $product_name
 * @property-read array $summary
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart forUser($userId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart wherePromoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart withValidProducts()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart withValidStock()
 */
	class Cart extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $icon
 * @property int $status
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $message
 * @property string|null $promo_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact wherePromoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Contact whereUpdatedAt($value)
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $customer_id
 * @property string|null $ticket_number
 * @property string|null $category
 * @property string|null $subject
 * @property string|null $description
 * @property string|null $status
 * @property string|null $priority
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereTicketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerSupport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CustomerSupport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $title
 * @property array<array-key, mixed>|null $layout
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dashboard whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Dashboard extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $db_name
 * @property string|null $backup_type
 * @property string|null $file_path
 * @property int|null $size
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereBackupType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereDbName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseBackup whereSize($value)
 * @mixin \Eloquent
 */
	class DatabaseBackup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $db_name
 * @property string $host
 * @property int $port
 * @property string $username
 * @property string|null $password
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereDbName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DatabaseConfig whereUsername($value)
 * @mixin \Eloquent
 */
	class DatabaseConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $version
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $notes
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deployment whereVersion($value)
 * @mixin \Eloquent
 */
	class Deployment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_dev_role_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Developer> $developers
 * @property-read int|null $developers_count
 * @property-read \App\Models\EnumDevRole $enumRole
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole whereEnumDevRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevRole whereId($value)
 * @mixin \Eloquent
 */
	class DevRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_dev_status_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Developer> $developers
 * @property-read int|null $developers_count
 * @property-read \App\Models\EnumDevStatus $enumStatus
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus whereEnumDevStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DevStatus whereId($value)
 * @mixin \Eloquent
 */
	class DevStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property int $role_id
 * @property int $status_id
 * @property string|null $specialization
 * @property string|null $github_profile
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeveloperLog> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeveloperPermission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\DevRole $role
 * @property-read \App\Models\DevStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereGithubProfile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Developer whereUsername($value)
 * @mixin \Eloquent
 */
	class Developer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $developer_id
 * @property string $action
 * @property string|null $description
 * @property string|null $ip_address
 * @property string $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Developer $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class DeveloperLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $developer_id
 * @property string $module
 * @property bool $can_view
 * @property bool $can_commit
 * @property bool $can_merge
 * @property bool $can_deploy
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Developer $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanCommit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanDeploy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanMerge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCanView($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeveloperPermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class DeveloperPermission extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $customer_id
 * @property string $type
 * @property string|null $description
 * @property string|null $status
 * @property string|null $resolution
 * @property int $interface_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User $customer
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereResolution($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisputeManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class DisputeManagement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminRole> $adminRoles
 * @property-read int|null $admin_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminRole whereValue($value)
 * @mixin \Eloquent
 */
	class EnumAdminRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdminStatus> $adminStatuses
 * @property-read int|null $admin_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumAdminStatus whereValue($value)
 * @mixin \Eloquent
 */
	class EnumAdminStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevRole> $devRoles
 * @property-read int|null $dev_roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevRole whereValue($value)
 * @mixin \Eloquent
 */
	class EnumDevRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DevStatus> $devStatuses
 * @property-read int|null $dev_statuses_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumDevStatus whereValue($value)
 * @mixin \Eloquent
 */
	class EnumDevStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumOrderStatus whereValue($value)
 * @mixin \Eloquent
 */
	class EnumOrderStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumPaymentStatus whereValue($value)
 * @mixin \Eloquent
 */
	class EnumPaymentStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ReportType> $reportTypes
 * @property-read int|null $report_types_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumReportType whereValue($value)
 * @mixin \Eloquent
 */
	class EnumReportType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereValue($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumRole whereUpdatedAt($value)
 */
	class EnumRole extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistic> $statistics
 * @property-read int|null $statistics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EnumStatsType whereValue($value)
 * @mixin \Eloquent
 */
	class EnumStatsType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $url
 * @property string|null $type
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Environment whereUrl($value)
 * @mixin \Eloquent
 */
	class Environment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $category
 * @property string|null $question
 * @property string|null $answer
 * @property bool $status
 * @property int|null $order
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq query()
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Faq whereOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Faq aktif($interfaceId = 8)
 */
	class Faq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $jenis
 * @property string|null $content
 * @property int|null $rating
 * @property string|null $status
 * @property int $interface_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\User|null $customer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $interface_id
 * @property string $method_name
 * @property string $return_type
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereMethodName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereReturnType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class InterfaceMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, InterfaceMethod> $methods
 * @property-read int|null $methods_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InterfaceModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class InterfaceModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $subject
 * @property string|null $content
 * @property string|null $recipient_type
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereRecipientType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Newsletter whereSubject($value)
 * @mixin \Eloquent
 */
	class Newsletter extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $redeem_code
 * @property int|null $promotion_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber wherePromotionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereRedeemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|NewsletterSubscriber whereUpdatedAt($value)
 */
	class NewsletterSubscriber extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $shipping_method
 * @property string|null $order_code
 * @property string|null $order_date
 * @property int|null $enum_order_status_id
 * @property string|null $total_price
 * @property string|null $shipping_cost
 * @property string|null $note
 * @property string|null $payment_method
 * @property string|null $total
 * @property string $status
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $details
 * @property-read int|null $details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEnumOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property numeric $price
 * @property numeric $subtotal
 * @property string|null $note
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OrderDetail extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property string|null $product_name
 * @property int $quantity
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $admin_id
 * @property string $action
 * @property string|null $notes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $interface_id
 * @property-read \App\Models\Admin $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OrderManagement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $method_id
 * @property string $transaction_code
 * @property string|null $bank_account
 * @property numeric $total
 * @property int $enum_payment_status_id
 * @property string|null $proof_of_payment
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\PaymentMethod $method
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\EnumPaymentStatus $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereEnumPaymentStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereProofOfPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentException whereMessage($value)
 * @mixin \Eloquent
 */
	class PaymentException extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property array<array-key, mixed>|null $config
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereDescription($value)
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $metric_name
 * @property numeric $value
 * @property string|null $unit
 * @property \Illuminate\Support\Carbon $timestamp
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereMetricName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Performance whereValue($value)
 * @mixin \Eloquent
 */
	class Performance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $policy_version
 * @property string $accepted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance wherePolicyVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PolicyAcceptance whereUserId($value)
 * @mixin \Eloquent
 */
	class PolicyAcceptance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string|null $description
 * @property int $stock
 * @property float $price
 * @property float $weight
 * @property string|null $image_url
 * @property bool $status
 * @property int $interface_id
 * @property int $is_featured
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductLike> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereWeight($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $image_url
 * @property bool $is_primary
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductLike whereUserId($value)
 */
	class ProductLike extends \Eloquent {}
}

namespace App\Models{
/**
 * Promotion Model
 *
 * @property int $id
 * @property string|null $promo_code
 * @property string|null $title
 * @property string|null $description
 * @property string|null $discount_type
 * @property float|null $discount_value
 * @property float|null $minimum_purchase
 * @property float|null $maximum_discount
 * @property int|null $usage_limit
 * @property int $used_count
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $interface_id
 * @property-read \App\Models\InterfaceModel|null $interface
 * @property-read string $discount_display
 * @property-read int|null $remaining_usage
 * 
 * Updated: 2025-07-31 17:13:30 by DenuJanuari
 * @property-read int|null $days_until_expiry
 * @property-read string|null $formatted_end_date
 * @property-read string|null $formatted_start_date
 * @property-read string $status_text
 * @property-read float $usage_percentage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion byCode($promoCode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion valid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion validDate()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereMaximumDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereMinimumPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion wherePromoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Promotion whereUsedCount($value)
 */
	class Promotion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $supplier_id
 * @property string|null $status
 * @property numeric|null $total_amount
 * @property string|null $payment_status
 * @property \Illuminate\Support\Carbon|null $delivery_date
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\SupplierManagement $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereDeliveryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseOrder whereTotalAmount($value)
 * @mixin \Eloquent
 */
	class PurchaseOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $query_text
 * @property int $execution_time
 * @property string|null $suggested_optimization
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereExecutionTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereQueryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|QueryOptimization whereSuggestedOptimization($value)
 * @mixin \Eloquent
 */
	class QueryOptimization extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property numeric|null $amount
 * @property string|null $reason
 * @property string|null $status
 * @property int|null $processed_by
 * @property \Illuminate\Support\Carbon|null $processed_at
 * @property int $interface_id
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereProcessedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RefundManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class RefundManagement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $deployment_id
 * @property string|null $content
 * @property int|null $created_by
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $creator
 * @property-read \App\Models\Deployment $deployment
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereDeploymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReleaseNote whereInterfaceId($value)
 * @mixin \Eloquent
 */
	class ReleaseNote extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_report_type_id
 * @property-read EnumReportType $enumType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereEnumReportTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportType whereId($value)
 * @mixin \Eloquent
 */
	class ReportType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResourceNotFoundException whereMessage($value)
 * @mixin \Eloquent
 */
	class ResourceNotFoundException extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int $user_id
 * @property int $rating
 * @property string|null $comment
 * @property string|null $image_url
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @mixin \Eloquent
 */
	class Review extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read EnumRole $enumRole
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereEnumRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $status
 * @property string|null $findings
 * @property int|null $developer_id
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $developer
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereDeveloperId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereFindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityAudit whereType($value)
 * @mixin \Eloquent
 */
	class SecurityAudit extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $component
 * @property string $config_key
 * @property string|null $config_value
 * @property bool $is_encrypted
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereConfigKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereConfigValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityConfig whereIsEncrypted($value)
 * @mixin \Eloquent
 */
	class SecurityConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $related_to
 * @property string|null $event
 * @property string|null $description
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SecurityLog whereRelatedTo($value)
 * @mixin \Eloquent
 */
	class SecurityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property string $courier
 * @property string $service
 * @property string|null $tracking_number
 * @property numeric $shipping_cost
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $estimated_delivery
 * @property int $interface_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Order $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCourier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereEstimatedDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Shipping extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingException whereMessage($value)
 * @mixin \Eloquent
 */
	class ShippingException extends \Eloquent {}
}

namespace App\Models{
/**
 * ShippingMethod Model - FINAL DECIMAL ERROR FIX
 * 
 * Updated: 2025-08-01 12:38:23 UTC by DenuJanuari
 * - DEFINITIVELY FIXED: Line 73 decimal type error in number_format()
 * - Changed decimal casting to float to prevent type conflicts
 * - Maintained data precision while ensuring PHP compatibility
 * - All number_format operations now work without errors
 *
 * @property int $id
 * @property string $code Kode metode pengiriman
 * @property string $name Nama metode pengiriman
 * @property string $service Jenis layanan
 * @property float $cost Biaya pengiriman default
 * @property string|null $description Deskripsi metode pengiriman
 * @property bool $is_active Status aktif
 * @property int $sort Urutan tampilan
 * @property \Illuminate\Support\Carbon|null $start_date Tanggal mulai aktif
 * @property \Illuminate\Support\Carbon|null $end_date Tanggal berakhir aktif
 * @property array<array-key, mixed>|null $settings Pengaturan tambahan dalam JSON
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $detail
 * @property-read mixed $display_name
 * @property-read mixed $estimated_time
 * @property-read mixed $formatted_cost
 * @property-read mixed $formatted_price
 * @property-read mixed $icon
 * @property mixed $price
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod byCode($code)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod byService($service)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ShippingMethod whereUpdatedAt($value)
 */
	class ShippingMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $stats_type_id
 * @property string $code
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 * @property-read EnumStatsType|null $enumType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistic> $statistics
 * @property-read int|null $statistics_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereStatsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class StatType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $enum_stats_type_id
 * @property string|null $period
 * @property array<array-key, mixed>|null $data
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\StatType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereEnumStatsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Statistic wherePeriod($value)
 * @mixin \Eloquent
 */
	class Statistic extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string|null $type
 * @property string|null $notes
 * @property int|null $created_by
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockManagement whereType($value)
 * @mixin \Eloquent
 */
	class StockManagement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $subscriber_id
 * @property string $email
 * @property string $subscribed_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereSubscribedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscriber whereSubscriberId($value)
 * @mixin \Eloquent
 */
	class Subscriber extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property bool $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property string $updated_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SupplierManagement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class SupplierManagement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $component
 * @property string $status
 * @property numeric|null $cpu_usage
 * @property numeric|null $memory_usage
 * @property numeric|null $disk_usage
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereCpuUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereDiskUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereMemoryUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemHealth whereStatus($value)
 * @mixin \Eloquent
 */
	class SystemHealth extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $test_type
 * @property string|null $expected_result
 * @property string|null $status
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TestReport> $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereExpectedResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereTestType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestCase whereTitle($value)
 * @mixin \Eloquent
 */
	class TestCase extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $test_id
 * @property string|null $actual_result
 * @property string|null $status
 * @property int|null $executed_by
 * @property \Illuminate\Support\Carbon|null $executed_at
 * @property int $interface_id
 * @property-read \App\Models\Developer|null $executor
 * @property-read \App\Models\InterfaceModel $interface
 * @property-read \App\Models\TestCase $testCase
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereActualResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereExecutedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereExecutedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TestReport whereTestId($value)
 * @mixin \Eloquent
 */
	class TestReport extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $testimonial_id
 * @property int $user_id
 * @property string $content
 * @property int $rating
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereTestimonialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUserId($value)
 * @mixin \Eloquent
 */
	class Testimonial extends \Eloquent {}
}

namespace App\Models{
/**
 * User Model
 * 
 * Last updated: 2025-07-30 07:32:44
 * Updated by: mulyadafa
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property string|null $plain_password
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property int $interface_id
 * @property string|null $profile_photo_path
 * @property-read string $avatar
 * @property-read Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|\App\Models\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cart> $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePlainPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $message
 * @property int $errorCode
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException whereErrorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ValidationException whereMessage($value)
 * @mixin \Eloquent
 */
	class ValidationException extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string|null $description
 * @property string|null $severity
 * @property string|null $status
 * @property string|null $fix_details
 * @property string $created_at
 * @property int $interface_id
 * @property-read \App\Models\InterfaceModel $interface
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereFixDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereInterfaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vulnerability whereType($value)
 * @mixin \Eloquent
 */
	class Vulnerability extends \Eloquent {}
}

