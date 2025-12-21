<?php $page = 'sales-report'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Sales Report</h4>
                    <h6>View sales performance by product</h6>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf" id="export-pdf"><img
                                src="<?php echo e(URL::asset('/build/img/icons/pdf.svg')); ?>" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel" id="export-excel"><img
                                src="<?php echo e(URL::asset('/build/img/icons/excel.svg')); ?>" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print" id="print-report"><i data-feather="printer"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="<?php echo e(route('sales-report')); ?>"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Sales</h6>
                                    <h4 class="mb-0">MYR <?php echo e(number_format($totalSales ?? 0, 2)); ?></h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i data-feather="dollar-sign" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Quantity Sold</h6>
                                    <h4 class="mb-0"><?php echo e(number_format($totalQuantity ?? 0)); ?> units</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="package" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Top Product</h6>
                                    <h4 class="mb-0"><?php echo e($topProduct->product_name ?? 'N/A'); ?></h4>
                                </div>
                                <div class="bg-warning rounded-circle p-3">
                                    <i data-feather="trending-up" class="text-white"></i>
                                </div>
                            </div>
                        </div>
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
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <form method="GET" action="<?php echo e(route('sales-report')); ?>" class="row">
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="start_date" value="<?php echo e($startDate ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="end_date" value="<?php echo e($endDate ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>Product</label>
                                        <select class="select" name="product_id">
                                            <option value="">All Products</option>
                                            <?php $__currentLoopData = $products ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($product->id); ?>"><?php echo e($product->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-filters w-100"> 
                                            <i data-feather="search" class="feather-search"></i> Search 
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Sold Qty</th>
                                    <th>Sold Amount</th>
                                    <th>In Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $salesData ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td class="productimgname">
                                        <div class="view-product me-2">
                                            <?php if($item->image): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="product" style="width:40px;height:40px;object-fit:cover;">
                                            <?php else: ?>
                                                <img src="<?php echo e(URL::asset('/build/img/products/default.png')); ?>" alt="product">
                                            <?php endif; ?>
                                        </div>
                                        <a href="javascript:void(0);"><?php echo e($item->product_name); ?></a>
                                    </td>
                                    <td><?php echo e($item->sku ?? 'N/A'); ?></td>
                                    <td><?php echo e($item->category_name ?? 'N/A'); ?></td>
                                    <td><?php echo e(number_format($item->sold_qty)); ?></td>
                                    <td>MYR <?php echo e(number_format($item->sold_amount, 2)); ?></td>
                                    <td><?php echo e(number_format($item->in_stock ?? 0)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No sales data found for the selected period</td>
                                </tr>
                                <?php endif; ?>
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
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/sales-report.blade.php ENDPATH**/ ?>