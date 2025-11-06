<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">
            @lang('messages.File Attachment')

        </h3>
        {{-- <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div> --}}
    </div>
    <div class="card-body">
        <div class="row px-2 py-2" style="line-height: 1">
            <form enctype="multipart/form-data" method="post" action="#" class="krajee-example-form"
                id="form_certificate_about_us">
                @csrf
                <div class="file-loading">
                    <input id="input-multiple-file" name="input-multiple-file[]" multiple type="file"
                        accept="image/*" data-show-upload="true" data-show-caption="true">
                </div>
            </form>
        </div>
    </div>
</div>
