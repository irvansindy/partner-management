<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FormatResponseJson;
use App\Models\{
    CompanyInformation,
    ApprovalMaster,
    ApprovalDetails,
    CompanyAddress,
    Provinces,
    Regencies
};
use Illuminate\Support\Str;
class DashboardController extends Controller
{
    protected $user;
    protected $isSuperAdmin;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            $this->isSuperAdmin = $this->user && $this->user->hasRole('super-admin');
            return $next($request);
        });
    }

    public function index()
    {
        return view('dashboard');
    }

    /**
     * Helper universal untuk filter data berdasarkan role, department, dan location
     */
    private function filterCompanyQueryByUser($query)
    {
        if (!$this->user) return $query;

        // Super-admin & super-user: bisa lihat semua
        if ($this->user->hasRole('super-admin') || $this->user->hasRole('super-user')) {
            return $query;
        }

        // Admin: batasi berdasarkan department dan location
        if ($this->user->hasRole('admin')) {
            return $query->where('department_id', $this->user->department_id)
                        ->where('location_id', $this->user->office_id);
        }

        // Role lain (staff, user biasa)
        return $query->where('department_id', $this->user->department_id)
                    ->where('location_id', $this->user->office_id);
    }

    /**
     * Dashboard summary (partner, vendor, customer, dsb)
     */
    public function fetchDataCount()
    {
        try {
            $cacheKey = "dashboard_data_count_{$this->isSuperAdmin}_{$this->user->office_id}";
            $data = Cache::remember($cacheKey, now()->addMinutes(5), function () {
                $data = [];

                // Gunakan helper filter
                $data['total_partners'] = $this->filterCompanyQueryByUser(
                    CompanyInformation::where('status', 'approved')
                )->count();

                $data['total_customers'] = $this->filterCompanyQueryByUser(
                    CompanyInformation::where('type', 'customer')->where('status', 'approved')
                )->count();

                $data['total_vendors'] = $this->filterCompanyQueryByUser(
                    CompanyInformation::where('type', 'vendor')->where('status', 'approved')
                )->count();

                $data['total_pending_approvals'] = $this->filterCompanyQueryByUser(
                    CompanyInformation::whereIn('status', ['checking', 'checking 2'])
                )->count();

                // Lokasi partner (map)
                $data['list_partner_location'] = $this->filterCompanyQueryByUser(
                    CompanyInformation::with(['AddressBaseOnMap:id,company_id,address,latitude,longitude'])
                        ->select('id', 'name', 'group_name', 'type')
                )->get()->map(function ($item) {
                    return [
                        'name'       => $item->name,
                        'group_name' => $item->group_name,
                        'type'       => $item->type,
                        'address'    => $item->AddressBaseOnMap->address ?? null,
                        'latitude'   => $item->AddressBaseOnMap->latitude ?? null,
                        'longitude'  => $item->AddressBaseOnMap->longitude ?? null,
                    ];
                });

                // get total partner (vendor and customer)
                $provinces = Provinces::select('id', 'name')->orderBy('name', 'asc')->get();

                $deptName = strtolower($this->user->dept->name ?? '');
                $deptId = $this->user->department_id ?? $this->user->dept_id ?? null;
                $locId = $this->user->office_id ?? $this->user->location_id ?? null;

                // Flags
                $isSuper = $this->isSuperAdmin;
                $isAdminOrSuperUser = $this->user && in_array($this->user->getRoleNames()->first() ?? '', ['admin', 'super-user']);
                $isSales = Str::contains($deptName, 'sales');
                $isPurchasing = Str::contains($deptName, 'purchasing');

                // Helper: build base query builder for a given type ('vendor'|'customer')
                $buildQueryForType = function(string $type) use ($isSuper, $isAdminOrSuperUser, $deptId, $locId) {
                    $q = DB::table('provinces')
                        ->leftJoin('company_addresses', 'company_addresses.province', '=', 'provinces.id')
                        ->leftJoin('company_informations', function ($join) use ($type) {
                            $join->on('company_informations.id', '=', 'company_addresses.company_id')
                                ->where('company_informations.type', $type)
                                ->where('company_informations.status', 'approved');
                        });

                    // apply role/location/department constraint only if NOT super admin
                    if (! $isSuper) {
                        if ($isAdminOrSuperUser && $deptId) {
                            // admin/super-user: filter by both department & location if available
                            if ($locId) {
                                $q->where('company_informations.department_id', $deptId)
                                ->where('company_informations.location_id', $locId);
                            } else {
                                $q->where('company_informations.department_id', $deptId);
                            }
                        } else {
                            // other roles: filter by location only if available
                            if ($locId) {
                                $q->where('company_informations.location_id', $locId);
                            }
                        }
                    }

                    return $q;
                };

                // Decide which keys to produce
                $resultVendor = null;
                $resultCustomer = null;

                // If super admin -> produce both
                if ($isSuper) {
                    $resultVendor = $buildQueryForType('vendor')
                        ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                        ->groupBy('provinces.name')
                        ->orderBy('provinces.name')
                        ->pluck('total', 'province');

                    $resultCustomer = $buildQueryForType('customer')
                        ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                        ->groupBy('provinces.name')
                        ->orderBy('provinces.name')
                        ->pluck('total', 'province');
                } else {
                    // Not super admin
                    if ($isAdminOrSuperUser) {
                        // admin/super-user: if department is sales => only customer
                        if ($isSales) {
                            // build only customer
                            $resultCustomer = $buildQueryForType('customer')
                                ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                                ->groupBy('provinces.name')
                                ->orderBy('provinces.name')
                                ->pluck('total', 'province');

                            // vendor intentionally not fetched (or set to null)
                            $resultVendor = null;
                        } elseif ($isPurchasing) {
                            // only vendor
                            $resultVendor = $buildQueryForType('vendor')
                                ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                                ->groupBy('provinces.name')
                                ->orderBy('provinces.name')
                                ->pluck('total', 'province');

                            $resultCustomer = null;
                        } else {
                            // admin of other dept => both but filtered by location/department as applied in builder
                            $resultVendor = $buildQueryForType('vendor')
                                ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                                ->groupBy('provinces.name')
                                ->orderBy('provinces.name')
                                ->pluck('total', 'province');

                            $resultCustomer = $buildQueryForType('customer')
                                ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                                ->groupBy('provinces.name')
                                ->orderBy('provinces.name')
                                ->pluck('total', 'province');
                        }
                    } else {
                        // regular user / other roles => filter by location only (both types)
                        $resultVendor = $buildQueryForType('vendor')
                            ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                            ->groupBy('provinces.name')
                            ->orderBy('provinces.name')
                            ->pluck('total', 'province');

                        $resultCustomer = $buildQueryForType('customer')
                            ->select('provinces.name as province', DB::raw('COUNT(DISTINCT company_informations.id) as total'))
                            ->groupBy('provinces.name')
                            ->orderBy('provinces.name')
                            ->pluck('total', 'province');
                    }
                }
                // Build final array only with allowed keys
                $final = [];

                // SUPER ADMIN → tampilkan keduanya
                if ($isSuper) {
                    $final['vendor'] = $provinces->map(fn($p) => [
                        'province' => $p->name,
                        'total' => $resultVendor[$p->name] ?? 0,
                    ]);

                    $final['customer'] = $provinces->map(fn($p) => [
                        'province' => $p->name,
                        'total' => $resultCustomer[$p->name] ?? 0,
                    ]);
                }

                // ADMIN / SUPER-USER (dengan department tertentu)
                elseif ($isAdminOrSuperUser) {
                    if ($isSales) {
                        // hanya CUSTOMER
                        $final['customer'] = $provinces->map(fn($p) => [
                            'province' => $p->name,
                            'total' => $resultCustomer[$p->name] ?? 0,
                        ]);
                    } elseif ($isPurchasing) {
                        // hanya VENDOR
                        $final['vendor'] = $provinces->map(fn($p) => [
                            'province' => $p->name,
                            'total' => $resultVendor[$p->name] ?? 0,
                        ]);
                    }

                }
                $data['total_partner_location'] = $final;

                return $data;
            });

            return FormatResponseJson::success($data, 'Dashboard data count fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

    /**
     * Customer terbaru
     */
    public function fetchRecentCustomer()
    {
        try {
            $query = CompanyInformation::where('type', 'customer')->latest()->limit(10);
            $customers = Cache::remember("recent_customers_{$this->isSuperAdmin}_{$this->user->office_id}", now()->addMinutes(5), function () use ($query) {
                return $this->filterCompanyQueryByUser($query)->get();
            });

            $message = $customers->isNotEmpty()
                ? 'Recent customers fetched successfully'
                : 'No recent customers found';

            return FormatResponseJson::success($customers, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

    /**
     * Vendor terbaru
     */
    public function fetchRecentVendor()
    {
        try {
            $query = CompanyInformation::where('type', 'vendor')->latest()->limit(10);
            $vendors = Cache::remember("recent_vendors_{$this->isSuperAdmin}_{$this->user->office_id}", now()->addMinutes(5), function () use ($query) {
                return $this->filterCompanyQueryByUser($query)->get();
            });

            $message = $vendors->isNotEmpty()
                ? 'Recent vendors fetched successfully'
                : 'No recent vendors found';

            return FormatResponseJson::success($vendors, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

    /**
     * Approval terbaru
     */
    public function fetchRecentApprovals()
    {
        try {
            if ($this->isSuperAdmin) {
                $approvals = Cache::remember("recent_approvals_superadmin", now()->addMinutes(1), function () {
                    return ApprovalMaster::with(['company', 'approval.user'])
                        ->latest()
                        ->limit(10)
                        ->get();
                });
            } else {
                $approvals = Cache::remember("recent_approvals_{$this->user->id}", now()->addMinutes(1), function () {
                    return ApprovalDetails::with(['user', 'approvalMaster.company'])
                        ->where('status', 1)
                        ->where('user_id', Auth::id())
                        ->whereHas('approvalMaster', fn($q) => $q->where('location_id', $this->user->office_id))
                        ->latest()
                        ->limit(10)
                        ->get();
                });
            }

            $message = $approvals->isNotEmpty()
                ? 'Recent approvals fetched successfully'
                : 'No recent approvals found';

            return FormatResponseJson::success([
                'approvals' => $approvals,
                'isSuperAdmin' => $this->isSuperAdmin,
            ], $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

    /**
     * Titik map vendor & customer
     */
    public function mapPoint()
    {
        try {
            $vendors = $this->filterCompanyQueryByUser(
                CompanyInformation::with('address')->where('type', 'vendor')
            )->get();

            $customers = $this->filterCompanyQueryByUser(
                CompanyInformation::with('address')->where('type', 'customer')
            )->get();

            $map_points = collect();

            foreach ($vendors as $v) {
                foreach ($v->address as $a) {
                    $map_points->push([
                        'type' => 'vendor',
                        'name' => $v->name,
                        'address' => $a->address,
                        'latitude' => $a->latitude,
                        'longitude' => $a->longitude,
                    ]);
                }
            }

            foreach ($customers as $c) {
                foreach ($c->address as $a) {
                    $map_points->push([
                        'type' => 'customer',
                        'name' => $c->name,
                        'address' => $a->address,
                        'latitude' => $a->latitude,
                        'longitude' => $a->longitude,
                    ]);
                }
            }

            return FormatResponseJson::success($map_points, 'Map Point Fetched Successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    /**
     * Data jumlah partner per kota (untuk chart drilldown)
     */
    public function fetchDataCountByRegency(Request $request)
    {
        try {
            $provinceName = $request->query('province');
            if (!$provinceName) {
                return FormatResponseJson::error(null, 'Province parameter is required');
            }

            $province = Provinces::where('name', $provinceName)->first();
            if (!$province) {
                return FormatResponseJson::error(null, 'Province not found');
            }

            $user = auth()->user();

            // --- Tentukan hak akses berdasarkan role & department ---
            $showVendor = false;
            $showCustomer = false;

            if ($user->hasRole('super-admin')) {
                $showVendor = true;
                $showCustomer = true;
            } elseif ($user->hasRole(['super-user', 'admin'])) {
                // admin/super-user bisa lihat data sesuai location & department
                if ($user->dept && str_contains(strtolower($user->dept->name), 'sales')) {
                    $showCustomer = true;
                } elseif ($user->dept && str_contains(strtolower($user->dept->name), 'purchasing')) {
                    $showVendor = true;
                } else {
                    // jika bukan sales/purchasing tapi admin biasa, tampilkan keduanya
                    $showVendor = true;
                    $showCustomer = true;
                }
            } elseif ($user->dept && str_contains(strtolower($user->dept->name), 'sales')) {
                $showCustomer = true;
            } elseif ($user->dept && str_contains(strtolower($user->dept->name), 'purchasing')) {
                $showVendor = true;
            }

            // --- Ambil daftar regency di provinsi tersebut ---
            $regencies = $province->regencies()->pluck('id', 'name');
            $vendorData = [];
            $customerData = [];

            foreach ($regencies as $regencyName => $regencyId) {
                // ===================== VENDOR =====================
                $vendorCount = 0;
                if ($showVendor) {
                    $vendorQuery = CompanyInformation::where('type', 'vendor')
                        ->where('status', 'approved')
                        ->whereHas('AddressBaseOnMap', function ($q) use ($province, $regencyId) {
                            $q->where('province', $province->id)
                            ->where('city', $regencyId);
                        });

                    // batasi data berdasarkan user jika bukan super-admin
                    if (!$user->hasRole('super-admin')) {
                        $vendorQuery = $this->filterCompanyQueryByUser($vendorQuery);
                    }

                    $vendorCount = $vendorQuery->count();
                }

                // ===================== CUSTOMER =====================
                $customerCount = 0;
                if ($showCustomer) {
                    $customerQuery = CompanyInformation::where('type', 'customer')
                        ->where('status', 'approved')
                        ->whereHas('AddressBaseOnMap', function ($q) use ($province, $regencyId) {
                            $q->where('province', $province->id)
                            ->where('city', $regencyId);
                        });

                    if (!$user->hasRole('super-admin')) {
                        $customerQuery = $this->filterCompanyQueryByUser($customerQuery);
                    }

                    $customerCount = $customerQuery->count();
                }

                // ===================== BUILD ARRAY =====================
                if ($showVendor) {
                    $vendorData[] = [
                        'city' => $regencyName,
                        'total' => $vendorCount
                    ];
                }

                if ($showCustomer) {
                    $customerData[] = [
                        'city' => $regencyName,
                        'total' => $customerCount
                    ];
                }
            }

            // --- Return hasil JSON ---
            return FormatResponseJson::success([
                'vendor' => $showVendor ? $vendorData : [],
                'customer' => $showCustomer ? $customerData : [],
            ], 'Regency data fetched successfully');

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

    // public function fetchDataCountByRegency(Request $request)
    // {
    //     try {
    //         $provinceName = $request->query('province');
    //         if (!$provinceName) return FormatResponseJson::error(null, 'Province parameter is required');

    //         $province = Provinces::where('name', $provinceName)->first();
    //         if (!$province) return FormatResponseJson::error(null, 'Province not found');

    //         $regencies = $province->regencies()->pluck('id', 'name');
    //         $vendorData = [];
    //         $customerData = [];

    //         foreach ($regencies as $regencyName => $regencyId) {
    //             $vendorCount = $this->filterCompanyQueryByUser(
    //                 CompanyInformation::where('type', 'vendor')
    //                     ->where('status', 'approved')
    //                     ->whereHas('AddressBaseOnMap', fn($q) => $q->where('province', $province->id)->where('city', $regencyId))
    //             )->count();

    //             $customerCount = $this->filterCompanyQueryByUser(
    //                 CompanyInformation::where('type', 'customer')
    //                     ->where('status', 'approved')
    //                     ->whereHas('AddressBaseOnMap', fn($q) => $q->where('province', $province->id)->where('city', $regencyId))
    //             )->count();

    //             $vendorData[] = ['city' => $regencyName, 'total' => $vendorCount];
    //             $customerData[] = ['city' => $regencyName, 'total' => $customerCount];
    //         }

    //         return FormatResponseJson::success([
    //             'vendor' => $vendorData,
    //             'customer' => $customerData,
    //         ], 'Regency data fetched successfully');
    //     } catch (\Exception $e) {
    //         return FormatResponseJson::error(null, $e->getMessage());
    //     }
    // }
}
