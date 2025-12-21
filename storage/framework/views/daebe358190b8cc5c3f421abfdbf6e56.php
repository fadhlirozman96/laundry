<?php $page = 'product-list'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Product List
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage your products
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    <?php echo e(url('add-product')); ?>

                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_3'); ?>
                    Add New Product
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_4'); ?>
                    Import Product
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <!-- /product list -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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
                                <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
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
                                                <i class="fas fa-money-bill info-img"></i>
                                                <select class="select">
                                                    <option>Price</option>
                                                    <option>MYR 12500.00</option>
                                                    <option>MYR 12500.00</option>
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
                                    <th class="no-sort">#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
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
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    /* Hide DataTables default elements - let DataTables handle pagination */
    #product-table_wrapper .dataTables_length,
    #product-table_wrapper .dataTables_filter {
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
            url: "<?php echo e(route('product-list')); ?>",
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
                name: 'row_number', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
            { data: 'product', name: 'name' },
            { data: 'sku', name: 'sku' },
            { data: 'category', name: 'category.name' },
            { data: 'price', name: 'price' },
            { data: 'unit', name: 'unit.short_name' },
            { data: 'quantity', name: 'quantity' },
            { data: 'created_by', name: 'creator.name' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'action-table-data'
            }
        ],
        order: [], // Use server-side ordering (newest products first)
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        lengthChange: false,
        pagingType: "simple_numbers",
        searching: true,
        language: {
            paginate: {
                previous: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>'
            },
            info: "Showing _START_ of _TOTAL_ Results"
        },
        dom: 'rtip',
        drawCallback: function(settings) {
            // Reinitialize feather icons after table redraw
            setTimeout(function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 100);
        },
        initComplete: function(settings, json) {
            // Move filter to search input area
            $('.dataTables_filter').appendTo('.search-input');
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
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/product-list.blade.php ENDPATH**/ ?>