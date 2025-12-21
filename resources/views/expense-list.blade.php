<?php $page = 'expense-list'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        @component('components.breadcrumb')
            @slot('title')
                Expense List
            @endslot
            @slot('li_1')
                Manage Your Expenses
            @endslot
            @slot('li_2')
                <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-expense">
                    <i data-feather="plus-circle" class="me-1"></i> Add Expense
                </a>
            @endslot
        @endcomponent

        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fa fa-info-circle me-1"></i>
            Expenses are specific to the currently selected store.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <input type="text" id="expense-search" placeholder="Search..." class="form-control form-control-sm">
                            <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                        </div>
                    </div>
                    <div class="search-path">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                    </div>
                    <div class="form-sort">
                        <i data-feather="sliders" class="info-img"></i>
                        <select class="select" id="filter-category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Filter -->
                <div class="card" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <i data-feather="calendar" class="info-img"></i>
                                    <input type="text" id="filter-date-from" class="form-control datetimepicker" placeholder="From Date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <i data-feather="calendar" class="info-img"></i>
                                    <input type="text" id="filter-date-to" class="form-control datetimepicker" placeholder="To Date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <a href="javascript:void(0);" class="btn btn-filters" onclick="applyFilters()">
                                        <i data-feather="search" class="feather-search"></i> Search
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <a href="javascript:void(0);" class="btn btn-secondary" onclick="clearFilters()">
                                        <i data-feather="x" class="feather-x"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Filter -->
                <div class="table-responsive">
                    <table class="table" id="expense-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Payment Method</th>
                                <th>Created By</th>
                                <th>Status</th>
                                <th class="no-sort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="add-expense" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-expense-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expense Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="add-category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                                <input type="text" name="expense_date" id="add-date" class="form-control datetimepicker" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount (MYR) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="add-amount" class="form-control" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="add-payment-method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="add-description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveExpense()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Expense Modal -->
<div class="modal fade" id="edit-expense" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Expense</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-expense-form">
                    <input type="hidden" id="edit-id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expense Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="edit-category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expense Date <span class="text-danger">*</span></label>
                                <input type="text" name="expense_date" id="edit-date" class="form-control datetimepicker" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Amount (MYR) <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="edit-amount" class="form-control" step="0.01" min="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select name="payment_method" id="edit-payment-method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="ewallet">E-Wallet</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_active" id="edit-is-active" class="form-check-input">
                                <label class="form-check-label" for="edit-is-active">Active</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateExpense()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Expense Modal -->
