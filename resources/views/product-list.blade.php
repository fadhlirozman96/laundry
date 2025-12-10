<?php $page = 'product-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Product List
                @endslot
                @slot('li_1')
                    Manage your products
                @endslot
                @slot('li_2')
                    {{ url('add-product') }}
                @endslot
                @slot('li_3')
                    Add New Product
                @endslot
                @slot('li_4')
                    Import Product
                @endslot
            @endcomponent

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" placeholder="Search..." class="form-control" id="product-search">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>14 09 23</option>
                                <option>11 09 23</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card mb-0" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="box" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Product</option>
                                                    <option>
                                                        Lenovo 3rd Generation</option>
                                                    <option>Nike Jordan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="stop-circle" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Categroy</option>
                                                    <option>Laptop</option>
                                                    <option>Shoe</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="git-merge" class="info-img"></i>
                                                <select class="select">
                                                    <option>Choose Sub Category</option>
                                                    <option>Computers</option>
                                                    <option>Fruits</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i data-feather="stop-circle" class="info-img"></i>
                                                <select class="select">
                                                    <option>All Brand</option>
                                                    <option>Lenovo</option>
                                                    <option>Nike</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <i class="fas fa-money-bill info-img"></i>
                                                <select class="select">
                                                    <option>Price</option>
                                                    <option>$12500.00</option>
                                                    <option>$12500.00</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                        class="feather-search"></i> Search </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table datanew" id="product-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Created by</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div id="product-table_info" class="dataTables_info"></div>
                            <div id="product-table_paginate" class="dataTables_paginate"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection

@push('scripts')
<style>
    /* Action icons styling */
    .edit-delete-action a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    /* View icon - blue/cyan background */
    .edit-delete-action a:first-child {
        background-color: rgba(13, 202, 240, 0.1);
    }
    .edit-delete-action a:first-child:hover {
        background-color: rgba(13, 202, 240, 0.2);
    }
    .edit-delete-action a:first-child svg {
        color: #0dcaf0;
        stroke: #0dcaf0;
    }
    
    /* Edit icon - orange/warning background */
    .edit-delete-action a:nth-child(2) {
        background-color: rgba(255, 159, 67, 0.1);
    }
    .edit-delete-action a:nth-child(2):hover {
        background-color: rgba(255, 159, 67, 0.2);
    }
    .edit-delete-action a:nth-child(2) svg {
        color: #FF9F43;
        stroke: #FF9F43;
    }
    
    /* Delete icon - red/danger background */
    .edit-delete-action a:nth-child(3) {
        background-color: rgba(234, 84, 85, 0.1);
    }
    .edit-delete-action a:nth-child(3):hover {
        background-color: rgba(234, 84, 85, 0.2);
    }
    .edit-delete-action a:nth-child(3) svg {
        color: #ea5455;
        stroke: #ea5455;
    }

    /* Pagination styling */
    .pagination-wrapper {
        display: inline-flex;
        gap: 10px;
    }
    .pagination-btn {
        padding: 8px 20px;
        border-radius: 5px;
        font-weight: 500;
        border: none;
    }
    .pagination-btn.btn-primary {
        background: #FF9F43;
        color: #fff;
    }
    .pagination-btn.btn-primary:hover {
        background: #ff8510;
    }
    .pagination-btn.btn-secondary {
        background: #e9ecef;
        color: #6c757d;
        cursor: not-allowed;
    }
    #product-table_info {
        color: #5e6278;
        font-size: 14px;
    }
    /* Hide DataTables default elements */
    #product-table_wrapper .dataTables_length,
    #product-table_wrapper .dataTables_filter,
    #product-table_wrapper > .row {
        display: none !important;
    }
</style>
<script>
var table;
$(document).ready(function() {
    table = $('#product-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('product-list') }}",
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataSrc: function(json) {
                console.log('DataTables Response:', json);
                return json.data;
            },
            error: function (xhr, error, code) {
                console.log('AJAX Error:', error);
                console.log('Status:', xhr.status);
                console.log('Response:', xhr.responseText);
            }
        },
        columns: [
            { 
                data: 'checkbox', 
                name: 'checkbox', 
                orderable: false, 
                searchable: false 
            },
            { data: 'product', name: 'name' },
            { data: 'sku', name: 'sku' },
            { data: 'category', name: 'category.name' },
            { data: 'brand', name: 'brand.name' },
            { data: 'price', name: 'price' },
            { data: 'unit', name: 'unit.short_name' },
            { data: 'quantity', name: 'quantity' },
            { data: 'created_by', name: 'creator.name' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false 
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        lengthChange: false,
        pagingType: "simple",
        searching: true,
        language: {
            paginate: {
                previous: "← Previous",
                next: "Next →"
            },
            info: "Showing _START_ to _END_ of _TOTAL_ entries"
        },
        dom: 'rt',
        drawCallback: function(settings) {
            // Reinitialize feather icons after table redraw
            setTimeout(function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 100);
            
            // Manually place pagination and info in footer
            var api = this.api();
            var pageInfo = api.page.info();
            
            // Update info
            $('#product-table_info').html('Showing ' + (pageInfo.start + 1) + ' to ' + pageInfo.end + ' of ' + pageInfo.recordsTotal + ' entries');
            
            // Create pagination buttons
            var pagination = '<div class="pagination-wrapper">';
            if (pageInfo.page > 0) {
                pagination += '<a href="javascript:void(0)" class="btn btn-primary pagination-btn" onclick="table.page(\'previous\').draw(\'page\')">← Previous</a>';
            } else {
                pagination += '<button class="btn btn-secondary pagination-btn" disabled>← Previous</button>';
            }
            
            if (pageInfo.page < pageInfo.pages - 1) {
                pagination += '<a href="javascript:void(0)" class="btn btn-primary pagination-btn ms-2" onclick="table.page(\'next\').draw(\'page\')">Next →</a>';
            } else {
                pagination += '<button class="btn btn-secondary pagination-btn ms-2" disabled>Next →</button>';
            }
            pagination += '</div>';
            
            $('#product-table_paginate').html(pagination);
        }
    });
    
    // Connect the search input to DataTables
    $('#product-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function deleteProduct(id) {
    if(confirm('Are you sure you want to delete this product?')) {
        fetch(`/products/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                $('#product-table').DataTable().ajax.reload();
            } else {
                alert('Error deleting product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting product');
        });
    }
}
</script>
@endpush
