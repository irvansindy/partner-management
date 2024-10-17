<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompanyInformation;
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
    public function viewCreatePartner()
    {
        return view('cs_vendor.index');
    }
    public function detailPartner(Request $request)
    {
        return view('company.detail_company');
    }
    public function fetchCompany()
    {
        try {
            $company_profile = CompanyInformation::with(['user', 'address', 'bank', 'tax', 'attachment'])
            ->where("user_id", auth()->user()->id)->first();

            $result_detail_partner = $company_profile != null ? $company_profile : null;
            $doc_pt = null;
            $doc_cv = null;
            $doc_ud_or_pd = null;
            $doc_perorangan = null;
            $supporting_document_user = null;
            $doc_type = CompanyDocumentTypeCategories::get(['id', 'name', 'name_id_class']);
            if ($result_detail_partner != null) {
                $doc_pt = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'pt')
                ->get();
                
                $doc_cv = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'cv')
                ->get();
                
                $doc_ud_or_pd = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'ud_or_pd')
                ->get();
                
                $doc_perorangan = CompanySupportingDocument::where('company_id', $company_profile->id)
                ->where('company_doc_type', 'perorangan')
                ->get();

                $supporting_document_user = CompanySupportingDocument::where('company_id', $company_profile->id)->get();
            }

            $data = [
                $company_profile,
                $doc_type,
                'pt' => $doc_pt,
                'cv' => $doc_cv,
                'ud_or_pd' => $doc_ud_or_pd,
                'perorangan' => $doc_perorangan,
                'document' => $supporting_document_user
            ];
            return FormatResponseJson::success($data, 'Company profile fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchDocument()
    {
        try {

        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function fetchCompanyPartnerById()
    {
        try {
            $company_profile = CompanyInformation::with(['user', 'address', 'bank', 'tax', 'attachment'])
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
            // dd($doc_type);
            return FormatResponseJson::success($doc_type,'Document Type fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 400);
        }
    }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = CompanyInformation::where('user_id', auth()->user()->id)->first();
            if ($existing_data) {
                return FormatResponseJson::error(null, 'Anda sudah mendaftar.', 400);
            }
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'company_group_name' => 'required|string',
                'company_type' => 'required|string',
                'established_year' => 'required|integer',
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
                'email_address' => 'required|email|unique:company_informations,email_address',
                'stamp_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'signature_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'address.*' => 'required|string',
                'city.*' => 'required|string',
                'country.*' => 'required|string',
                'province.*' => 'required|string',
                'zip_code.*' => 'required|integer',
                'telephone.*' => 'required|integer',
                'fax.*' => 'required|integer',
            ], 
            [
            //     'company_name.required' => 'Company name/Nama perusahaan tidak boleh kosong',
            //     'company_group_name.required' => 'Company group name/Nama grup perusahaan tidak boleh kosong',
            //     'company_type.required' => 'Company type/tipe perusahaan tidak boleh kosong',
            //     'established_year.required' => 'Established since year/Tahun berdiri tidak boleh kosong',
            //     'total_employee.required' => 'Total employee/Jumlah Karyawan tidak boleh kosong',
            //     'liable_person_and_position.required' => 'Liable person/Penanggung jawab tidak boleh kosong',
            //     'owner_name.required' => 'Owner name/Nama pemilik tidak boleh kosong',
            //     'board_of_directors.required' => 'Board of directors/Dewan direktur tidak boleh kosong',
            //     'major_shareholders.required' => 'Board of directors/Pemilik saham mayoritas tidak boleh kosong',
            //     'business_classification.required' => 'Business classification/Jenis usaha tidak boleh kosong',
            //     'website_address.required' => 'Website address/Alamat situs web tidak boleh kosong',
            //     'system_management.required' => 'System management/Manajemen sistem tidak boleh kosong',
            //     'contact_person.required' => 'Contact person/Kontak person tidak boleh kosong',
            //     'communication_language.required' => 'Communication language/Bahasa komunikasi tidak boleh kosong',
            //     'email_address.required' => 'Email address/Alamat email tidak boleh kosong',
            //     'stamp_file.required' => 'Stamp/Stempel tidak boleh kosong',
            //     'signature_file.required' => 'Signature/Tanda tangan tidak boleh kosong',
                'address.*.required' => 'The address field is required',
                'city.*.required' => 'The city field is required.',
                'country.*.required' => 'The country field is required.',
                'province.*.required' => 'The province field is required.',
                'zip_code.*.required' => 'The zip_code field is required.',
                'telephone.*.required' => 'The telephone field is required.',
                'fax.*.required' => 'The fax field is required.',
                'zip_code.*.integer' => 'The zip_code field is required.',
                'telephone.*.integer' => 'The telephone field is required.',
                'fax.*.integer' => 'The fax field is required.',
            ]
        );

            if ($request->business_classification == 'Other' && $request->business_classification_other_detail == NULL) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'business_classification_other_detail', 'Business classification/Jenis usaha tidak boleh kosong!'
                    );
                });
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            if($request->signature_file != NULL) {
                $file_signature = $request->file('signature_file');
                // $file_signature_name = time().'.'.$file_signature->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' signature', '-');
                $file_signature_name = $slug_name.'.'.$file_signature->getClientOriginalExtension();
                $file_signature->move(public_path('storage/uploads/signature'), $file_signature_name);
            }
            
            if($request->stamp_file != NULL) {
                $file_stamp = $request->file('stamp_file');
                // $file_stamp_name = time().'.'.$file_stamp->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' stamp', '-');
                $file_stamp_name = $slug_name.'.'.$file_stamp->getClientOriginalExtension();
                $file_stamp->move(public_path('storage/uploads/stamp'), $file_stamp_name);
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
                'stamp' => $request->stamp_file != null ? $file_stamp_name : null,
            ];
            $partner = CompanyInformation::create($data_company_partner);

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

            // support document PT
            if ($request->ktp_penanggung_jawab_pt != null) {
                $request->validate([
                    'ktp_penanggung_jawab_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('ktp_penanggung_jawab_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_ktp_penanggung_jawab_pt,
                ]);
            }
            if ($request->akte_pendirian_beserta_akte_perubahan_terakhir_pt != null) {
                $request->validate([
                    'akte_pendirian_beserta_akte_perubahan_terakhir_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('akte_pendirian_beserta_akte_perubahan_terakhir_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_akte_pendirian_beserta_akte_perubahan_terakhir_pt,
                ]);
            }
            if ($request->surat_kuasa_pt != null) {
                $request->validate([
                    'surat_kuasa_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_kuasa_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_kuasa_pt,
                ]);
            }
            if ($request->surat_keterangan_terdaftar_pajak_pt != null) {
                $request->validate([
                    'surat_keterangan_terdaftar_pajak_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_keterangan_terdaftar_pajak_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_keterangan_terdaftar_pajak_pt,
                ]);
            }
            if ($request->npwp_pt != null) {
                $request->validate([
                    'npwp_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('npwp_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kartu_npwp_pt
                ]);
                
            }
            if ($request->surat_pengukuhan_pengusaha_kena_pajak_pt != null) {
                $request->validate([
                    'surat_pengukuhan_pengusaha_kena_pajak_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_pengukuhan_pengusaha_kena_pajak_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_pengukuhan_pengusaha_kena_pajak_pt
                ]);
                
            }
            if ($request->tanda_daftar_perusahaan_pt != null) {
                $request->validate([
                    'tanda_daftar_perusahaan_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('tanda_daftar_perusahaan_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_tanda_daftar_perusahaan_pt
                ]);
                
            }
            if ($request->surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt != null) {
                $request->validate([
                    'surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_perdagangan_atau_ijin_usaha_tetap_untuk_pma_pt
                ]);
                
            }
            if ($request->siup_atau_situ_pt != null) {
                $request->validate([
                    'siup_atau_situ_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('siup_atau_situ_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_siup_atau_situ_pt,
                ]);
                
            }
            if ($request->company_organization_pt != null) {
                $request->validate([
                    'company_organization_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('company_organization_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_company_organization_pt,
                ]);
                
            }
            if ($request->customers_list_pt != null) {
                $request->validate([
                    'customers_list_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('customers_list_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_customers_list_pt,
                ]);
                
            }
            if ($request->products_list_pt != null) {
                $request->validate([
                    'products_list_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('products_list_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_product_list_pt,
                ]);
                
            }
            if ($request->fakta_integritas_vendor_pt != null) {
                $request->validate([
                    'fakta_integritas_vendor_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('fakta_integritas_vendor_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_fakta_integritas_vendor_pt,
                ]);
                
            }
            if ($request->surat_izin_usaha_kontruksi_pt != null) {
                $request->validate([
                    'surat_izin_usaha_kontruksi_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_kontruksi_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_konstruksi_pt
                ]);
                
            }
            if ($request->sertifikat_badan_usaha_pt != null) {
                $request->validate([
                    'sertifikat_badan_usaha_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('sertifikat_badan_usaha_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_sertifikat_badan_usaha_pt,
                ]);
                
            }
            if ($request->angka_pengenal_import_pt != null) {
                $request->validate([
                    'angka_pengenal_import_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('angka_pengenal_import_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_angka_pengenal_import_pt
                ]);
                
            }
            if ($request->nomor_induk_berusaha_pt != null) {
                $request->validate([
                    'nomor_induk_berusaha_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('nomor_induk_berusaha_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_nomor_induk_berusaha_pt
                ]);
                
            }
            if ($request->kbli_pt != null) {
                $request->validate([
                    'kbli_pt' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('kbli_pt');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/pt'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'pt',
                    'document' => 'storage/uploads/pt/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kbli_pt,
                ]);

            }

            // support document CV
            if ($request->ktp_penanggung_jawab_cv != null) {
                $request->validate([
                    'ktp_penanggung_jawab_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('ktp_penanggung_jawab_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_ktp_penanggung_jawab_cv,
                ]);
            }
            if ($request->akte_pendirian_beserta_akte_perubahan_terakhir_cv != null) {
                $request->validate([
                    'akte_pendirian_beserta_akte_perubahan_terakhir_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('akte_pendirian_beserta_akte_perubahan_terakhir_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_akte_pendirian_beserta_akte_perubahan_terakhir_cv,
                ]);
            }
            if ($request->surat_kuasa_cv != null) {
                $request->validate([
                    'surat_kuasa_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_kuasa_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_kuasa_cv,

                ]);
            }
            if ($request->surat_keterangan_terdaftar_pajak_cv != null) {
                $request->validate([
                    'surat_keterangan_terdaftar_pajak_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_keterangan_terdaftar_pajak_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_keterangan_terdaftar_pajak_cv,

                ]);
            }
            if ($request->npwp_cv != null) {
                $request->validate([
                    'npwp_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('npwp_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kartu_npwp_cv
                ]);
                
            }
            if ($request->surat_pengukuhan_pengusaha_kena_pajak_cv != null) {
                $request->validate([
                    'surat_pengukuhan_pengusaha_kena_pajak_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_pengukuhan_pengusaha_kena_pajak_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_pengukuhan_pengusaha_kena_pajak_cv,
                ]);
                
            }
            if ($request->tanda_daftar_perusahaan_cv != null) {
                $request->validate([
                    'tanda_daftar_perusahaan_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('tanda_daftar_perusahaan_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_tanda_daftar_perusahaan_cv
                ]);
                
            }
            if ($request->surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_cv != null) {
                $request->validate([
                    'surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_perdagangan_atau_ijin_usaha_tetap_untuk_pma_cv
                ]);
                
            }
            if ($request->siup_atau_situ_cv != null) {
                $request->validate([
                    'siup_atau_situ_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('siup_atau_situ_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_siup_atau_situ_cv
                ]);
                
            }
            if ($request->company_organization_cv != null) {
                $request->validate([
                    'company_organization_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('company_organization_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_company_organization_cv
                ]);
                
            }
            if ($request->customers_list_cv != null) {
                $request->validate([
                    'customers_list_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('customers_list_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_customers_list_cv,

                ]);
                
            }
            if ($request->products_list_cv != null) {
                $request->validate([
                    'products_list_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('products_list_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_product_list_cv,
                ]);
                
            }
            if ($request->fakta_integritas_vendor_cv != null) {
                $request->validate([
                    'fakta_integritas_vendor_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('fakta_integritas_vendor_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_fakta_integritas_vendor_cv,
                ]);
                
            }
            if ($request->surat_izin_usaha_kontruksi_cv != null) {
                $request->validate([
                    'surat_izin_usaha_kontruksi_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_kontruksi_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_konstruksi_cv,
                ]);
                
            }
            if ($request->sertifikat_badan_usaha_cv != null) {
                $request->validate([
                    'sertifikat_badan_usaha_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('sertifikat_badan_usaha_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_sertifikat_badan_usaha_cv,
                ]);
                
            }
            if ($request->angka_pengenal_import_cv != null) {
                $request->validate([
                    'angka_pengenal_import_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('angka_pengenal_import_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_angka_pengenal_import_cv,
                ]);
                
            }
            if ($request->nomor_induk_berusaha_cv != null) {
                $request->validate([
                    'nomor_induk_berusaha_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('nomor_induk_berusaha_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_nomor_induk_berusaha_cv
                ]);
                
            }
            if ($request->kbli_cv != null) {
                $request->validate([
                    'kbli_cv' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('kbli_cv');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/cv'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'cv',
                    'document' => 'storage/uploads/cv/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kbli_cv
                ]);

            }
            
            // support document UD or OD
            if ($request->ktp_penanggung_jawab_ud_or_pd != null) {
                $request->validate([
                    'ktp_penanggung_jawab_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('ktp_penanggung_jawab_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_ktp_penanggung_jawab_ud_or_pd,
                ]);
            }
            if ($request->akte_pendirian_beserta_akte_perubahan_terakhir_ud_or_pd != null) {
                $request->validate([
                    'akte_pendirian_beserta_akte_perubahan_terakhir_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('akte_pendirian_beserta_akte_perubahan_terakhir_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_akte_pendirian_beserta_akte_perubahan_terakhir_ud_or_pd,
                ]);
            }
            if ($request->surat_kuasa_ud_or_pd != null) {
                $request->validate([
                    'surat_kuasa_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_kuasa_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_kuasa_ud_or_pd,
            
                ]);
            }
            if ($request->surat_keterangan_terdaftar_pajak_ud_or_pd != null) {
                $request->validate([
                    'surat_keterangan_terdaftar_pajak_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_keterangan_terdaftar_pajak_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_keterangan_terdaftar_pajak_ud_or_pd,
            
                ]);
            }
            if ($request->npwp_ud_or_pd != null) {
                $request->validate([
                    'npwp_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('npwp_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kartu_npwp_ud_or_pd
                ]);
                
            }
            if ($request->surat_pengukuhan_pengusaha_kena_pajak_ud_or_pd != null) {
                $request->validate([
                    'surat_pengukuhan_pengusaha_kena_pajak_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_pengukuhan_pengusaha_kena_pajak_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_pengukuhan_pengusaha_kena_pajak_ud_or_pd,
                ]);
                
            }
            if ($request->tanda_daftar_perusahaan_ud_or_pd != null) {
                $request->validate([
                    'tanda_daftar_perusahaan_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('tanda_daftar_perusahaan_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_tanda_daftar_perusahaan_ud_or_pd
                ]);
                
            }
            if ($request->surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_ud_or_pd != null) {
                $request->validate([
                    'surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_perdagangan_atau_ijin_usaha_tetap_untuk_pma_ud_or_pd
                ]);
                
            }
            if ($request->siup_atau_situ_ud_or_pd != null) {
                $request->validate([
                    'siup_atau_situ_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('siup_atau_situ_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_siup_atau_situ_ud_or_pd
                ]);
                
            }
            if ($request->company_organization_ud_or_pd != null) {
                $request->validate([
                    'company_organization_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('company_organization_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_company_organization_ud_or_pd
                ]);
                
            }
            if ($request->customers_list_ud_or_pd != null) {
                $request->validate([
                    'customers_list_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('customers_list_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_customers_list_ud_or_pd,
            
                ]);
                
            }
            if ($request->products_list_ud_or_pd != null) {
                $request->validate([
                    'products_list_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('products_list_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_product_list_ud_or_pd,
                ]);
                
            }
            if ($request->fakta_integritas_vendor_ud_or_pd != null) {
                $request->validate([
                    'fakta_integritas_vendor_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('fakta_integritas_vendor_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_fakta_integritas_vendor_ud_or_pd,
                ]);
                
            }
            if ($request->surat_izin_usaha_kontruksi_ud_or_pd != null) {
                $request->validate([
                    'surat_izin_usaha_kontruksi_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_kontruksi_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_konstruksi_ud_or_pd,
                ]);
                
            }
            if ($request->sertifikat_badan_usaha_ud_or_pd != null) {
                $request->validate([
                    'sertifikat_badan_usaha_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('sertifikat_badan_usaha_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_sertifikat_badan_usaha_ud_or_pd,
                ]);
                
            }
            if ($request->angka_pengenal_import_ud_or_pd != null) {
                $request->validate([
                    'angka_pengenal_import_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('angka_pengenal_import_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_angka_pengenal_import_ud_or_pd,
                ]);
                
            }
            if ($request->nomor_induk_berusaha_ud_or_pd != null) {
                $request->validate([
                    'nomor_induk_berusaha_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('nomor_induk_berusaha_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_nomor_induk_berusaha_ud_or_pd
                ]);
                
            }
            if ($request->kbli_ud_or_pd != null) {
                $request->validate([
                    'kbli_ud_or_pd' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('kbli_ud_or_pd');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/ud_or_pd'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'ud_or_pd',
                    'document' => 'storage/uploads/ud_or_pd/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kbli_ud_or_pd
                ]);
            
            }
            
            // support document Perorangan
            if ($request->ktp_penanggung_jawab_perorangan != null) {
                $request->validate([
                    'ktp_penanggung_jawab_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('ktp_penanggung_jawab_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_ktp_penanggung_jawab_perorangan,
                ]);
            }
            if ($request->akte_pendirian_beserta_akte_perubahan_terakhir_perorangan != null) {
                $request->validate([
                    'akte_pendirian_beserta_akte_perubahan_terakhir_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('akte_pendirian_beserta_akte_perubahan_terakhir_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_akte_pendirian_beserta_akte_perubahan_terakhir_perorangan,
                ]);
            }
            if ($request->surat_kuasa_perorangan != null) {
                $request->validate([
                    'surat_kuasa_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_kuasa_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_kuasa_perorangan,
            
                ]);
            }
            if ($request->surat_keterangan_terdaftar_pajak_perorangan != null) {
                $request->validate([
                    'surat_keterangan_terdaftar_pajak_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_keterangan_terdaftar_pajak_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_keterangan_terdaftar_pajak_perorangan,
            
                ]);
            }
            if ($request->npwp_perorangan != null) {
                $request->validate([
                    'npwp_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('npwp_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kartu_npwp_perorangan
                ]);
                
            }
            if ($request->surat_pengukuhan_pengusaha_kena_pajak_perorangan != null) {
                $request->validate([
                    'surat_pengukuhan_pengusaha_kena_pajak_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_pengukuhan_pengusaha_kena_pajak_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_pengukuhan_pengusaha_kena_pajak_perorangan,
                ]);
                
            }
            if ($request->tanda_daftar_perusahaan_perorangan != null) {
                $request->validate([
                    'tanda_daftar_perusahaan_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('tanda_daftar_perusahaan_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_tanda_daftar_perusahaan_perorangan
                ]);
                
            }
            if ($request->surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_perorangan != null) {
                $request->validate([
                    'surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_perdagangan_atau_izin_usaha_tetap_untuk_pma_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_perdagangan_atau_ijin_usaha_tetap_untuk_pma_perorangan
                ]);
                
            }
            if ($request->siup_atau_situ_perorangan != null) {
                $request->validate([
                    'siup_atau_situ_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('siup_atau_situ_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_siup_atau_situ_perorangan
                ]);
                
            }
            if ($request->company_organization_perorangan != null) {
                $request->validate([
                    'company_organization_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('company_organization_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_company_organization_perorangan
                ]);
                
            }
            if ($request->customers_list_perorangan != null) {
                $request->validate([
                    'customers_list_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('customers_list_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_customers_list_perorangan,
            
                ]);
                
            }
            if ($request->products_list_perorangan != null) {
                $request->validate([
                    'products_list_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('products_list_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_product_list_perorangan,
                ]);
                
            }
            if ($request->fakta_integritas_vendor_perorangan != null) {
                $request->validate([
                    'fakta_integritas_vendor_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('fakta_integritas_vendor_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_fakta_integritas_vendor_perorangan,
                ]);
                
            }
            if ($request->surat_izin_usaha_kontruksi_perorangan != null) {
                $request->validate([
                    'surat_izin_usaha_kontruksi_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('surat_izin_usaha_kontruksi_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_surat_izin_usaha_konstruksi_perorangan,
                ]);
                
            }
            if ($request->sertifikat_badan_usaha_perorangan != null) {
                $request->validate([
                    'sertifikat_badan_usaha_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('sertifikat_badan_usaha_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_sertifikat_badan_usaha_perorangan,
                ]);
                
            }
            if ($request->angka_pengenal_import_perorangan != null) {
                $request->validate([
                    'angka_pengenal_import_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('angka_pengenal_import_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_angka_pengenal_import_perorangan,
                ]);
                
            }
            if ($request->nomor_induk_berusaha_perorangan != null) {
                $request->validate([
                    'nomor_induk_berusaha_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('nomor_induk_berusaha_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_nomor_induk_berusaha_perorangan
                ]);
                
            }
            if ($request->kbli_perorangan != null) {
                $request->validate([
                    'kbli_perorangan' => 'required|mimes:png,jpg,jpeg,pdf'
                ]);
                $file = $request->file('kbli_perorangan');
                $file_name = time().'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/uploads/perorangan'), $file_name);
                CompanySupportingDocument::create([
                    'company_id'=> $partner->id,
                    'company_doc_type' => 'perorangan',
                    'document' => 'storage/uploads/perorangan/'.$file_name,
                    'document_type' => $file->getClientOriginalExtension(),
                    'document_type_name' => $request->doc_name_kbli_perorangan
                ]);
            
            }

            DB::commit();
            // return FormatResponseJson::success($partner, 'partner profile created successfully');
            return FormatResponseJson::success('success', 'partner profile created successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = CompanyInformation::where('id', $request->detail_id)->first();
            
            // Dynamic validation rules
            $rules = [
                'detail_company_name' => 'required|string',
                'detail_company_group_name' => 'required|string',
                'detail_company_type' => 'required|string',
                'detail_established_year' => 'required|string',
                'detail_total_employee' => 'required|string',
                'detail_liable_person_and_position' => 'required|string',
                'detail_owner_name' => 'required|string',
                'detail_board_of_directors' => 'required|string',
                'detail_major_shareholders' => 'required|string',
                'detail_business_classification' => 'required|string',
                'detail_website_address' => 'required|string',
                'detail_system_management' => 'required|string',
                'detail_contact_person' => 'required|string',
                'detail_communication_language' => 'required|string',
                'detail_email_address' => 'required|string|email',
                'detail_address.*' => 'required|string',
                'detail_city.*' => 'required|string',
                'detail_country.*' => 'required|string',
                'detail_province.*' => 'required|string',
                'detail_zip_code.*' => 'required|integer',
                'detail_telephone.*' => 'required|string',
                'detail_fax.*' => 'required|string',
            ];
            
            // Only require stamp and signature if they are not already set
            if ($existing_data->stamp == null) {
                if($request->detail_stamp_file == null) {
                    $rules['detail_stamp_file'] = 'required|image|max:10000|mimes:jpg,jpeg,png';
                }
            }

            if ($existing_data->signature == null) {
                if($request->detail_signature_file == null) {
                    $rules['detail_signature_file'] = 'required|image|max:10000|mimes:jpg,jpeg,png';
                }
            }

            // Validation messages
            $messages = [
                'detail_company_name.required' => 'Company name/Nama perusahaan tidak boleh kosong',
                'detail_company_group_name.required' => 'Company group name/Nama grup perusahaan tidak boleh kosong',
                'detail_company_type.required' => 'Company type/tipe perusahaan tidak boleh kosong',
                'detail_established_year.required' => 'Established since year/Tahun berdiri tidak boleh kosong',
                'detail_total_employee.required' => 'Total employee/Jumlah Karyawan tidak boleh kosong',
                'detail_liable_person_and_position.required' => 'Liable person/Penanggung jawab tidak boleh kosong',
                'detail_owner_name.required' => 'Owner name/Nama pemilik tidak boleh kosong',
                'detail_board_of_directors.required' => 'Board of directors/Dewan direktur tidak boleh kosong',
                'detail_major_shareholders.required' => 'Board of directors/Pemilik saham mayoritas tidak boleh kosong',
                'detail_business_classification.required' => 'Business classification/Jenis usaha tidak boleh kosong',
                'detail_website_address.required' => 'Website address/Alamat situs web tidak boleh kosong',
                'detail_system_management.required' => 'System management/Manajemen sistem tidak boleh kosong',
                'detail_contact_person.required' => 'Contact person/Kontak person tidak boleh kosong',
                'detail_communication_language.required' => 'Communication language/Bahasa komunikasi tidak boleh kosong',
                'detail_email_address.required' => 'Email address/Alamat email tidak boleh kosong',
                'detail_stamp_file.required' => 'Stamp/Stempel tidak boleh kosong',
                'detail_signature_file.required' => 'Signature/Tanda tangan tidak boleh kosong',
                'detail_address.*.required' => 'Address/Alamat tidak boleh kosong',
                'detail_city.*.required' => 'City/Kota tidak boleh kosong',
                'detail_country.*.required' => 'Country/Negara tidak boleh kosong',
                'detail_province.*.required' => 'Province/Provinsi tidak boleh kosong',
                'detail_zip_code.*.required' => 'Zip code/Kode pos tidak boleh kosong',
                'detail_telephone.*.required' => 'Telephone/Telepon tidak boleh kosong',
                'detail_fax.*.required' => 'Fax tidak boleh kosong',
            ];

            // Apply validation
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check and delete existing signature file if a new one is provided
            if ($request->hasFile('detail_signature_file')) {
                if ($existing_data->signature != null) {
                    // Delete old signature file
                    $oldSignaturePath = public_path('storage/uploads/signature/' . $existing_data->signature);
                    if (file_exists($oldSignaturePath)) {
                        unlink($oldSignaturePath);
                    }
                }
                // Handle new signature file upload
                $file_signature = $request->file('detail_signature_file');
                $slug_name = Str::slug($request->detail_company_name . ' signature', '-');
                $file_signature_name = $slug_name . '.' . $file_signature->getClientOriginalExtension();
                // Save signature file (example: move it to storage/uploads/signature)
                $file_signature->move(public_path('storage/uploads/signature'), $file_signature_name);
            } else {
                // Keep the old signature if no new one is uploaded
                $file_signature_name = $existing_data->signature;
            }

            // Check and delete existing stamp file if a new one is provided
            if ($request->hasFile('detail_stamp_file')) {
                if ($existing_data->stamp != null) {
                    // Delete old stamp file
                    $oldStampPath = public_path('storage/uploads/stamp/' . $existing_data->stamp);
                    if (file_exists($oldStampPath)) {
                        unlink($oldStampPath);
                    }
                }
                // Handle new stamp file upload
                $file_stamp = $request->file('detail_stamp_file');
                $slug_name = Str::slug($request->detail_company_name . ' stamp', '-');
                $file_stamp_name = $slug_name . '.' . $file_stamp->getClientOriginalExtension();
                // Save stamp file (example: move it to storage/uploads/stamp)
                $file_stamp->move(public_path('storage/uploads/stamp'), $file_stamp_name);
            } else {
                // Keep the old stamp if no new one is uploaded
                $file_stamp_name = $existing_data->stamp;
            }

            // Update company information
            $data_company_partner = [
                // 'user_id' => \Auth::user()->id,
                'name' => $request->detail_company_name,
                'group_name' => $request->detail_company_group_name,
                'type' => $request->detail_company_type,
                'established_year' => $request->detail_established_year,
                'total_employee' => $request->detail_total_employee,
                'liable_person_and_position' => $request->detail_liable_person_and_position,
                'owner_name' => $request->detail_owner_name,
                'board_of_directors' => $request->detail_board_of_directors,
                'major_shareholders' => $request->detail_major_shareholders,
                'business_classification' => $request->detail_business_classification,
                'other_business' => $request->detail_business_classification_other_detail,
                'website_address' => $request->detail_website_address,
                'system_management' => $request->detail_system_management,
                'contact_person' => $request->detail_contact_person,
                'communication_language' => $request->detail_communication_language,
                'email_address' => $request->detail_email_address,
                'signature' => $request->detail_signature_file != null ? $file_signature_name : $existing_data->signature,
                'stamp' => $request->detail_stamp_file != null ? $file_stamp_name : $existing_data->stamp,
            ];
            // updating data
            $existing_data->update($data_company_partner);
            // Commit changes
            DB::commit();
            return FormatResponseJson::success('success', 'partner profile updated successfully');
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
    public function update2(Request $request)
{
    try {
        DB::beginTransaction();
        $existing_data = CompanyInformation::where('id', $request->detail_id)->first();
        
        // Validate input
        $validator = Validator::make($request->all(), [
            'detail_company_name' => 'required|string',
            'detail_company_group_name' => 'required|string',
            'detail_company_type' => 'required|string',
            'detail_established_year' => 'required|string',
            'detail_total_employee' => 'required|string',
            'detail_liable_person_and_position' => 'required|string',
            'detail_owner_name' => 'required|string',
            'detail_board_of_directors' => 'required|string',
            'detail_major_shareholders' => 'required|string',
            'detail_business_classification' => 'required|string',
            'detail_website_address' => 'required|string',
            'detail_system_management' => 'required|string',
            'detail_contact_person' => 'required|string',
            'detail_communication_language' => 'required|string',
            'detail_email_address' => 'required|string|email',
            'detail_stamp_file' => 'image|max:10000|mimes:jpg,jpeg,png', // Signature/Stamp files can be optional if old file exists
            'detail_signature_file' => 'image|max:10000|mimes:jpg,jpeg,png',
            'detail_address.*' => 'required|string',
            'detail_city.*' => 'required|string',
            'detail_country.*' => 'required|string',
            'detail_province.*' => 'required|string',
            'detail_zip_code.*' => 'required|string',
            'detail_telephone.*' => 'required|string',
            'detail_fax.*' => 'required|string',
        ], [
            // Custom validation error messages (same as before)
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Check and delete existing signature file if a new one is provided
        if ($request->hasFile('detail_signature_file')) {
            if ($existing_data->signature != null) {
                // Delete old signature file
                $oldSignaturePath = public_path('storage/uploads/signature/' . $existing_data->signature);
                if (file_exists($oldSignaturePath)) {
                    unlink($oldSignaturePath);
                }
            }
            // Handle new signature file upload
            $file_signature = $request->file('detail_signature_file');
            $slug_name = Str::slug($request->detail_company_name . ' signature', '-');
            $file_signature_name = $slug_name . '.' . $file_signature->getClientOriginalExtension();
            // Save signature file (example: move it to storage/uploads/signature)
            $file_signature->move(public_path('storage/uploads/signature'), $file_signature_name);
        } else {
            // Keep the old signature if no new one is uploaded
            $file_signature_name = $existing_data->signature;
        }

        // Check and delete existing stamp file if a new one is provided
        if ($request->hasFile('detail_stamp_file')) {
            if ($existing_data->stamp != null) {
                // Delete old stamp file
                $oldStampPath = public_path('storage/uploads/stamp/' . $existing_data->stamp);
                if (file_exists($oldStampPath)) {
                    unlink($oldStampPath);
                }
            }
            // Handle new stamp file upload
            $file_stamp = $request->file('detail_stamp_file');
            $slug_name = Str::slug($request->detail_company_name . ' stamp', '-');
            $file_stamp_name = $slug_name . '.' . $file_stamp->getClientOriginalExtension();
            // Save stamp file (example: move it to storage/uploads/stamp)
            $file_stamp->move(public_path('storage/uploads/stamp'), $file_stamp_name);
        } else {
            // Keep the old stamp if no new one is uploaded
            $file_stamp_name = $existing_data->stamp;
        }

        // Update company information
        $data_company_partner = [
            'user_id' => \Auth::user()->id,
            'name' => $request->detail_company_name,
            'group_name' => $request->detail_company_group_name,
            'type' => $request->detail_company_type,
            'established_year' => $request->detail_established_year,
            'total_employee' => $request->detail_total_employee,
            'liable_person_and_position' => $request->detail_liable_person_and_position,
            'owner_name' => $request->detail_owner_name,
            'board_of_directors' => $request->detail_board_of_directors,
            'major_shareholders' => $request->detail_major_shareholders,
            'business_classification' => $request->detail_business_classification,
            'other_business' => $request->business_classification_other_detail,
            'website_address' => $request->detail_website_address,
            'system_management' => $request->detail_system_management,
            'contact_person' => $request->detail_contact_person,
            'communication_language' => $request->detail_communication_language,
            'email_address' => $request->detail_email_address,
            'signature' => $file_signature_name,
            'stamp' => $file_stamp_name,
        ];

        // Save the updated company information
        $existing_data->update($data_company_partner);

        DB::commit();
        return FormatResponseJson::success(null, 'Company information updated successfully');
    } catch (ValidationException $e) {
        DB::rollback();
        return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
    } catch (\Throwable $e) {
        DB::rollback();
        return FormatResponseJson::error(null, $e->getMessage(), 500);
    }
    }
    public function updateOld(Request $request)
    {
        try {
            DB::beginTransaction();
            $existing_data = CompanyInformation::where('id', $request->detail_id)->first();
            if ($existing_data->signature != null) {
                dd($existing_data->signature);
            }
            $validator = Validator::make($request->all(), [
                'detail_company_name' => 'required|string',
                'detail_company_group_name' => 'required|string',
                'detail_company_type' => 'required|string',
                'detail_established_year' => 'required|string',
                'detail_total_employee' => 'required|string',
                'detail_liable_person_and_position' => 'required|string',
                'detail_owner_name' => 'required|string',
                'detail_board_of_directors' => 'required|string',
                'detail_major_shareholders' => 'required|string',
                'detail_business_classification' => 'required|string',
                'detail_website_address' => 'required|string',
                'detail_system_management' => 'required|string',
                'detail_contact_person' => 'required|string',
                'detail_communication_language' => 'required|string',
                'detail_email_address' => 'required|string|email',
                'detail_stamp_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'detail_signature_file' => 'required|image|max:10000|mimes:jpg,jpeg,png',
                'detail_address.*' => 'required|string',
                'detail_city.*' => 'required|string',
                'detail_country.*' => 'required|string',
                'detail_province.*' => 'required|string',
                'detail_zip_code.*' => 'required|string',
                'detail_telephone.*' => 'required|string',
                'detail_fax.*' => 'required|string',
            ], [
                'detail_company_name.required' => 'Company name/Nama perusahaan tidak boleh kosong',
                'detail_company_group_name.required' => 'Company group name/Nama grup perusahaan tidak boleh kosong',
                'detail_company_type.required' => 'Company type/tipe perusahaan tidak boleh kosong',
                'detail_established_year.required' => 'Established since year/Tahun berdiri tidak boleh kosong',
                'detail_total_employee.required' => 'Total employee/Jumlah Karyawan tidak boleh kosong',
                'detail_liable_person_and_position.required' => 'Liable person/Penanggung jawab tidak boleh kosong',
                'detail_owner_name.required' => 'Owner name/Nama pemilik tidak boleh kosong',
                'detail_board_of_directors.required' => 'Board of directors/Dewan direktur tidak boleh kosong',
                'detail_major_shareholders.required' => 'Board of directors/Pemilik saham mayoritas tidak boleh kosong',
                'detail_business_classification.required' => 'Business classification/Jenis usaha tidak boleh kosong',
                'detail_website_address.required' => 'Website address/Alamat situs web tidak boleh kosong',
                'detail_system_management.required' => 'System management/Manajemen sistem tidak boleh kosong',
                'detail_contact_person.required' => 'Contact person/Kontak person tidak boleh kosong',
                'detail_communication_language.required' => 'Communication language/Bahasa komunikasi tidak boleh kosong',
                'detail_email_address.required' => 'Email address/Alamat email tidak boleh kosong',
                'detail_stamp_file.required' => 'Stamp/Stempel tidak boleh kosong',
                'detail_signature_file.required' => 'Signature/Tanda tangan tidak boleh kosong',
                'detail_address.*.required' => 'Address/Alamat tidak boleh kosong',
                'detail_city.*.required' => 'City/Kota tidak boleh kosong',
                'detail_country.*.required' => 'Country/Negara tidak boleh kosong',
                'detail_province.*.required' => 'Province/Provinsi tidak boleh kosong',
                'detail_zip_code.*.required' => 'Zip code/Kode pos tidak boleh kosong',
                'detail_telephone.*.required' => 'Telephone/Telepon tidak boleh kosong',
                'detail_fax.*.required' => 'Fax tidak boleh kosong',
            ]);

            if ($existing_data->signature != null) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'detail_stamp_file', 'Business classification/Jenis usaha tidak boleh kosong!'
                    );
                });
            }
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            if($request->signature_file != NULL) {
                $file_signature = $request->file('signature_file');
                // $file_signature_name = time().'.'.$file_signature->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' signature', '-');
                $file_signature_name = $slug_name.'.'.$file_signature->getClientOriginalExtension();
                // $file_signature->move(public_path('storage/uploads/signature'), $file_signature_name);
            }
            
            if($request->stamp_file != NULL) {
                $file_stamp = $request->file('stamp_file');
                // $file_stamp_name = time().'.'.$file_stamp->getClientOriginalExtension();
                $slug_name = Str::slug($request->company_name.' stamp', '-');
                $file_stamp_name = $slug_name.'.'.$file_stamp->getClientOriginalExtension();
                // $file_stamp->move(public_path('storage/uploads/stamp'), $file_stamp_name);
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
                'stamp' => $request->stamp_file != null ? $file_stamp_name : null,
            ];

            $existing_data->update($data_company_partner);
            DB::commit();
        } catch (ValidationException $e) {
            // Return validation errors as JSON response
            DB::rollback();
            return FormatResponseJson::error(null, ['errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            DB::rollback();
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }
}
