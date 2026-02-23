<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;
    // ============================================
    // USER TYPE CONSTANTS (Add these at the top)
    // ============================================
    const TYPE_CUSTOMER = 'customer';
    const TYPE_VENDOR_INDIVIDUAL = 'vendor_individual';
    const TYPE_VENDOR_COMPANY = 'vendor_company';
    const TYPE_ADMIN = 'admin';
    const TYPE_STAFF = 'staff';
    const TYPE_SUPERVISOR = 'supervisor';

    // Vendor Type Constants
    const VENDOR_MOVING = 'moving_company';
    const VENDOR_TRANSPORT = 'transportation';
    const VENDOR_PACKING = 'packing_material';
    const VENDOR_MANPOWER = 'manpower';
    const VENDOR_STORAGE = 'storage';
    const VENDOR_LOCAL_MOVING = 'local_moving';
    const VENDOR_INTERNATIONAL_MOVING = 'international_moving';

    // Status Constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_PENDING = 'pending';
    const STATUS_SUSPENDED = 'suspended';

    // ... rest of your model properties ...

    // ============================================
    // HELPER METHODS (Add these at the bottom)
    // ============================================

    /**
     * Check if user is a vendor
     */
    public function isVendor()
    {
        return in_array($this->user_type, [
            self::TYPE_VENDOR_INDIVIDUAL,
            self::TYPE_VENDOR_COMPANY
        ]);
    }

    /**
     * Check if user is a company vendor
     */
    public function isCompany()
    {
        return $this->user_type === self::TYPE_VENDOR_COMPANY;
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer()
    {
        return $this->user_type === self::TYPE_CUSTOMER;
    }

    /**
     * Check if user is admin or staff
     */
    public function isAdmin()
    {
        return in_array($this->user_type, [
            self::TYPE_ADMIN,
            self::TYPE_STAFF
        ]);
    }

    /**
     * Check if user is a supervisor
     */
    public function isSupervisor()
    {
        return $this->user_type === self::TYPE_SUPERVISOR;
    }

    /**
     * Check if vendor is approved
     */
    public function isApprovedVendor()
    {
        return $this->isVendor() && $this->is_approved;
    }

    /**
     * Calculate profile completion percentage
     */
    public function profileCompletionPercentage()
    {
        $totalPoints = 0;
        $earnedPoints = 0;

        // Basic profile fields (40 points)
        $basicFields = [
            'name' => 10,
            'email' => 10,
            'phone' => 10,
            'address' => 10,
        ];

        foreach ($basicFields as $field => $points) {
            $totalPoints += $points;
            if (!empty($this->$field)) {
                $earnedPoints += $points;
            }
        }

        // Photo (10 points)
        $totalPoints += 10;
        if (!empty($this->photo)) {
            $earnedPoints += 10;
        }

        // Vendor-specific fields (50 points if vendor)
        if ($this->isVendor()) {
            $vendorFields = [
                'company_name' => 10,
                'vendor_type' => 10,
                'country' => 5,
                'state' => 5,
                'service_area' => 10,
            ];

            foreach ($vendorFields as $field => $points) {
                $totalPoints += $points;
                if (!empty($this->$field)) {
                    $earnedPoints += $points;
                }
            }

            // Company-specific fields (10 points)
            if ($this->isCompany()) {
                $companyFields = [
                    'trade_license' => 5,
                    'contact_person_name' => 5,
                ];

                foreach ($companyFields as $field => $points) {
                    $totalPoints += $points;
                    if (!empty($this->$field)) {
                        $earnedPoints += $points;
                    }
                }
            }

            // Moving company specific (5 points)
            if ($this->vendor_type === self::VENDOR_MOVING && !empty($this->emirates_id)) {
                $totalPoints += 5;
                $earnedPoints += 5;
            }
        }

        // Calculate percentage
        return $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
    }

    /**
     * Get display name (company name for vendors, personal name for others)
     */
    public function getDisplayName()
    {
        if ($this->isCompany() && !empty($this->company_name)) {
            return $this->company_name;
        }
        return $this->name;
    }

    /**
     * Check if vendor can accept a job (has sufficient wallet balance)
     */
    public function canAcceptJob($jobPrice)
    {
        if (!$this->isVendor() || !$this->is_approved) {
            return false;
        }

        // Job price + 5% VAT (as per your business logic)
        $requiredDeposit = $jobPrice * 1.05;
        return $this->wallet_balance >= $requiredDeposit;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'user_type',
        'vendor_type',
        'company_name',
        'trade_license',
        'emirates_id',
        'contact_person_name',
        'contact_person_designation',
        'country',
        'state',
        'service_area',
        'address',
        'photo',
        'id_copy_front',
        'id_copy_back',
        'trade_license_copy',
        'package_id',
        'wallet_balance',
        'loyalty_points',
        'status',
        'is_approved',
        'approved_at',
        'profile_completed',
        'email_verified_at',
        'last_login_at',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'is_approved' => 'boolean',
            'profile_completed' => 'boolean',
            'service_area' => 'array', // For multiple service areas
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Workspaces owned by the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workspaces(): HasMany
    {
        return $this->hasMany(\App\Models\Workspace::class);
    }

}
