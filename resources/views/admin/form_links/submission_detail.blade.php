@extends('adminlte::page')

@section('title', 'Submission Detail')

@section('content_header')
    <h1>Submission Detail: {{ $company->name }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('admin.form-links.submissions', $formLink) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Submissions
                </a>
            </div>
        </div>

        <!-- Company Information -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Company Information</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Company Type</th>
                        <td>
                            <span class="badge badge-{{ $company->type === 'vendor' ? 'info' : 'success' }}">
                                {{ ucfirst($company->type) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Company Name</th>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <th>Company Group Name</th>
                        <td>{{ $company->group_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Established Year</th>
                        <td>{{ $company->established_year ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Employee</th>
                        <td>{{ $company->total_employee ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Business Classification</th>
                        <td>{{ $company->business_classification }}</td>
                    </tr>
                    <tr>
                        <th>Business Classification Detail</th>
                        <td>{{ $company->business_classification_detail ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tax Register Number</th>
                        <td>{{ $company->register_number_as_in_tax_invoice ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Website Address</th>
                        <td>
                            @if ($company->website_address)
                                <a href="{{ $company->website_address }}"
                                    target="_blank">{{ $company->website_address }}</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Certificate Type</th>
                        <td>{{ $company->system_management ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td>{{ $company->email_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Credit Limit</th>
                        <td>{{ $company->credit_limit ? number_format($company->credit_limit, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Term of Payment</th>
                        <td>{{ $company->term_of_payment ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Submitted At</th>
                        <td>{{ $company->created_at->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Liable Persons -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Liable Persons</h3>
            </div>
            <div class="card-body">
                @if ($company->liablePeople->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>NIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->liablePeople as $liable)
                                <tr>
                                    <td>{{ $liable->name }}</td>
                                    <td>{{ $liable->position }}</td>
                                    <td>{{ $liable->nik }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No liable persons recorded</p>
                @endif
            </div>
        </div>

        <!-- Contact Persons -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Contact Persons</h3>
            </div>
            <div class="card-body">
                @if ($company->contact->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telephone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->contact as $contact)
                                <tr>
                                    <td>{{ $contact->department }}</td>
                                    <td>{{ $contact->position }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->telephone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No contacts recorded</p>
                @endif
            </div>
        </div>

        <!-- Addresses -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Addresses</h3>
            </div>
            <div class="card-body">
                @if ($company->address->isNotEmpty())
                    @foreach ($company->address as $index => $address)
                        <div class="mb-4">
                            <h5>Address {{ $index + 1 }}</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Address</th>
                                    <td>{{ $address->address }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $address->country }}</td>
                                </tr>
                                <tr>
                                    <th>Province</th>
                                    <td>{{ $address->province }}</td>
                                </tr>
                                <tr>
                                    <th>City</th>
                                    <td>{{ $address->city }}</td>
                                </tr>
                                <tr>
                                    <th>Postal Code</th>
                                    <td>{{ $address->zip_code }}</td>
                                </tr>
                                <tr>
                                    <th>Telephone</th>
                                    <td>{{ $address->telephone }}</td>
                                </tr>
                                <tr>
                                    <th>Fax</th>
                                    <td>{{ $address->fax ?? '-' }}</td>
                                </tr>
                                @if ($address->latitude && $address->longitude)
                                    <tr>
                                        <th>Coordinates</th>
                                        <td>
                                            Lat: {{ $address->latitude }}, Long: {{ $address->longitude }}
                                            <br>
                                            <a href="https://www.google.com/maps?q={{ $address->latitude }},{{ $address->longitude }}"
                                                target="_blank" class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-map-marker-alt"></i> View on Map
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No addresses recorded</p>
                @endif
            </div>
        </div>

        <!-- Banks -->
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Bank Information</h3>
            </div>
            <div class="card-body">
                @if ($company->bank->isNotEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Bank Name</th>
                                <th>Account Name</th>
                                <th>Account Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($company->bank as $bank)
                                <tr>
                                    <td>{{ $bank->name }}</td>
                                    <td>{{ $bank->account_name }}</td>
                                    <td>{{ $bank->account_number }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No bank information recorded</p>
                @endif
            </div>
        </div>

        <!-- Survey Data (only for customer) -->
        @if ($company->type === 'customer' && $company->salesSurvey)
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Survey Data</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Ownership Status</th>
                            <td>
                                @if ($company->salesSurvey->ownership_status)
                                    <span
                                        class="badge badge-{{ $company->salesSurvey->ownership_status === 'own' ? 'success' : 'info' }}">
                                        {{ ucfirst($company->salesSurvey->ownership_status) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @if ($company->salesSurvey->rental_year)
                            <tr>
                                <th>Rental Year</th>
                                <td>{{ $company->salesSurvey->rental_year }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Pick Up</th>
                            <td>{{ $company->salesSurvey->pick_up ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Truck</th>
                            <td>{{ $company->salesSurvey->truck ?? '-' }}</td>
                        </tr>
                    </table>

                    @if ($company->productCustomers->isNotEmpty())
                        <h5 class="mt-4">Products</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Merk</th>
                                    <th>Distributor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->productCustomers as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->merk ?? '-' }}</td>
                                        <td>{{ $product->distributor ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif
        <!-- Attachments -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">File Attachments</h3>
            </div>
            <div class="card-body">
                @if ($company->attachment->isNotEmpty())
                    <div class="row">
                        @foreach ($company->attachment as $file)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        @if (str_contains($file->filetype, 'image'))
                                            <img src="{{ asset('storage/' . $file->filepath) }}"
                                                alt="{{ $file->filename }}" class="img-fluid mb-2"
                                                style="max-height: 150px;">
                                        @else
                                            <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                        @endif
                                        <p class="small mb-2">{{ $file->filename }}</p>
                                        <p class="small text-muted">{{ number_format($file->filesize / 1024, 2) }} KB</p>
                                        <a href="{{ asset('storage/' . $file->filepath) }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No attachments</p>
                @endif
            </div>
        </div>
    </div>
@stop
