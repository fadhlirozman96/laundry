<?php $page = 'purchase-list'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header transfer">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Purchase List</h4>
                        <h6>Manage your purchases</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                                src="<?php echo e(URL::asset('/build/img/icons/pdf.svg')); ?>" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img
                                src="<?php echo e(URL::asset('/build/img/icons/excel.svg')); ?>" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                src="<?php echo e(URL::asset('/build/img/icons/printer.svg')); ?>" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="d-flex purchase-pg-btn">
                    <div class="page-btn">
                        <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                                data-feather="plus-circle" class="me-2"></i>Add New Purchase</a>
                    </div>
                    <div class="page-btn import">
                        <a href="#" class="btn btn-added color" data-bs-toggle="modal" data-bs-target="#view-notes"><i
                                data-feather="download" class="me-2"></i>Import Purchase</a>
                    </div>
                </div>

            </div>

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Supplier Name</option>
                                            <option>Apex Computers</option>
                                            <option>Beats Headphones</option>
                                            <option>Dazzle Shoes</option>
                                            <option>Best Accessories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select">
                                            <option>Choose Status</option>
                                            <option>Received</option>
                                            <option>Ordered</option>
                                            <option>Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file" class="info-img"></i>
                                        <select class="select">
                                            <option>Enter Reference</option>
                                            <option>PT001</option>
                                            <option>PT002</option>
                                            <option>PT003</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i class="fas fa-money-bill info-img"></i>
                                        <select class="select">
                                            <option>Choose Payment Status</option>
                                            <option>Paid</option>
                                            <option>Partial</option>
                                            <option>Unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive product-list">
                        <table class="table datanew" id="orders-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Store</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    /* Hide duplicate DataTables elements */
    #orders-table_wrapper .dataTables_length,
    #orders-table_wrapper .dataTables_filter {
        display: none !important;
    }
    
    /* Action icons styling - matching store-list */
    .edit-delete-action {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .edit-delete-action a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    /* View icon - cyan background */
    .edit-delete-action a.action-view {
        background-color: rgba(13, 202, 240, 0.1);
    }
    .edit-delete-action a.action-view:hover {
        background-color: rgba(13, 202, 240, 0.2);
    }
    .edit-delete-action a.action-view svg {
        color: #0dcaf0;
        stroke: #0dcaf0;
    }
    
    /* Print icon - blue background */
    .edit-delete-action a.action-print {
        background-color: rgba(0, 103, 226, 0.1);
    }
    .edit-delete-action a.action-print:hover {
        background-color: rgba(0, 103, 226, 0.2);
    }
    .edit-delete-action a.action-print svg {
        color: #0067e2;
        stroke: #0067e2;
    }
</style>
<script>
$(document).ready(function() {
    // Check if DataTable is already initialized
    if ($.fn.DataTable.isDataTable('#orders-table')) {
        // Destroy existing instance
        $('#orders-table').DataTable().destroy();
    }
    
    // Initialize DataTable with AJAX
    const table = $('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route("purchase-list")); ?>',
            type: 'GET'
        },
        columns: [
            { data: 'checkbox', name: 'row_number', orderable: false, searchable: false, className: 'text-center' },
            { data: 'order_number', name: 'order_number' },
            { data: 'customer', name: 'customer_name' },
            { data: 'store', name: 'store_id' },
            { data: 'items', name: 'items', orderable: false },
            { data: 'total', name: 'total' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'status', name: 'payment_status' },
            { data: 'date', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[8, 'desc']], // Order by date descending
        pageLength: 10,
        pagingType: "simple_numbers",
        language: {
            paginate: {
                previous: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>'
            },
            info: "Showing _START_ of _TOTAL_ Results"
        },
        dom: 'rtip',
        drawCallback: function() {
            // Reinitialize feather icons after table draw
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        },
        initComplete: function() {
            // Move filter to search input area
            $('.dataTables_filter').appendTo('.search-input');
        }
    });
});

// View order function
window.viewOrder = function(orderId) {
    $.ajax({
        url: '/pos/order/' + orderId,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                showOrderModal(response.order);
            }
        },
        error: function() {
            alert('Error loading order details');
        }
    });
};

// Show order details modal
function showOrderModal(order) {
    let itemsHtml = '';
    order.items.forEach(function(item) {
        itemsHtml += `
            <tr>
                <td>${item.product_name} (${item.product_sku})</td>
                <td>${item.quantity}</td>
                <td>MYR ${parseFloat(item.price).toFixed(2)}</td>
                <td>MYR ${parseFloat(item.subtotal).toFixed(2)}</td>
            </tr>
        `;
    });
    
    const modalHtml = `
        <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Details - ${order.order_number}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Customer:</strong> ${order.customer_name || 'Walk-in Customer'}</p>
                                <p><strong>Store:</strong> ${order.store ? order.store.name : 'N/A'}</p>
                                <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                                <p><strong>Payment Status:</strong> <span class="badge ${order.payment_status === 'paid' ? 'bg-success' : 'bg-warning'}">${order.payment_status}</span></p>
                                <p><strong>Order Status:</strong> <span class="badge bg-info">${order.order_status}</span></p>
                            </div>
                        </div>
                        <h6>Order Items:</h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                        </table>
                        <div class="text-end">
                            <p><strong>Subtotal:</strong> MYR ${parseFloat(order.subtotal).toFixed(2)}</p>
                            <p><strong>Tax:</strong> MYR ${parseFloat(order.tax).toFixed(2)}</p>
                            <p><strong>Shipping:</strong> MYR ${parseFloat(order.shipping).toFixed(2)}</p>
                            <p><strong>Discount:</strong> MYR ${parseFloat(order.discount).toFixed(2)}</p>
                            <h5><strong>Grand Total:</strong> MYR ${parseFloat(order.total).toFixed(2)}</h5>
                        </div>
                        ${order.notes ? `<div class="mt-3"><strong>Notes:</strong><p>${order.notes}</p></div>` : ''}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printOrder(${order.id})"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#viewOrderModal').remove();
    
    // Append and show new modal
    $('body').append(modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
    modal.show();
    
    // Clean up on hide
    $('#viewOrderModal').on('hidden.bs.modal', function() {
        $(this).remove();
    });
}

// Print order function (placeholder)
window.printOrder = function(orderId) {
    alert('Print functionality coming soon for order ID: ' + orderId);
};
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/purchase-list.blade.php ENDPATH**/ ?>