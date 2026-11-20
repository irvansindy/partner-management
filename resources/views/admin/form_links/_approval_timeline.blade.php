<div class="approval-timeline">
    @foreach ($approval->steps->sortBy('step_ordering')->groupBy('step_ordering') as $stageNumber => $stageSteps)
        @php
            $stageState = $stageSteps->contains(fn ($step) => $step->isApproved()) ? 'approved'
                : ($stageSteps->contains(fn ($step) => $step->isRejected()) ? 'rejected'
                : ($stageSteps->contains(fn ($step) => $step->isWaiting()) ? 'waiting' : 'pending'));
        @endphp
        <div class="approval-step" data-stage-order="{{ $stageNumber }}">
            <span class="approval-status-dot is-{{ $stageState }}">
                <i class="fas fa-{{ $stageState === 'approved' ? 'check' : ($stageState === 'rejected' ? 'times' : ($stageState === 'waiting' ? 'clock' : 'circle')) }}"></i>
            </span>
            <div class="approval-stage-cards {{ $stageSteps->count() > 1 ? 'is-parallel' : '' }}">
            @foreach ($stageSteps as $step)
            <div class="approval-step-card">
                <div class="approval-step-header">
                    <div>
                        <h3 class="approval-step-title">{{ __('form_links_remaining.Step') }} {{ $step->step_ordering }} · {{ $step->approver->name ?? __('form_links_remaining.Unknown') }}</h3>
                        <span class="approval-step-meta">
                    @if ($step->isApproved() && $step->approved_at)
                        <i class="fas fa-clock"></i> {{ $step->approved_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
                    @elseif($step->isRejected() && $step->rejected_at)
                        <i class="fas fa-clock"></i> {{ $step->rejected_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
                    @else
                        <i class="fas fa-clock"></i> {{ $step->created_at->locale(app()->getLocale())->translatedFormat('d M Y H:i') }}
                    @endif
                        </span>
                    </div>
                    @if ($step->isApproved())
                        <span class="approval-state">{{ __('admin.Approved') }}</span>
                    @elseif($step->isRejected())
                        <span class="approval-state">{{ __('admin.Rejected') }}</span>
                    @elseif($step->isWaiting())
                        <span class="approval-state">{{ __('admin.Waiting for Approval') }}</span>
                    @else
                        <span class="approval-state">{{ __('admin.Pending') }}</span>
                    @endif
                </div>
                <div class="approval-detail-list">
                    <p class="mb-1">
                        <i class="fas fa-user"></i> <strong>{{ __('submission.Approver:') }}</strong>
                        {{ $step->approver->name ?? __('form_links_remaining.Unknown') }}
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-envelope"></i> <strong>Email:</strong>
                        {{ $step->approver->email ?? '-' }}
                    </p>
                </div>

                @if ($step->notes)
                    <div class="approval-note">
                        <strong><i class="fas fa-comment mr-1"></i> {{ __('submission.Notes') }}</strong>
                        <div class="mt-1" style="white-space: pre-line; overflow-wrap: anywhere">{{ $step->notes }}</div>
                    </div>
                @endif

                @if ($step->isWaiting() && auth()->id() === $step->user_id)
                    <div class="mt-3">
                        <a href="{{ route('admin.approvals.step.action', $step) }}"
                            class="btn btn-primary btn-sm form-link-action">
                            <i class="fas fa-tasks"></i> {{ __('admin.Take Action') }}
                        </a>
                    </div>
                @endif
            </div>
            @endforeach
            </div>
        </div>
    @endforeach
</div>

