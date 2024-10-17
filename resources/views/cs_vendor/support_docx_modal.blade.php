<!-- Modal -->
<div class="modal fade" id="modal_support_document" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="modal_support_documentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_support_documentLabel">List Supporting Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- support document --}}
                <div class="row px-2 py-2" style="line-height: 1">
                    <div class="input-group mt-2">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#document_type_pt" role="tab"
                                    data-toggle="tab">PT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#document_type_cv" role="tab"
                                    data-toggle="tab">CV</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#document_type_ud_or_pd" role="tab"
                                    data-toggle="tab">UD/PD</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#document_type_perorangan" role="tab"
                                    data-toggle="tab">Perorangan</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-4">
                            <div role="tabpanel" class="tab-pane fade" id="document_type_pt">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doc Type</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_doc_type_pt">
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="document_type_cv">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doc Type</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_doc_type_cv">
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="document_type_ud_or_pd">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doc Type</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_doc_type_ud_or_pd">
                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="document_type_perorangan">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Doc Type</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data_doc_type_perorangan">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            SUPPORTING DOCUMENTS (Dokumen yang harus dilengkapi)
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div> --}}
                {{-- end support document --}}
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="btn_submit_data_company">
                        submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
