<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInformation;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use App\Helpers\JsonCollection;
use App\Http\Resources\PartnerResource;
class ApiPartnerController extends Controller
{
/**
 * Get list of partners.
 *
 * @response 200 {
 *   "meta": {
 *     "code": 200,
 *     "status": "success",
 *     "message": "Data partners fetched successfully"
 *   },
 *   "data": [
 *     {
 *       "id": 1,
 *       "user_id": 5,
 *       "name": "Vereenigde Oost-Indische Compagnie",
 *       "group_name": "VOC Group",
 *       "type": "customer",
 *       "established_year": "1602",
 *       "total_employee": 234000,
 *       "liable_person_and_position": "Heeren Zeventien",
 *       "owner_name": "Johan van Oldenbarnevelt",
 *       "board_of_directors": "Heeren Zeventien",
 *       "major_shareholders": "Isaac Le Maire",
 *       "business_classification": "Distributor",
 *       "business_classification_detail": "Company, atau Persatuan Perusahaan Hindia Timur Belanda. VOC adalah kongsi dagang Belanda yang didirikan pada tahun 1602. VOC memiliki peran penting dalam sejarah Indonesia, karena menjadi perusahaan dagang terbesar pada masanya dan memiliki kekuasaan politik yang besar di wilayah Nusantara",
 *       "other_business": null,
 *       "website_address": "voc.com",
 *       "system_management": "SMK3",
 *       "contact_person": "Isaac Le Maire",
 *       "communication_language": "Bahasa",
 *       "email_address": "isaaclemaire@gmail.com",
 *       "remark": null,
 *       "signature": "vereenigde-oost-indische-compagnie-signature.jpeg",
 *       "stamp": "vereenigde-oost-indische-compagnie-stamp.jpeg",
 *       "supplier_number": null,
 *       "status": "approved",
 *       "location_id": 1,
 *       "department_id": 11,
 *       "blacklist": 0,
 *       "created_at": "2025-05-22T02:56:54.000000Z",
 *       "updated_at": "2025-05-22T08:13:08.000000Z",
 *       "address": [
 *         {
 *           "id": 1,
 *           "company_id": 1,
 *           "address": "Oude Hoogstraat 24, 1012 CE Amsterdam, Belanda",
 *           "country": "Belanda",
 *           "province": "Amsterdam",
 *           "city": "Amsterdam",
 *           "zip_code": "1012",
 *           "telephone": "318789098789",
 *           "fax": "31898767878"
 *         }
 *       ],
 *       "bank": [
 *         {
 *           "id": 1,
 *           "company_id": 1,
 *           "name": "Bank van Leening",
 *           "branch": "Pusat",
 *           "account_name": "Heeren Zeventien",
 *           "city_or_country": "Belanda",
 *           "account_number": "8768976",
 *           "currency": "Gulden",
 *           "swift_code": "897564"
 *         },
 *         {
 *           "id": 2,
 *           "company_id": 1,
 *           "name": "Bank van Courant en Bank van Leening",
 *           "branch": "Pusat",
 *           "account_name": "Heeren Zeventien",
 *           "city_or_country": "Belanda",
 *           "account_number": "6897879879",
 *           "currency": "Gulden",
 *           "swift_code": "87658"
 *         }
 *       ],
 *       "tax": [
 *         {
 *           "id": 1,
 *           "company_id": 1,
 *           "register_number_as_in_tax_invoice": "46854168534168541",
 *           "trc_number": "865341685341653",
 *           "register_number_related_branch": "4165324168534",
 *           "valid_until": "2030-12-08",
 *           "taxable_entrepreneur_number": "5341286521",
 *           "tax_invoice_serial_number": "52416523135",
 *           "created_at": "2025-05-22T02:56:54.000000Z",
 *           "updated_at": "2025-05-22T02:56:54.000000Z"
 *         }
 *       ],
 *       "attachment": []
 *     }
 *   ]
 * }
 */


    public function fetchPartner()
    {
        try {
            $partners = CompanyInformation::with(['address', 'bank', 'tax', 'attachment'])
            ->where('blacklist', 0)
            ->where('status', 'approved')
            ->get();
            // return FormatResponseJson::success($partners, 'Data partners fetched successfully');
            return JsonCollection::success(PartnerResource::collection($partners), 'Data partners fetched successfully');

        } catch (\Exception $e) {
            // return FormatResponseJson::error(null, $e->getMessage(), 404);
            return JsonCollection::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            // return FormatResponseJson::error(null, $th->getMessage(), 500);
            return JsonCollection::error(null, $th->getMessage(), 500);
        }
    }

    /**
     * Blacklist a partner by ID
     *
     * @bodyParam partner_id integer required The ID of the partner to blacklist. Example: 1
     *
     * Blacklist a partner by ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @response 200 {
     *   "meta": {
     *     "code": 200,
     *     "status": "success",
     *     "message": "Partner data has been successfully blacklisted"
     *   },
     *   "data": {
     *     "id": 1,
     *     "name": "PT Maju Jaya",
     *     "status": "approved",
     *     "blacklist": 1,
     *     "address": {
     *       "city": "Jakarta",
     *       "province": "DKI Jakarta"
     *     },
     *     "bank": {
     *       "bank_name": "BCA",
     *       "account_number": "1234567890"
     *     },
     *     "tax": {
     *       "npwp": "01.234.567.8-901.000"
     *     },
     *     "attachment": {
     *       "document_type": "SIUP",
     *       "file_url": "https://example.com/file.pdf"
     *     }
     *   }
     * }
     *
     * @response 404 {
     *   "meta": {
     *     "code": 404,
     *     "status": "error",
     *     "message": "Partner not found"
     *   },
     *   "data": null
     * }
     *
     * @response 500 {
     *   "meta": {
     *     "code": 500,
     *     "status": "error",
     *     "message": "Internal server error"
     *   },
     *   "data": null
     * }
     */

    public function blacklistPartner(Request $request)
    {
        try {
            
            $id = $request->partner_id;
            $partner = CompanyInformation::find($id);
            $partner->update([
                'blacklist' => 1,
            ]);
            // return FormatResponseJson::success($partner,'Partner data has been successfully blacklisted');
            return JsonCollection::success(new PartnerResource($partner), 'Partner data has been successfully blacklisted');
        } catch (\Exception $e) {
            // return FormatResponseJson::error(null, $e->getMessage(), 404);
            return JsonCollection::error(null, $e->getMessage(), 404);
        } catch (\Throwable $th) {
            // return FormatResponseJson::error(null, $th->getMessage(), 500);
            return JsonCollection::error(null, $th->getMessage(), 500);
        }
    }
}
