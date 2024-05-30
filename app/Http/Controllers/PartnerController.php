<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInfomation;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyDocumentTypeCategories;
use App\Models\CompanyAddress;
use App\Models\CompanyBank;
use App\Models\CompanyTax;
use App\Models\CompanyAdditionalInformation;
use App\Models\CompanySupportingDocument;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Validator;
class PartnerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view("company.index");
    }

    public function detailPartner(Request $request)
    {
        return view('company.detail_company');
    }

    public function fetchCompany()
    {
        try {
            $company_profile = CompanyInfomation::with(['user', 'address', 'bank', 'tax', 'add_info'])
            ->where("user_id", auth()->user()->id)->first();

            return FormatResponseJson::success($company_profile, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function fetchCompanyPartnerById()
    {
        try {
            $company_profile = CompanyInfomation::with(['user', 'address', 'bank', 'tax', 'add_info'])
            ->where("user_id", auth()->user()->id)->first();

            return FormatResponseJson::success($company_profile, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function fetchDocTypeCategories()
    {
        try {
            $doc_type = CompanyDocumentTypeCategories::all();
            return FormatResponseJson::success($doc_type,'Document Type fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'company_group_name' => 'required|string',
                'company_type' => 'required|string',
                'established_year' => 'required|string',
                'total_employee' => 'required|string',
                'liable_person_and_position' => 'required|string',
                'owner_name' => 'required|string',
                'board_of_directors' => 'required|string',
                'major_shareholders' => 'required|string',
                'business_classification' => 'required|string',
                'website_address' => 'required|string',
                'system_management' => 'required|string',
                'contact_person' => 'required|string',
                'communication_language' => 'required|string',
                'email_address' => 'required|string',
                'stamp_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'signature_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'address.*' => 'required|string',
                'city.*' => 'required|string',
                'country.*' => 'required|string',
                'province.*' => 'required|string',
                'zip_code.*' => 'required|string',
                'telephone.*' => 'required|string',
                'fax.*' => 'required|string',
            ], [
                'company_name.required' => 'Company name/Nama perusahaan tidak boleh kosong',
                'company_group_name.required' => 'Company group name/Nama grup perusahaan tidak boleh kosong',
                'company_type.required' => 'Company type/tipe perusahaan tidak boleh kosong',
                'established_year.required' => 'Established since year/Tahun berdiri tidak boleh kosong',
                'total_employee.required' => 'Total employee/Jumlah Karyawan tidak boleh kosong',
                'liable_person_and_position.required' => 'Liable person/Penanggung jawab tidak boleh kosong',
                'owner_name.required' => 'Owner name/Nama pemilik tidak boleh kosong',
                'board_of_directors.required' => 'Board of directors/Dewan direktur tidak boleh kosong',
                'major_shareholders.required' => 'Board of directors/Pemilik saham mayoritas tidak boleh kosong',
                'business_classification.required' => 'Business classification/Jenis usaha tidak boleh kosong',
                'website_address.required' => 'Website address/Alamat situs web tidak boleh kosong',
                'system_management.required' => 'System management/Manajemen sistem tidak boleh kosong',
                'contact_person.required' => 'Contact person/Kontak person tidak boleh kosong',
                'communication_language.required' => 'Communication language/Bahasa komunikasi tidak boleh kosong',
                'email_address.required' => 'Email address/Alamat email tidak boleh kosong',
                'stamp_file.required' => 'Stamp/Stempel tidak boleh kosong',
                'signature_file.required' => 'Signature/Tanda tangan tidak boleh kosong',
                'address.*.required' => 'Address/Alamat tidak boleh kosong',
                'city.*.required' => 'City/Kota tidak boleh kosong',
                'country.*.required' => 'Country/Negara tidak boleh kosong',
                'province.*.required' => 'Province/Provinsi tidak boleh kosong',
                'zip_code.*.required' => 'Zip code/Kode pos tidak boleh kosong',
                'telephone.*.required' => 'Telephone/Telepon tidak boleh kosong',
                'fax.*.required' => 'Fax tidak boleh kosong',
            ]);

            if ($request->business_classification == 'Other' && $request->business_classification_other_detail == NULL) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'business_classification_other_detail', 'Business classification/Jenis usaha tidak boleh kosong!'
                    );
                    // if ($this->somethingElseIsInvalid()) {
                    // }
                });
                // $validator->errors()->add(
                //     'business_classification_other_detail',
                //     'Business classification/Jenis usaha tidak boleh kosong',
                // );
                // $validator->$request->validate([
                //     'business_classification_other_detail' => 'required|string'
                // ]);
            }
            // [
            //     'business_classification_other_detail' => 'required|string'
            // ], [
            //     'business_classification_other_detail.required' => 'Business classification/Jenis usaha tidak boleh kosong',
            // ]
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // create data partner profile
            $business_class = $request->business_classification == 'Other' ? $request->business_classification_other_detail : $request->business_classification;

            if($request->signature_file != NULL) {
                $file_signature = $request->file('signature_file');
                // $file_signature_name = time().'.'.$file_signature->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' signature', '-');
                $file_signature_name = $slug_name.'.'.$file_signature->getClientOriginalExtension();
                $file_signature->move(public_path('uploads/signature'), $file_signature_name);
            }
            
            if($request->stamp_file != NULL) {
                $file_stamp = $request->file('stamp_file');
                // $file_stamp_name = time().'.'.$file_stamp->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' stamp', '-');
                $file_stamp_name = $slug_name.'.'.$file_stamp->getClientOriginalExtension();
                $file_stamp->move(public_path('uploads/stamp'), $file_stamp_name);
            }

            $data_company_partner = [
                'user_id' =>\Auth::user()->id,
                'name' => $request->company_name,
                'group_name' => $request->company_group_name,
                'type' => $request->company_type,
                'established_year' => $request->established_year,
                'total_employee' => $request->total_employee,
                'liable_person_and_position' => $request->liable_person_and_position,
                'owner_name' => $request->owner_name,
                'board_of_directors' => $request->board_of_directors,
                'major_shareholders' => $request->major_shareholders,
                'business_classification' => $request->business_classification,
                'other_business' => $request->business_classification_other_detail,
                'website_address' => $request->website_address,
                'system_management' => $request->system_management,
                'contact_person' => $request->contact_person,
                'communication_language' => $request->communication_language,
                'email_address' => $request->email_address,
                'signature' => $request->signature_file != null ? $file_signature_name : null,
                'stamp' => $request->stamp_file != null ? $file_signature_name : null,
                // 'address.*' => $request->address,
                // 'city.*' => $request->city,
                // 'country.*' => $request->country,
                // 'province.*' => $request->province,
                // 'zip_code.*' => $request->zip_code,
                // 'telephone.*' => $request->telephone,
                // 'fax.*' => $request->fax
            ];
            $partner = CompanyInfomation::create($data_company_partner);

            if ($request->address[0] != null) {
                $data_address = [];
                $list_address_data = [];
                for ($i=0; $i < count($request->address); $i++) { 
                    # code...
                    $list_address_data = [
                        'company_id' => $partner->id,
                        // 'company_id' => 'id',
                        'address' => $request->address[$i],
                        'city' => $request->city[$i],
                        'country' => $request->country[$i],
                        'province' => $request->province[$i],
                        'zip_code' => $request->zip_code[$i],
                        'telephone' => $request->telephone[$i],
                        'fax' => $request->fax[$i],
                    ];
                    array_push($data_address, $list_address_data);
                }
                // $create_address = CompanyAddress::create($data_address);
                $create_address = CompanyAddress::insert($data_address);
            }

            if ($request->bank_name [0] != null) {
                $data_bank = [];
                $list_bank_data = [];
                for ($i= 0; $i < count($request->bank_name); $i++) {
                    $list_bank_data = [
                        'company_id' => $partner->id,
                        // 'company_id' => 'id',
                        'name' => $request->bank_name[$i],
                        'branch' => $request->branch[$i],
                        'account_name' => $request->account_name[$i],
                        'city_or_country' => $request->city_or_country[$i],
                        'account_number' => $request->account_number[$i],
                        'currency' => $request->currency[$i],
                        'swift_code' => $request->swift_code[$i],
                    ];
                    array_push($data_bank, $list_bank_data);
                }
                $create_bank = CompanyBank::insert($data_bank);
            }

            if ($request->register_number_as_in_tax_invoice != null) {
                $data_tax = [
                    'company_id' => $partner->id,
                    // 'company_id' => 'id',
                    'register_number_as_in_tax_invoice' => $request->register_number_as_in_tax_invoice,
                    'trc_number' => $request->trc_number,
                    'register_number_related_branch' => $request->register_number_related_branch,
                    'valid_until' => $request->valid_until,
                    'taxable_entrepreneur_number' => $request->taxable_entrepreneur_number,
                    'tax_invoice_serial_number' => $request->tax_invoice_serial_number,
                ];
                $create_tax = CompanyTax::insert($data_tax);
            }

            if ($request->address_add_info[0] != null) {
                // CompanyAdditionalInformation
                $data_add_info = [];
                $list_add_info_data = [];
                for ($i=0; $i < count($request->address_add_info); $i++) { 
                    $list_add_info_data = [
                        'company_id' => $partner->id,
                        'type_branch' => $request->address_add_info[$i],
                        'country' => $request->country_add_info[$i],
                        'province' => $request->province_add_info[$i],
                        'city' => $request->city_add_info[$i],
                        'zip_code' => $request->zip_code_add_info[$i],
                        // 'telephone_country_code' => $request->telephone_add_info[$i],
                        'telephone' => $request->telephone_add_info[$i],
                        // 'fax_country_code',
                        'fax' => $request->fax_add_info[$i],
                        // 'main_product_name_and_brand',
                        // 'main_customer',
                        // 'main_customer_telephone'
                    ];
                    array_push($data_add_info, $list_add_info_data);
                }
                $create_info = CompanyAdditionalInformation::insert($data_add_info);
            }

            // support document PT
            if ($request->ktp_penanggung_jawab_pt != null) {
                $request->validate([
                    'ktp_penanggung_jawab_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('ktp_penanggung_jawab_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
            }
            if ($request->akte_pendirian_beserta_akte_perubahan_terakhir_pt != null) {
                $request->validate([
                    'akte_pendirian_beserta_akte_perubahan_terakhir_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('akte_pendirian_beserta_akte_perubahan_terakhir_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
            }
            if ($request->surat_kuasa_pt != null) {
                $request->validate([
                    'surat_kuasa_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_kuasa_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
            }
            if ($request->surat_keterangan_terdaftar_pajak_pt != null) {
                $request->validate([
                    'surat_keterangan_terdaftar_pajak_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_keterangan_terdaftar_pajak_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
            }
            if ($request->npwp_pt != null) {
                $request->validate([
                    'npwp_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('npwp_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->surat_pengukuhan_pengusaha_kena_pajak_pt != null) {
                $request->validate([
                    'surat_pengukuhan_pengusaha_kena_pajak_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_pengukuhan_pengusaha_kena_pajak_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->tanda_daftar_perusahaan_pt != null) {
                $request->validate([
                    'tanda_daftar_perusahaan_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('tanda_daftar_perusahaan_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt != null) {
                $request->validate([
                    'surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->siup_atau_situ_pt != null) {
                $request->validate([
                    'siup_atau_situ_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('siup_atau_situ_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->company_organization_pt != null) {
                $request->validate([
                    'company_organization_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('company_organization_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->customers_list_pt != null) {
                $request->validate([
                    'customers_list_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('customers_list_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->products_list_pt != null) {
                $request->validate([
                    'products_list_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('products_list_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->fakta_integritas_vendor_pt != null) {
                $request->validate([
                    'fakta_integritas_vendor_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('fakta_integritas_vendor_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->surat_izin_usaha_kontruksi_pt != null) {
                $request->validate([
                    'surat_izin_usaha_kontruksi_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_kontruksi_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->sertifikat_badan_usaha_pt != null) {
                $request->validate([
                    'sertifikat_badan_usaha_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('sertifikat_badan_usaha_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->angka_pengenal_import_pt != null) {
                $request->validate([
                    'angka_pengenal_import_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('angka_pengenal_import_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->nomor_induk_berusaha_pt != null) {
                $request->validate([
                    'nomor_induk_berusaha_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('nomor_induk_berusaha_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);
                
            }
            if ($request->kbli_pt != null) {
                $request->validate([
                    'kbli_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('kbli_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'jpg',
                    'document' => $file_name,
                    'document_type' => 'jpg',
                ]);

            }

            DB::commit();
            // return FormatResponseJson::success($partner, 'partner profile created successfully');
            return FormatResponseJson::success('success', 'partner profile created successfully');
        }  catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

}
