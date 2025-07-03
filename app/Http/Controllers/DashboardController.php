<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Helpers\FormatResponseJson;
use App\Models\CompanyInformation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ApprovalMaster;
use App\Models\ApprovalDetails;
class DashboardController extends Controller
{
    protected $userOfficeId;
    protected $isSuperAdmin;
    public function __construct()
    {
        $this->middleware(function ($request, $next)  {
            $this->userOfficeId = auth()->check() ? auth()->user()->office_id : null;
            $this->isSuperAdmin = auth()->user()->hasRole('super-admin');
            return $next($request);
        });
    }
    public function index()
    {
        return view('dashboard');
    }
    public function fetchDataCount()
    {
        try {
            $data = Cache::remember("dashboard_data_count_{$this->isSuperAdmin}_{$this->userOfficeId}", now()->addMinutes(5), function () {
                $data = [];
                $data['total_partners'] = CompanyInformation::where('status', 'approved')->count();
                $data['total_customers'] = CompanyInformation::where('type', 'customer')->where('status', 'approved')->count();
                $data['total_vendors'] = CompanyInformation::where('type', 'vendor')->where('status', 'approved')->count();
                $data['total_users'] = User::whereHas('roles', function ($query) {
                    $query->where('name', '!=', 'super-admin');
                })->count();
                return $data;
            });
            return FormatResponseJson::success($data, 'Dashboard data count fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }
    public function fetchRecentCustomer()
    {
        try {
            $this->authorize('viewCustomer', CompanyInformation::class);
            $customers = Cache::remember("recent_customers_{$this->isSuperAdmin}_{$this->userOfficeId}", now()->addMinutes(5), function () {
                $query = CompanyInformation::where('type', 'customer')->latest()->limit(10);
                if (!$this->isSuperAdmin) {
                    $query->where('location_id', $this->userOfficeId);
                }
                return $query->get();
            });
            $message = count($customers) > 0 ? 'Recent customers fetched successfully' : 'No recent customers found';
            return FormatResponseJson::success($customers, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }
    public function fetchRecentVendor()
    {
        try {
            $this->authorize('viewVendor', CompanyInformation::class);
            $vendors = Cache::remember("recent_vendors_{$this->isSuperAdmin}_{$this->userOfficeId}", now()->addMinutes(5), function () {
                $query = CompanyInformation::where('type', 'vendor')->latest()->limit(10);
                if (!$this->isSuperAdmin) {
                    $query->where('location_id', $this->userOfficeId);
                }
                return $query->get();
            });
        $message = count($vendors) > 0 ? 'Recent vendors fetched successfully' : 'No recent vendors found';
        return FormatResponseJson::success($vendors, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }
    public function fetchRecentApprovals() {
        try {
            $this->authorize('viewApproval', CompanyInformation::class);
            switch ($this->isSuperAdmin) {
                case false:
                    $approvals = Cache::remember("recent_approvals", now()->addMinutes(1), function () {
                        $query = ApprovalDetails::with(['user', 'approvalMaster.company']);
                        $query->where('status', 1);
                        $query->where('user_id', Auth::id());
                        $query->whereHas('approvalMaster', function ($query) {
                            $query->where('location_id', $this->userOfficeId);
                        });
                        $query->latest()->limit(10);
                        return $query->get();
                    });
                    break;

                case true:
                    $approvals = Cache::remember("recent_approvals_{$this->isSuperAdmin}", now()->addMinutes(1), function () {
                        $query = ApprovalMaster::with(['company', 'approval.user']);
                        $query->latest()->limit(10);
                        return $query->get();
                    });
                
                default:
                    false;
                    break;
            }
            $data = [
                'approvals' => $approvals,
                'isSuperAdmin' => $this->isSuperAdmin,
            ];
            $message = count($approvals) > 0 ? 'Recent approvals fetched successfully' : 'No recent approvals found';
            return FormatResponseJson::success($data, $message);
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage());
        }
    }

}