<div class="modal fade" id="view-expense" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Expense Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <strong>Reference:</strong>
                        <p id="view-reference" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Category:</strong>
                        <p id="view-category" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Amount:</strong>
                        <p id="view-amount" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Date:</strong>
                        <p id="view-date" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Payment Method:</strong>
                        <p id="view-payment" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Status:</strong>
                        <p id="view-status" class="mb-0"></p>
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Description:</strong>
                        <p id="view-description" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Created By:</strong>
                        <p id="view-created-by" class="mb-0"></p>
                    </div>
                    <div class="col-6 mb-3">
                        <strong>Created At:</strong>
                        <p id="view-created-at" class="mb-0"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    #expense-table_wrapper .dataTables_length, #expense-table_wrapper .dataTables_filter { display: none !important; }
    .badge-linesuccess {
        background-color: transparent;
        border: 1px solid #28a745;
        color: #28a745;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .badge-linedanger {
        background-color: transparent;
        border: 1px solid #dc3545;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
<script>
    $(document).ready(function() {
        var expenseTable = $('#expense-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('expense-list') }}",
                data: function (d) {
                    d.search = $('#expense-search').val();
                    d.category = $('#filter-category').val();
                    d.date_from = $('#filter-date-from').val();
                    d.date_to = $('#filter-date-to').val();
                }
            },
            columns: [
                { data: 'row_number', name: 'row_number', orderable: false, searchable: false },
                { data: 'category', name: 'category' },
                { data: 'reference', name: 'reference' },
                { data: 'amount', name: 'amount' },
                { data: 'expense_date', name: 'expense_date' },
                { data: 'payment_method', name: 'payment_method' },
                { data: 'created_by', name: 'created_by' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'action-table-data' }
            ],
            order: [[4, 'desc']],
            language: {
                info: "Showing _START_ - _END_ of _TOTAL_ Results",
                paginate: {
                    previous: '<i class="fa fa-angle-left"></i>',
                    next: '<i class="fa fa-angle-right"></i>'
                }
            },
            drawCallback: function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }
        });

        $('#expense-search').on('keyup', function() {
            expenseTable.search(this.value).draw();
        });

        $('#filter-category').on('change', function() {
            expenseTable.ajax.reload();
        });

        window.expenseTable = expenseTable;
    });

    function applyFilters() {
        window.expenseTable.ajax.reload();
    }

    function clearFilters() {
        $('#filter-category').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        window.expenseTable.ajax.reload();
    }

    function saveExpense() {
        var data = {
            _token: '{{ csrf_token() }}',
            category_id: $('#add-category').val(),
            expense_date: $('#add-date').val(),
            amount: $('#add-amount').val(),
            payment_method: $('#add-payment-method').val(),
            description: $('#add-description').val()
        };

        if (!data.category_id) {
            Swal.fire('Error', 'Please select a category', 'error');
            return;
        }

        $.ajax({
            url: "{{ route('expenses.store') }}",
            type: "POST",
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#add-expense').modal('hide');
                    $('#add-expense-form')[0].reset();
                    window.expenseTable.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    function viewExpense(id) {
        $.ajax({
            url: "{{ url('expenses') }}/" + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    var expense = response.expense;
                    $('#view-reference').text(expense.reference || '-');
                    $('#view-category').text(expense.category ? expense.category.name : '-');
                    $('#view-amount').text('MYR ' + parseFloat(expense.amount).toFixed(2));
                    $('#view-date').text(new Date(expense.expense_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }));
                    $('#view-payment').text(expense.payment_method ? expense.payment_method.charAt(0).toUpperCase() + expense.payment_method.slice(1) : '-');
                    $('#view-status').html(expense.is_active ? '<span class="badge badge-linesuccess">Active</span>' : '<span class="badge badge-linedanger">Inactive</span>');
                    $('#view-description').text(expense.description || '-');
                    $('#view-created-by').text(expense.user ? expense.user.name : '-');
                    $('#view-created-at').text(new Date(expense.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }));
                    $('#view-expense').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to fetch expense details.', 'error');
            }
        });
    }

    function editExpense(id) {
        $.ajax({
            url: "{{ url('expenses') }}/" + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    var expense = response.expense;
                    $('#edit-id').val(expense.id);
                    $('#edit-category').val(expense.category_id);
                    $('#edit-date').val(expense.expense_date);
                    $('#edit-amount').val(expense.amount);
                    $('#edit-payment-method').val(expense.payment_method);
                    $('#edit-description').val(expense.description);
                    $('#edit-is-active').prop('checked', expense.is_active);
                    $('#edit-expense').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to fetch expense details.', 'error');
            }
        });
    }

    function updateExpense() {
        var id = $('#edit-id').val();
        var data = {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            category_id: $('#edit-category').val(),
            expense_date: $('#edit-date').val(),
            amount: $('#edit-amount').val(),
            payment_method: $('#edit-payment-method').val(),
            description: $('#edit-description').val(),
            is_active: $('#edit-is-active').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: "{{ url('expenses') }}/" + id,
            type: "POST",
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#edit-expense').modal('hide');
                    window.expenseTable.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    function deleteExpense(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('expenses') }}/" + id,
                    type: "DELETE",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            window.expenseTable.ajax.reload();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete expense.', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
