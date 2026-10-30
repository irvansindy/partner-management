@extends('layouts.main')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}">
        <style>
            .maintenance-page { color: #292b3a; padding: 26px 0 44px; }
            .maintenance-heading { align-items: flex-end; display: flex; gap: 20px; justify-content: space-between; margin-bottom: 24px; }
            .maintenance-heading h2 { font-size: 28px; font-weight: 700; margin: 0 0 6px; }
            .maintenance-heading p { color: #707486; margin: 0; }
            .maintenance-notice { background: #f4f2ff; border: 1px solid #dcd7ff; border-radius: 9px; color: #484064; margin-bottom: 20px; padding: 14px 16px; }
            .maintenance-result { background: #fff; border: 1px solid #e4e5ed; border-radius: 10px; margin-bottom: 20px; overflow: hidden; }
            .maintenance-result__header { align-items: center; display: flex; gap: 10px; justify-content: space-between; padding: 14px 18px; }
            .maintenance-result__header strong { overflow-wrap: anywhere; }
            .maintenance-result__status { border: 1px solid #d7d9e2; border-radius: 5px; color: #4f5565; font-size: 12px; font-weight: 700; padding: 5px 8px; }
            .maintenance-output { background: #242631; color: #f3f4f7; font-family: SFMono-Regular, Consolas, monospace; font-size: 13px; margin: 0; max-height: 360px; overflow: auto; padding: 18px; white-space: pre-wrap; }
            .maintenance-group { background: #fff; border: 1px solid #e4e5ed; border-radius: 10px; margin-bottom: 18px; overflow: hidden; }
            .maintenance-group__header { border-bottom: 1px solid #ececf2; padding: 17px 20px; }
            .maintenance-group__header h3 { font-size: 17px; font-weight: 700; margin: 0; }
            .maintenance-command-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .maintenance-command { align-items: center; border-bottom: 1px solid #ececf2; display: grid; gap: 18px; grid-template-columns: minmax(0, 1fr) auto; padding: 18px 20px; }
            .maintenance-command:nth-child(odd) { border-right: 1px solid #ececf2; }
            .maintenance-command__copy { min-width: 0; }
            .maintenance-command__copy strong { display: block; margin-bottom: 4px; }
            .maintenance-command__copy p { color: #74788a; font-size: 13px; margin: 0; }
            .maintenance-command .btn { border-radius: 7px; min-width: 92px; }
            .maintenance-command .btn:focus-visible { box-shadow: 0 0 0 3px rgba(108, 93, 211, .28); outline: 2px solid #5548bd; outline-offset: 2px; }
            @media (max-width: 991.98px) { .maintenance-command-list { grid-template-columns: 1fr; } .maintenance-command:nth-child(odd) { border-right: 0; } }
            @media (max-width: 575.98px) { .maintenance-heading { align-items: flex-start; flex-direction: column; } .maintenance-command { align-items: stretch; grid-template-columns: 1fr; } .maintenance-command .btn { min-height: 44px; width: 100%; } }
        </style>
    @endpush

    <div class="maintenance-page">
        <header class="maintenance-heading">
            <div>
                <h2>System Maintenance</h2>
                <p>Run approved Laravel maintenance operations without terminal access.</p>
            </div>
        </header>

        <div class="maintenance-notice">
            <i class="fas fa-shield-alt mr-1" aria-hidden="true"></i>
            Only predefined commands can run. Every execution is locked and written to the application log.
        </div>

        @if ($result)
            <section class="maintenance-result" aria-live="polite" aria-label="Last command result">
                <div class="maintenance-result__header">
                    <strong><i class="fas {{ $result['success'] ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-1" aria-hidden="true"></i>{{ $result['label'] }}</strong>
                    <span class="maintenance-result__status">{{ $result['success'] ? 'Completed' : (($result['busy'] ?? false) ? 'Already running' : 'Failed') }}</span>
                </div>
                <pre class="maintenance-output">{{ $result['output'] }}</pre>
            </section>
        @endif

        @foreach ($commandGroups as $group => $commands)
            <section class="maintenance-group">
                <header class="maintenance-group__header"><h3>{{ $group }}</h3></header>
                <div class="maintenance-command-list">
                    @foreach ($commands as $key => $command)
                        <article class="maintenance-command">
                            <div class="maintenance-command__copy">
                                <strong>{{ $command['label'] }}</strong>
                                <p>{{ $command['description'] }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.system-maintenance.execute') }}"
                                class="maintenance-command-form" data-confirm="{{ $command['confirmation'] ? '1' : '0' }}"
                                data-command-label="{{ $command['label'] }}">
                                @csrf
                                <input type="hidden" name="command" value="{{ $key }}">
                                <button type="submit" class="btn {{ $command['confirmation'] ? 'btn-primary' : 'btn-outline-primary' }}">
                                    <i class="fas fa-play mr-1" aria-hidden="true"></i> Run
                                </button>
                            </form>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    @push('scripts')
        <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            document.querySelectorAll('.maintenance-command-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.submitting === '1') {
                        event.preventDefault();
                        return;
                    }

                    if (form.dataset.confirm !== '1') {
                        form.dataset.submitting = '1';
                        form.querySelector('button').disabled = true;
                        return;
                    }

                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Run maintenance command?',
                        text: form.dataset.commandLabel + ' may temporarily affect application performance.',
                        showCancelButton: true,
                        confirmButtonText: 'Run command',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#6558d3'
                    }).then(function (result) {
                        if (!result.isConfirmed) return;
                        form.dataset.submitting = '1';
                        form.querySelector('button').disabled = true;
                        form.submit();
                    });
                });
            });
        </script>
    @endpush
@endsection
