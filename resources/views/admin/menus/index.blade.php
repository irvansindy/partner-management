@extends('layouts.main')
@section('content')
    @push('styles')
        <!-- Grid.js -->
        <link href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css" rel="stylesheet" />

        <!-- Font Awesome (dibutuhkan oleh ikon tombol aksi: fa-list-alt, fa-eye, fa-trash) -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />

        <style>
            /* =========================================================
               Custom theme untuk Grid.js — disesuaikan dengan warna
               navbar ungu pada layout admin.
               Semua selector di-scope ke #menu_table & #list_submenu_table
               supaya tidak bentrok dengan Grid.js lain di halaman ini.
               ========================================================= */
            :root {
                --gj-primary: #6C5DD3;
                --gj-primary-soft: #EEF0FF;
                --gj-border: #E9E9F1;
                --gj-text-muted: #8A8CA5;
                --gj-row-hover: #F7F7FC;
            }

            #menu_table .gridjs-wrapper,
            #list_submenu_table .gridjs-wrapper {
                border: 1px solid var(--gj-border);
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(20, 20, 43, 0.04);
                overflow: hidden;
            }

            /* .gridjs-head membungkus search bar; defaultnya punya margin-bottom besar
               yang bikin jarak ke tabel terasa jauh */
            #menu_table .gridjs-head,
            #list_submenu_table .gridjs-head {
                margin-bottom: 8px;
                padding: 12px 12px 0 12px;
            }

            /* Search box */
            #menu_table .gridjs-search,
            #list_submenu_table .gridjs-search {
                margin-bottom: 4px;
            }

            #menu_table .gridjs-search-input,
            #list_submenu_table .gridjs-search-input {
                border: 1px solid var(--gj-border);
                border-radius: 8px;
                padding: 8px 14px;
                font-size: 14px;
                min-width: 260px;
                transition: border-color .15s ease, box-shadow .15s ease;
            }

            #menu_table .gridjs-search-input:focus,
            #list_submenu_table .gridjs-search-input:focus {
                outline: none;
                border-color: var(--gj-primary);
                box-shadow: 0 0 0 3px rgba(108, 93, 211, 0.15);
            }

            /* Table header */
            #menu_table th.gridjs-th,
            #list_submenu_table th.gridjs-th {
                background: var(--gj-primary-soft);
                color: var(--gj-primary);
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .04em;
                border-bottom: 2px solid var(--gj-primary);
                padding: 14px 16px;
            }

            /* Sortable header: kasih cursor + hover supaya kelihatan interaktif */
            #menu_table th.gridjs-th-sort,
            #list_submenu_table th.gridjs-th-sort {
                cursor: pointer;
                transition: background-color .15s ease;
            }

            #menu_table th.gridjs-th-sort:hover,
            #list_submenu_table th.gridjs-th-sort:hover {
                background: #E3E0FA;
            }

            #menu_table th.gridjs-th-sort .gridjs-th-content,
            #list_submenu_table th.gridjs-th-sort .gridjs-th-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            /* Body cells & rows */
            #menu_table td.gridjs-td,
            #list_submenu_table td.gridjs-td {
                padding: 14px 16px;
                font-size: 14px;
                border-color: var(--gj-border);
                vertical-align: middle;
            }

            #menu_table tr.gridjs-tr,
            #list_submenu_table tr.gridjs-tr {
                transition: background-color .12s ease;
            }

            #menu_table tr.gridjs-tr:hover td,
            #list_submenu_table tr.gridjs-tr:hover td {
                background-color: var(--gj-row-hover);
            }

            /* Tombol aksi -> outline waves-effect (Material Design), rapi & sejajar */
            #menu_table .gridjs-td .btn,
            #list_submenu_table .gridjs-td .btn {
                width: 34px;
                height: 34px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: box-shadow .12s ease;
            }

            #menu_table .gridjs-td .btn:hover,
            #list_submenu_table .gridjs-td .btn:hover {
                box-shadow: 0 3px 8px rgba(20, 20, 43, 0.12);
            }

            /* Footer: search summary + pagination */
            #menu_table .gridjs-footer,
            #list_submenu_table .gridjs-footer {
                border-top: 1px solid var(--gj-border);
                padding: 14px 16px;
            }

            #menu_table .gridjs-summary,
            #list_submenu_table .gridjs-summary {
                color: var(--gj-text-muted);
                font-size: 13px;
            }

            #menu_table .gridjs-pagination .gridjs-pages button,
            #list_submenu_table .gridjs-pagination .gridjs-pages button {
                border-radius: 8px;
                border-color: var(--gj-border);
                color: var(--gj-text-muted);
                margin: 0 2px;
            }

            #menu_table .gridjs-pagination .gridjs-pages button:not([disabled]):hover,
            #list_submenu_table .gridjs-pagination .gridjs-pages button:not([disabled]):hover {
                background: var(--gj-primary-soft);
                border-color: var(--gj-primary);
                color: var(--gj-primary);
            }

            #menu_table .gridjs-pagination .gridjs-pages button.gridjs-currentPage,
            #list_submenu_table .gridjs-pagination .gridjs-pages button.gridjs-currentPage {
                background: var(--gj-primary);
                border-color: var(--gj-primary);
                color: #fff;
                font-weight: 600;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="page-title">Menu Management</h2>
                <div class="p-2 flex-fill bd-highlight d-flex justify-content-end" style="float: right !important;">
                    <button type="button" class="btn bg-gradient-primary" id="for_create_menu" data-toggle="modal" data-target="#formCreateMenu">+ Menu</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-container">
                            {{-- Grid.js membangun <table>-nya sendiri di dalam container div ini --}}
                            <div id="menu_table"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.menus.create')
        @include('admin.menus.submenu_create')
        @include('admin.menus.modal_list_submenu')
    </div>
    @push('scripts')
        <!-- Grid.js -->
        <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>

        <!-- sweetalert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @include('admin.menus.menu_js')
    @endpush
@endsection