@props(['title', 'container', 'addLabel'])

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">{{ $title }}</h3>
            <p class="section-help">Update, add, or remove records in this section.</p>
        </div>
        <button type="button" class="btn btn-outline-primary btn-sm" data-add-row="{{ $container }}">
            <i class="fas fa-plus mr-1" aria-hidden="true"></i> {{ $addLabel }}
        </button>
    </div>
    <div class="card-body" id="{{ $container }}" data-next-index="{{ $attributes->get('next-index', 1000) }}">
        {{ $slot }}
    </div>
</div>
