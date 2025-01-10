@php
    $baseUrl = asset('assest')."/";
@endphp
@extends('layouts.app-main')
@section('title', 'Admin | Firmendaten ')
@section('content')
    <section class="mainSection">
        <div class="homeSectionPart">
            <div class="company_details">
                <div class="companySec">
                    <div class="addCommonBtn">
                        <button class="btn btnAdd edit-company-profile" data-bs-toggle="modal" data-bs-target="#companyDetailModal"><i class="fa-solid fa-edit"></i> Firmendetails bearbeiten</button>
                    </div>
                    <div class="titleBox">
                        <h5>Firmendaten</h5>
                    </div>
                    @if($companyData)
                        <div class="detailsCompany">
                            <ul>
                                <li><p>Name:</p><h6>{{ $companyData->name }}</h6></li>
                                <li><p>Art:</p><h6>{{ $companyData->type }}</h6></li>
                                <li><p>Adresse:</p><pre><h6 class="adress">{{ $companyData->address }}</h6></pre></li>
                                <li><p>Geschäftsführer:</p><h6>{{ $companyData->managing_director }}</h6></li>
                                <li><p>Bankname:</p><h6>{{ $companyData->bank_name }}</h6></li>
                                <li><p>IBAN:</p><h6>{{ $companyData->iban_number }}</h6></li>
                                <li><p>BIC:</p><h6>{{ $companyData->bic_number }}</h6></li>
                                <li><p>Ust.-ID:</p><h6>{{ $companyData->vat_id }}</h6></li>
                                <li><p>Handelregister:</p><h6>{{ $companyData->trade_register }}</h6></li>
                                <li><p>E-Mail:</p><h6>{{ $companyData->email }}</h6></li>
                                <li><p>Telefon:</p><h6>{{ $companyData->phone }}</h6></li>
                                <li><p>Web:</p><h6>{{ $companyData->website_url }}</h6></li>
                            </ul>
                        </div>
                        <div class="companyFile">
                            <ul>
                                <li>
                                    <a href="{{asset('storage/Company Documents/' . $companyData->company_presentation_word)}}"><i class="fa-solid fa-file-lines"></i> Unternehmensvorstellung Word
                                    </a>
                                </li>
                                <li>
                                    <a href="{{asset('storage/Company Documents/' . $companyData->company_presentation_pdf)}}" target="_blank"><i class="fa-solid fa-file-lines"></i> Unternehmensvorstellung PDF
                                    </a>
                                </li>
                                <li>
                                    <a href="{{asset('storage/Company Documents/' . $companyData->agile_framework_word)}}"><i class="fa-solid fa-file-lines"></i> Agile Framework Word
                                    </a>
                                </li>
                                <li>
                                    <a href="{{asset('storage/Company Documents/' . $companyData->agile_framework_pdf)}}"><i class="fa-solid fa-file-lines" target="_blank"></i> Agile Framework PDF
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modal')
    <div class="modal fade addEmployModal" id="companyDetailModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLabel">Firmendetails bearbeiten</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addcompanydetail">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="name">Name*</label>
                                    <input type="text" id="name" name="name">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="art" class="control-label">Art*</label>
                                    <input type="text" id="art" name="art" value="">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group boxForm">
                                    <label for="address">Address*</label>
                                    <textarea name="address" id="address" rows="5"></textarea>
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="managing_director" class="control-label">Managing Director*</label>
                                    <input type="text" id="managing_director" name="managing_director">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="bank_name" class="control-label">Bank Name*</label>
                                    <input type="text" id="bank_name" name="bank_name">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="iban_number" class="control-label">IBAN*</label>
                                    <input type="text" id="iban_number" name="iban_number">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="bic_number" class="control-label">BIC*</label>
                                    <input type="text" id="bic_number" name="bic_number">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="ust_id" class="control-label">UST ID*</label>
                                    <input type="text" id="ust_id" name="ust_id">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="trade_register" class="control-label">Trade Register*</label>
                                    <input type="text" id="trade_register" name="trade_register">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="email" class="control-label">Email*</label>
                                    <input type="text" id="email" name="email">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="phone" class="control-label">Phone*</label>
                                    <input type="text" id="phone" name="phone">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group boxForm">
                                    <label for="website_url" class="control-label">Website Url*</label>
                                    <input type="text" id="website_url" name="website_url">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="company_presentation_word" class="control-label">Unternehmensvorstellung(Word)*</label>
                                    <input type="file" id="company_presentation_word" name="company_presentation_word">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="company_presentation_pdf" class="control-label">Unternehmensvorstellung(PDF)*</label>
                                    <input type="file" id="company_presentation_pdf" name="company_presentation_pdf">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="agile_framework_word" class="control-label">Agile Framework(Word)*</label>
                                    <input type="file" id="agile_framework_word" name="agile_framework_word">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group boxForm">
                                    <label for="agile_framework_pdf" class="control-label">Agile Framework(PDF)*</label>
                                    <input type="file" id="agile_framework_pdf" name="agile_framework_pdf">
                                    <span class="error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                        <button type="submit" class="btn btn-info waves-effect waves-light">Speichern</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    var getCompanyDetailsUrl = "{{ route('company-details.detail') }}";
    var createCompanyDetailsUrl = "{{ route('company-details.update') }}";
</script>
<script src="{{ $baseUrl }}custom/js/company.js"></script>
@endsection

