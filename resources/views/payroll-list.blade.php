<?php $page = 'payroll-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Payroll
                @endslot
                @slot('li_1')
                    Manage Employee Payroll
                @endslot
                @slot('li_2')
                    Add New Payroll
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="payroll-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Employee ID</th>
                                    <th>Email</th>
                                    <th>Month/Year</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payroll Modal -->
    <div class="modal fade" id="add-payroll" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-payroll-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select class="form-control select" name="user_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Month <span class="text-danger">*</span></label>
                                    <select class="form-control" name="month" required>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Year <span class="text-danger">*</span></label>
                                    <select class="form-control" name="year" required>
                                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="basic_salary" step="0.01" required>
                        </div>

                        <h6 class="mt-3 mb-2">Allowances</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">HRA</label>
                                    <input type="number" class="form-control" name="hra_allowance" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Conveyance</label>
                                    <input type="number" class="form-control" name="conveyance" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Medical</label>
                                    <input type="number" class="form-control" name="medical_allowance" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Bonus</label>
                                    <input type="number" class="form-control" name="bonus" step="0.01" value="0">
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-3 mb-2">Deductions</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">PF</label>
                                    <input type="number" class="form-control" name="pf_deduction" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Prof. Tax</label>
                                    <input type="number" class="form-control" name="professional_tax" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">TDS</label>
                                    <input type="number" class="form-control" name="tds" step="0.01" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Loans</label>
                                    <input type="number" class="form-control" name="loans_deduction" step="0.01" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="draft">Draft</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Payroll</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payroll Modal -->
    <div class="modal fade" id="edit-payroll-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-payroll-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="payroll_id" id="edit-payroll-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="basic_salary" id="edit-basic-salary" step="0.01" required>
                        </div>

                        <h6 class="mt-3 mb-2">Allowances</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">HRA</label>
                                    <input type="number" class="form-control" name="hra_allowance" id="edit-hra" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Conveyance</label>
                                    <input type="number" class="form-control" name="conveyance" id="edit-conveyance" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Medical</label>
                                    <input type="number" class="form-control" name="medical_allowance" id="edit-medical" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Bonus</label>
                                    <input type="number" class="form-control" name="bonus" id="edit-bonus" step="0.01">
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-3 mb-2">Deductions</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">PF</label>
                                    <input type="number" class="form-control" name="pf_deduction" id="edit-pf" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Prof. Tax</label>
                                    <input type="number" class="form-control" name="professional_tax" id="edit-tax" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">TDS</label>
                                    <input type="number" class="form-control" name="tds" id="edit-tds" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Loans</label>
                                    <input type="number" class="form-control" name="loans_deduction" id="edit-loans" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status" id="edit-status">
                                        <option value="draft">Draft</option>
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select class="form-control" name="payment_method" id="edit-payment-method">
                                        <option value="">Select</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var table = $('#payroll-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("payrolls.data") }}',
        columns: [
            { data: 'employee_name', name: 'user.name' },
            { data: 'employee_id', name: 'user.employee_id' },
            { data: 'email', name: 'user.email' },
            { data: 'month_year', name: 'month' },
            { data: 'salary_formatted', name: 'net_salary' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[3, 'desc']],
        drawCallback: function() {
            feather.replace();
        }
    });

    $('#add-payroll-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("payrolls.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-payroll').modal('hide');
                    Swal.fire('Success', response.message, 'success');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    $(document).on('click', '.edit-payroll', function() {
        var id = $(this).data('id');
        $.get('/payrolls/' + id, function(response) {
            if (response.success) {
                var p = response.payroll;
                $('#edit-payroll-id').val(p.id);
                $('#edit-basic-salary').val(p.basic_salary);
                $('#edit-hra').val(p.hra_allowance);
                $('#edit-conveyance').val(p.conveyance);
                $('#edit-medical').val(p.medical_allowance);
                $('#edit-bonus').val(p.bonus);
                $('#edit-pf').val(p.pf_deduction);
                $('#edit-tax').val(p.professional_tax);
                $('#edit-tds').val(p.tds);
                $('#edit-loans').val(p.loans_deduction);
                $('#edit-status').val(p.status);
                $('#edit-payment-method').val(p.payment_method);
                $('#edit-payroll-modal').modal('show');
            }
        });
    });

    $('#edit-payroll-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-payroll-id').val();
        $.ajax({
            url: '/payrolls/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#edit-payroll-modal').modal('hide');
                    Swal.fire('Success', response.message, 'success');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    $(document).on('click', '.delete-payroll', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/payrolls/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection
