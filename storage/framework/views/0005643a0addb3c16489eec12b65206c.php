<?php $page = 'pos'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper pos-pg-wrapper ms-0">
        <div class="content pos-design p-0">
            <div class="btn-row d-sm-flex align-items-center">
                <a href="javascript:void(0);" class="btn btn-secondary mb-xs-3" data-bs-toggle="modal"
                    data-bs-target="#orders"><span class="me-1 d-flex align-items-center"><i data-feather="shopping-cart"
                            class="feather-16"></i></span>View Orders</a>
                <a href="javascript:void(0);" class="btn btn-info"><span class="me-1 d-flex align-items-center"><i
                            data-feather="rotate-cw" class="feather-16"></i></span>Reset</a>
                <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#recents"><span
                        class="me-1 d-flex align-items-center"><i data-feather="refresh-ccw"
                            class="feather-16"></i></span>Transaction</a>
            </div>

            <div class="row align-items-start pos-wrapper">
                <div class="col-md-12 col-lg-8">
                    <div class="pos-categories tabs_wrapper">
                        <h5>Categories</h5>
                        <p>Select From Below Categories</p>
                        <ul class="tabs owl-carousel pos-category">
                            <li id="all" class="category-tab active" data-category-id="">
                                <a href="javascript:void(0);">
                                    <img src="<?php echo e(URL::asset('/build/img/categories/category-01.png')); ?>" alt="Categories">
                                </a>
                                <h6><a href="javascript:void(0);">All Categories</a></h6>
                                <span id="all-count"><?php echo e($products->count()); ?> Items</span>
                            </li>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li id="category-<?php echo e($category->id); ?>" class="category-tab" data-category-id="<?php echo e($category->id); ?>">
                                <a href="javascript:void(0);">
                                    <img src="<?php echo e(URL::asset('/build/img/categories/category-' . str_pad((($index % 6) + 1), 2, '0', STR_PAD_LEFT) . '.png')); ?>" alt="<?php echo e($category->name); ?>">
                                </a>
                                <h6><a href="javascript:void(0);"><?php echo e($category->name); ?></a></h6>
                                <span id="category-<?php echo e($category->id); ?>-count"><?php echo e($category->products_count ?? 0); ?> Items</span>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <div class="pos-products">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Products</h5>
                                <div class="search-product" style="max-width: 300px; width: 100%;">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="product-search" placeholder="Search products..." autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="button" id="clear-search" style="display: none;">
                                            <i data-feather="x" class="feather-16"></i>
                                        </button>
                                        <span class="input-group-text">
                                            <i data-feather="search" class="feather-16"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="tabs_container">
                                <div class="tab_content active" data-tab="all">
                                    <div class="row" id="products-container">
                                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 pe-2 product-item" data-product-id="<?php echo e($product->id); ?>" data-category-id="<?php echo e($product->category_id); ?>">
                                            <div class="product-info default-cover card">
                                                <a href="javascript:void(0);" class="img-bg add-to-cart" data-product-id="<?php echo e($product->id); ?>">
                                                    <img src="<?php echo e($product->image ? URL::asset($product->image) : URL::asset('/build/img/products/pos-product-01.png')); ?>" alt="<?php echo e($product->name); ?>">
                                                    <span class="check-icon" style="display: none;"><i data-feather="check" class="feather-16"></i></span>
                                                </a>
                                                <h6 class="cat-name"><a href="javascript:void(0);"><?php echo e($product->category->name ?? 'N/A'); ?></a></h6>
                                                <h6 class="product-name"><a href="javascript:void(0);"><?php echo e($product->name); ?></a></h6>
                                                <div class="d-flex align-items-center justify-content-between price">
                                                    <span><?php echo e($product->quantity); ?> <?php echo e($product->unit->name ?? 'Pcs'); ?></span>
                                                    <p>MYR <?php echo e(number_format($product->price, 2)); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php if($products->isEmpty()): ?>
                                    <div class="text-center py-5 col-12">
                                        <p class="text-muted">No products available for this store.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-4 ps-0">
                    <aside class="product-order-list">
                        <div class="head d-flex align-items-center justify-content-between w-100">
                            <div class="">
                                <h5>Order List</h5>
                                <span>Transaction ID : <span id="transaction-id">#<?php echo e(date('Ymd')); ?>-<?php echo e(str_pad(1, 4, '0', STR_PAD_LEFT)); ?></span></span>
                            </div>
                            <div class="">
                                <a class="confirm-text" href="javascript:void(0);"><i data-feather="trash-2"
                                        class="feather-16 text-danger"></i></a>
                                <a href="javascript:void(0);" class="text-default"><i data-feather="more-vertical"
                                        class="feather-16"></i></a>
                            </div>
                        </div>
                        <div class="customer-info block-section">
                            <h6>Customer Information</h6>
                            <div class="input-block d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <select class="select" id="customer-select">
                                        <option value="">Walk in Customer</option>
                                    </select>
                                </div>
                                <a href="javascript:void(0);" class="btn btn-primary btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#create" title="Add New Customer"><i data-feather="user-plus" class="feather-16"></i></a>
                            </div>
                        </div>

                        <div class="product-added block-section">
                            <div class="head-text d-flex align-items-center justify-content-between">
                                <h6 class="d-flex align-items-center mb-0">Product Added<span class="count" id="cart-count">0</span></h6>
                                <a href="javascript:void(0);" class="d-flex align-items-center text-danger" id="clear-cart"><span
                                        class="me-1"><i data-feather="x" class="feather-16"></i></span>Clear all</a>
                            </div>
                            <div class="product-wrap" id="cart-items">
                                <div class="text-center py-3">
                                    <p class="text-muted mb-0">No items in cart</p>
                                </div>
                            </div>
                        </div>
                        <div class="block-section">
                            <div class="selling-info">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="input-block">
                                            <label>Order Tax (%)</label>
                                            <input type="number" class="form-control" id="order-tax" value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="input-block">
                                            <label>Shipping</label>
                                            <input type="number" class="form-control" id="order-shipping" value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="input-block">
                                            <label>Discount</label>
                                            <input type="number" class="form-control" id="order-discount" value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-total">
                                <table class="table table-responsive table-borderless">
                    <tr>
                        <td>Sub Total</td>
                        <td class="text-end" id="subtotal-amount">MYR 0.00</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td class="text-end" id="tax-amount">MYR 0.00</td>
                    </tr>
                    <tr>
                        <td>Shipping</td>
                        <td class="text-end" id="shipping-amount">MYR 0.00</td>
                    </tr>
                    <tr>
                        <td class="danger">Discount</td>
                        <td class="danger text-end" id="discount-amount">MYR 0.00</td>
                    </tr>
                    <tr>
                        <td><strong>Total</strong></td>
                        <td class="text-end"><strong id="grand-total">MYR 0.00</strong></td>
                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="block-section payment-method">
                            <h6>Payment Method</h6>
                            <div class="row d-flex align-items-center justify-content-center methods">
                                <div class="col-md-6 col-lg-4 item">
                                    <div class="default-cover">
                                        <a href="javascript:void(0);" class="payment-method-btn" data-method="cash">
                                            <img src="<?php echo e(URL::asset('/build/img/icons/cash-pay.svg')); ?>"
                                                alt="Payment Method">
                                            <span>Cash</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 item">
                                    <div class="default-cover">
                                        <a href="javascript:void(0);" class="payment-method-btn" data-method="card">
                                            <img src="<?php echo e(URL::asset('/build/img/icons/credit-card.svg')); ?>"
                                                alt="Payment Method">
                                            <span>Debit Card</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 item">
                                    <div class="default-cover">
                                        <a href="javascript:void(0);" class="payment-method-btn" data-method="qr">
                                            <img src="<?php echo e(URL::asset('/build/img/icons/qr-scan.svg')); ?>" alt="Payment Method">
                                            <span>Scan</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid btn-block">
                            <a class="btn btn-secondary" href="javascript:void(0);" id="grand-total-btn">
                                Grand Total : <span id="grand-total-display">MYR 0.00</span>
                            </a>
                        </div>
                        <div class="btn-row d-sm-flex align-items-center justify-content-between">
                            <a href="javascript:void(0);" class="btn btn-info btn-icon flex-fill"
                                data-bs-toggle="modal" data-bs-target="#hold-order"><span
                                    class="me-1 d-flex align-items-center"><i data-feather="pause"
                                        class="feather-16"></i></span>Hold</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-icon flex-fill" id="void-order"><span
                                    class="me-1 d-flex align-items-center"><i data-feather="trash-2"
                                        class="feather-16"></i></span>Void</a>
                            <a href="javascript:void(0);" class="btn btn-success btn-icon flex-fill" id="complete-order"><span
                                    class="me-1 d-flex align-items-center"><i data-feather="credit-card"
                                        class="feather-16"></i></span>Payment</a>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Quantity/Weight Modal -->
    <div class="modal fade" id="productQuantityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product to Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6 id="modal-product-name"></h6>
                        <p class="text-muted mb-0">Price: <span id="modal-product-price"></span> per <span id="modal-product-unit"></span></p>
                    </div>
                    <div class="mb-3">
                        <label for="product-quantity" class="form-label">Quantity / Weight (<span id="quantity-unit-label"></span>)</label>
                        <input type="number" class="form-control" id="product-quantity" min="0.1" step="0.1" value="1" required>
                        <small class="text-muted" id="quantity-hint">Enter the quantity or weight based on the unit</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Price</label>
                        <h5 id="modal-total-price" class="text-primary">MYR 0.00</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="create" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="create-customer-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customer-name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer-name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customer-email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer-phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="customer-phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="customer-address" class="form-label">Address</label>
                            <textarea class="form-control" id="customer-address" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer-city" class="form-label">City</label>
                                <input type="text" class="form-control" id="customer-city">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer-country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="customer-country" value="Malaysia">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="save-customer-btn">Save Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Orders Modal -->
    <div class="modal fade" id="orders" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pending Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orders-list">
                        <div class="text-center py-4">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Modal -->
    <div class="modal fade" id="recents" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Recent Transactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="transactions-list">
                        <div class="text-center py-4">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Loading state for product cards */
    .add-to-cart.loading {
        opacity: 0.6;
        pointer-events: none;
        position: relative;
    }
    
    .add-to-cart.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #fff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Ensure modal backdrop is properly handled */
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal {
        z-index: 1050;
    }
    
    /* Payment method styling */
    .payment-method .default-cover {
        position: relative;
        display: block;
        margin-bottom: 15px;
    }
    
    .payment-method .payment-method-btn {
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 15px !important;
        border: 3px solid #e9ecef !important;
        border-radius: 12px !important;
        transition: all 0.3s ease !important;
        background: #ffffff !important;
        text-decoration: none !important;
        width: 100%;
        position: relative;
        cursor: pointer;
    }
    
    .payment-method .payment-method-btn:hover {
        border-color: #50b0ff !important;
        background: #f0f9ff !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 6px 15px rgba(0, 103, 226, 0.2) !important;
    }
    
    /* Active state */
    .payment-method .payment-method-btn.active,
    .payment-method a.payment-method-btn.active {
        border-color: #0067e2 !important;
        background: linear-gradient(135deg, #e6f2ff 0%, #cce5ff 100%) !important;
        box-shadow: 0 6px 20px rgba(0, 103, 226, 0.5) !important;
        transform: translateY(-3px) scale(1.02) !important;
    }
    
    /* Checkmark for active payment method */
    .payment-method .payment-method-btn.active::before,
    .payment-method a.payment-method-btn.active::before {
        content: '✓';
        position: absolute;
        top: 8px;
        right: 8px;
        background: linear-gradient(135deg, #0067e2 0%, #50b0ff 100%);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        line-height: 30px;
        text-align: center;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 103, 226, 0.4);
        animation: checkmarkPop 0.3s ease-out;
    }
    
    @keyframes checkmarkPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .payment-method .payment-method-btn img {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
        object-fit: contain;
        filter: grayscale(50%);
        transition: filter 0.3s ease;
    }
    
    .payment-method .payment-method-btn.active img {
        filter: grayscale(0%);
    }
    
    .payment-method .payment-method-btn span {
        font-weight: 600;
        color: #6c757d;
        font-size: 14px;
        margin-top: 5px;
        transition: color 0.3s ease;
    }
    
    .payment-method .payment-method-btn.active span {
        color: #0067e2 !important;
        font-weight: 700;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedStoreId = <?php echo e(session('selected_store_id') ?? 'null'); ?>;
    
    // Clean up any stuck modals on page load
    $('.modal-backdrop').remove();
    $('body').removeClass('modal-open');
    $('.modal').modal('hide');
    
    // Cart storage
    let cart = [];
    let currentProduct = null;
    let selectedPaymentMethod = null;
    
    // Category click handler
    $('.category-tab').on('click', function() {
        $('.category-tab').removeClass('active');
        $(this).addClass('active');
        
        const categoryId = $(this).data('category-id');
        const searchTerm = $('#product-search').val().trim();
        loadProducts(categoryId, searchTerm);
    });
    
    // Load products function
    function loadProducts(categoryId = '', searchTerm = '') {
        $.ajax({
            url: '<?php echo e(route("pos.products")); ?>',
            method: 'GET',
            data: {
                category_id: categoryId || '',
                search: searchTerm || ''
            },
            success: function(response) {
                if (response.success) {
                    renderProducts(response.products);
                }
            },
            error: function(xhr) {
                console.error('Error loading products:', xhr);
            }
        });
    }
    
    // Search products
    let searchTimeout;
    $('#product-search').on('input', function() {
        const searchTerm = $(this).val().trim();
        const activeCategory = $('.category-tab.active').data('category-id') || '';
        
        // Show/hide clear button
        if (searchTerm.length > 0) {
            $('#clear-search').show();
        } else {
            $('#clear-search').hide();
        }
        
        // Debounce search to avoid too many requests
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadProducts(activeCategory, searchTerm);
        }, 300); // Wait 300ms after user stops typing
    });
    
    // Clear search button
    $('#clear-search').on('click', function() {
        $('#product-search').val('');
        $(this).hide();
        const activeCategory = $('.category-tab.active').data('category-id') || '';
        loadProducts(activeCategory, '');
    });
    
    // Clear search on category change
    $('.category-tab').on('click', function() {
        $('#product-search').val('');
        $('#clear-search').hide();
    });
    
    // Render products
    function renderProducts(products) {
        const container = $('#products-container');
        container.empty();
        
        if (products.length === 0) {
            const searchTerm = $('#product-search').val().trim();
            const message = searchTerm ? 
                `No products found matching "${searchTerm}"` : 
                'No products available for this store.';
            container.html(`<div class="text-center py-5 col-12"><p class="text-muted">${message}</p></div>`);
            return;
        }
        
        products.forEach(function(product) {
            const isInCart = cart.some(item => item.product_id === product.id);
            const checkIconStyle = isInCart ? '' : 'display: none;';
            const productHtml = `
                <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 pe-2 product-item" data-product-id="${product.id}" data-category-id="${product.category_id}">
                    <div class="product-info default-cover card">
                        <a href="javascript:void(0);" class="img-bg add-to-cart" data-product-id="${product.id}">
                            <img src="${product.image || '<?php echo e(URL::asset('/build/img/products/pos-product-01.png')); ?>'}" alt="${product.name}">
                            <span class="check-icon" style="${checkIconStyle}"><i data-feather="check" class="feather-16"></i></span>
                        </a>
                        <h6 class="cat-name"><a href="javascript:void(0);">${product.category ? product.category.name : 'N/A'}</a></h6>
                        <h6 class="product-name"><a href="javascript:void(0);">${product.name}</a></h6>
                        <div class="d-flex align-items-center justify-content-between price">
                            <span>${product.quantity} ${product.unit ? product.unit.name : 'Pcs'}</span>
                            <p>MYR ${parseFloat(product.price).toFixed(2)}</p>
                        </div>
                    </div>
                </div>
            `;
            container.append(productHtml);
        });
        
        // Reinitialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Update product count
        updateProductCount(products.length);
    }
    
    // Update product count display
    function updateProductCount(count) {
        const activeCategory = $('.category-tab.active');
        const categoryId = activeCategory.data('category-id');
        
        if (!categoryId || categoryId === '') {
            $('#all-count').text(count + ' Items');
        } else {
            $('#category-' + categoryId + '-count').text(count + ' Items');
        }
    }
    
    // Update product checkmarks based on cart
    function updateProductCheckmarks() {
        $('.product-item').each(function() {
            const productId = parseInt($(this).data('product-id'));
            const isInCart = cart.some(item => item.product_id === productId);
            const checkIcon = $(this).find('.check-icon');
            
            if (isInCart) {
                checkIcon.show();
            } else {
                checkIcon.hide();
            }
        });
        
        // Reinitialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Add to cart handler - show modal
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent event bubbling
        
        const $this = $(this);
        
        // Prevent double-click
        if ($this.hasClass('loading')) {
            return false;
        }
        
        $this.addClass('loading');
        const productId = $this.data('product-id');
        
        // Get product details
        $.ajax({
            url: '<?php echo e(route("pos.product", ":id")); ?>'.replace(':id', productId),
            method: 'GET',
            success: function(response) {
                $this.removeClass('loading');
                if (response.success) {
                    showProductModal(response.product);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error loading product details.',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false
                    });
                }
            },
            error: function(xhr) {
                $this.removeClass('loading');
                console.error('Error loading product:', xhr);
                
                let errorMessage = 'Error loading product details.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 404) {
                    errorMessage = 'Product not found.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again.';
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            }
        });
        
        return false; // Prevent default and stop propagation
    });
    
    // Helper function to check if unit allows decimals
    function allowsDecimals(unitShortName) {
        const unit = unitShortName.toLowerCase();
        // Only Kg and Sq.ft allow decimals
        return unit === 'kg' || unit === 'sq.ft' || unit === 'sqft';
    }
    
    // Show product quantity modal
    function showProductModal(product) {
        currentProduct = product;
        const unitName = product.unit ? product.unit.name : 'Pc';
        const unitShortName = product.unit ? product.unit.short_name : 'Pc';
        
        // Close any existing modals first
        $('.modal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        $('#modal-product-name').text(product.name);
        $('#modal-product-price').text('MYR ' + parseFloat(product.price).toFixed(2));
        $('#modal-product-unit').text(unitName);
        $('#quantity-unit-label').text(unitShortName);
        $('#product-quantity').val(1);
        
        // Set min and step based on unit type
        if (allowsDecimals(unitShortName)) {
            $('#product-quantity').attr('min', '0.1');
            $('#product-quantity').attr('step', '0.1');
            $('#product-quantity').removeAttr('pattern');
            $('#quantity-hint').text('Enter the quantity or weight (decimals allowed, e.g., 3.2, 3.5, 5.0)');
        } else {
            // Piece, Set, Pair - integers only
            $('#product-quantity').attr('min', '1');
            $('#product-quantity').attr('step', '1');
            $('#product-quantity').attr('pattern', '[0-9]*');
            $('#quantity-hint').text('Enter the quantity (whole numbers only, e.g., 1, 2, 5)');
        }
        
        // Ensure current value is valid for the unit type
        const currentVal = parseFloat($('#product-quantity').val()) || 1;
        if (!allowsDecimals(unitShortName) && currentVal % 1 !== 0) {
            $('#product-quantity').val(Math.round(currentVal));
        }
        
        // Reset button to "Add to Cart"
        $('#add-to-cart-btn').text('Add to Cart').off('click').on('click', addToCartHandler);
        
        updateModalTotal();
        
        // Show modal with a small delay to ensure cleanup is done
        setTimeout(function() {
            const modalElement = document.getElementById('productQuantityModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
                modal.show();
            }
        }, 100);
    }
    
    // Reset modal when closed
    $('#productQuantityModal').on('hidden.bs.modal', function() {
        currentProduct = null;
        $('#add-to-cart-btn').text('Add to Cart').off('click').on('click', addToCartHandler);
    });
    
    // Update modal total price
    function updateModalTotal() {
        if (!currentProduct) return;
        
        const quantity = parseFloat($('#product-quantity').val()) || 0;
        const price = parseFloat(currentProduct.price) || 0;
        const total = parseFloat((quantity * price).toFixed(2));
        
        $('#modal-total-price').text('MYR ' + total.toFixed(2));
    }
    
    // Quantity input change
    $('#product-quantity').on('input', function() {
        // If unit doesn't allow decimals, round to integer immediately
        if (currentProduct && currentProduct.unit) {
            const unitShortName = currentProduct.unit.short_name;
            if (!allowsDecimals(unitShortName)) {
                const value = $(this).val();
                // Remove decimal point and everything after it
                if (value.includes('.') || value.includes(',')) {
                    const intValue = Math.max(1, Math.floor(parseFloat(value) || 1));
                    $(this).val(intValue);
                }
            }
        }
        updateModalTotal();
    });
    
    // Validate on blur/change
    $('#product-quantity').on('blur change', function() {
        if (currentProduct && currentProduct.unit) {
            const unitShortName = currentProduct.unit.short_name;
            if (!allowsDecimals(unitShortName)) {
                let value = parseFloat($(this).val()) || 1;
                value = Math.max(1, Math.round(value));
                $(this).val(value);
                updateModalTotal();
            }
        }
    });
    
    // Prevent decimal input for non-decimal units in modal
    $('#product-quantity').on('keypress', function(e) {
        if (!currentProduct || !currentProduct.unit) return true;
        
        const unitShortName = currentProduct.unit.short_name;
        const char = String.fromCharCode(e.which);
        
        // If unit doesn't allow decimals, block decimal point
        if (!allowsDecimals(unitShortName)) {
            if (char === '.' || char === ',') {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // Also prevent paste of decimal values for non-decimal units
    $('#product-quantity').on('paste', function(e) {
        if (!currentProduct || !currentProduct.unit) return true;
        
        const unitShortName = currentProduct.unit.short_name;
        if (!allowsDecimals(unitShortName)) {
            const paste = (e.originalEvent || e).clipboardData.getData('text');
            if (paste.includes('.') || paste.includes(',')) {
                e.preventDefault();
                // Round to integer
                const intValue = Math.floor(parseFloat(paste) || 1);
                $(this).val(intValue);
                updateModalTotal();
                return false;
            }
        }
        return true;
    });
    
    // Add to cart handler function
    function addToCartHandler() {
        if (!currentProduct) return;
        
        let quantity = parseFloat($('#product-quantity').val()) || 0;
        if (quantity <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Quantity',
                text: 'Please enter a valid quantity',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false
            });
            return;
        }
        
        // Round based on unit type - force integer for non-decimal units
        const unitShortName = currentProduct.unit ? currentProduct.unit.short_name : 'Pc';
        let roundedQuantity;
        if (allowsDecimals(unitShortName)) {
            roundedQuantity = parseFloat(quantity.toFixed(1));
        } else {
            // Force integer - round and ensure minimum 1
            roundedQuantity = Math.max(1, Math.round(quantity));
            // Update the input field to show the rounded value
            $('#product-quantity').val(roundedQuantity);
        }
        
        const price = parseFloat(currentProduct.price) || 0;
        const subtotal = parseFloat((roundedQuantity * price).toFixed(2));
        
        // Check if product already in cart
        const existingIndex = cart.findIndex(item => item.product_id === currentProduct.id);
        
        if (existingIndex >= 0) {
            // Update existing item
            const newQuantity = parseFloat((cart[existingIndex].quantity + roundedQuantity).toFixed(1));
            cart[existingIndex].quantity = newQuantity;
            cart[existingIndex].subtotal = parseFloat((newQuantity * price).toFixed(2));
        } else {
            // Add new item
            cart.push({
                product_id: currentProduct.id,
                product_name: currentProduct.name,
                product_sku: currentProduct.sku,
                product_image: currentProduct.image,
                unit_name: currentProduct.unit ? currentProduct.unit.name : 'Pc',
                unit_short_name: unitShortName,
                price: price,
                quantity: roundedQuantity,
                subtotal: subtotal
            });
        }
        
        // Close modal and update cart display
        bootstrap.Modal.getInstance(document.getElementById('productQuantityModal')).hide();
        updateCartDisplay();
        updateTotals();
        updateProductCheckmarks();
    }
    
    // Add to cart button
    $('#add-to-cart-btn').on('click', addToCartHandler);
    
    // Update cart display
    function updateCartDisplay() {
        const cartContainer = $('#cart-items');
        const cartCount = $('#cart-count');
        
        cartCount.text(cart.length);
        
        if (cart.length === 0) {
            cartContainer.html('<div class="text-center py-3"><p class="text-muted mb-0">No items in cart</p></div>');
            return;
        }
        
        let cartHtml = '';
        cart.forEach(function(item, index) {
            const productImage = item.product_image ? 
                '<?php echo e(URL::asset("")); ?>' + item.product_image : 
                '<?php echo e(URL::asset("/build/img/products/pos-product-01.png")); ?>';
            
            cartHtml += `
                <div class="product-list d-flex align-items-center justify-content-between" data-cart-index="${index}">
                    <div class="d-flex align-items-center product-info">
                        <a href="javascript:void(0);" class="img-bg">
                            <img src="${productImage}" alt="${item.product_name}">
                        </a>
                        <div class="info">
                            <span>${item.product_sku}</span>
                            <h6><a href="javascript:void(0);">${item.product_name}</a></h6>
                            <p>MYR ${parseFloat(item.price).toFixed(2)}/${item.unit_short_name}</p>
                            <small class="text-muted">${parseFloat(item.quantity).toFixed(allowsDecimals(item.unit_short_name) ? 1 : 0)} ${item.unit_short_name} × MYR ${parseFloat(item.price).toFixed(2)} = MYR ${parseFloat(item.subtotal).toFixed(2)}</small>
                        </div>
                    </div>
                    <div class="qty-item text-center">
                        <input type="number" class="form-control text-center cart-quantity" data-index="${index}" 
                            value="${parseFloat(item.quantity).toFixed(allowsDecimals(item.unit_short_name) ? 1 : 0)}" 
                            min="${allowsDecimals(item.unit_short_name) ? '0.1' : '1'}" 
                            step="${allowsDecimals(item.unit_short_name) ? '0.1' : '1'}"
                            style="width: 100px;">
                    </div>
                    <div class="d-flex align-items-center action">
                        <a class="btn-icon edit-icon me-2" href="javascript:void(0);" onclick="editCartItem(${index})">
                            <i data-feather="edit" class="feather-14"></i>
                        </a>
                        <a class="btn-icon delete-icon confirm-text" href="javascript:void(0);" onclick="removeCartItem(${index})">
                            <i data-feather="trash-2" class="feather-14"></i>
                        </a>
                    </div>
                </div>
            `;
        });
        
        cartContainer.html(cartHtml);
        
        // Reinitialize feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Increase quantity (if buttons are re-added in future)
    $(document).on('click', '.cart-increase', function() {
        const index = parseInt($(this).data('index'));
        if (cart[index]) {
            const increment = allowsDecimals(cart[index].unit_short_name) ? 0.1 : 1;
            cart[index].quantity = allowsDecimals(cart[index].unit_short_name) ? 
                parseFloat((cart[index].quantity + increment).toFixed(1)) : 
                Math.round(cart[index].quantity + increment);
            cart[index].subtotal = parseFloat((cart[index].quantity * cart[index].price).toFixed(2));
            updateCartDisplay();
            updateTotals();
            updateProductCheckmarks();
        }
    });
    
    // Decrease quantity (if buttons are re-added in future)
    $(document).on('click', '.cart-decrease', function() {
        const index = parseInt($(this).data('index'));
        if (cart[index]) {
            const minQty = allowsDecimals(cart[index].unit_short_name) ? 0.1 : 1;
            if (cart[index].quantity > minQty) {
                const decrement = allowsDecimals(cart[index].unit_short_name) ? 0.1 : 1;
                cart[index].quantity = allowsDecimals(cart[index].unit_short_name) ? 
                    parseFloat((cart[index].quantity - decrement).toFixed(1)) : 
                    Math.round(cart[index].quantity - decrement);
                if (cart[index].quantity < minQty) cart[index].quantity = minQty;
                cart[index].subtotal = parseFloat((cart[index].quantity * cart[index].price).toFixed(2));
                updateCartDisplay();
                updateTotals();
                updateProductCheckmarks();
            }
        }
    });
    
    // Direct quantity input change
    $(document).on('change blur', '.cart-quantity', function() {
        const index = parseInt($(this).data('index'));
        if (!cart[index]) return;
        
        let newQuantity = parseFloat($(this).val()) || 0;
        const allowsDecimal = allowsDecimals(cart[index].unit_short_name);
        const minQty = allowsDecimal ? 0.1 : 1;
        
        // Validate minimum quantity
        if (newQuantity < minQty) {
            newQuantity = minQty;
            $(this).val(newQuantity.toFixed(allowsDecimal ? 1 : 0));
        }
        
        // Round to appropriate decimal places
        if (allowsDecimal) {
            newQuantity = parseFloat(newQuantity.toFixed(1));
        } else {
            // For Piece, Set, Pair - force integer
            newQuantity = Math.round(newQuantity);
            if (newQuantity < 1) newQuantity = 1;
        }
        
        // Update cart item
        cart[index].quantity = newQuantity;
        cart[index].subtotal = parseFloat((newQuantity * cart[index].price).toFixed(2));
        
        // Update display
        updateCartDisplay();
        updateTotals();
        updateProductCheckmarks();
    });
    
    // Prevent invalid input on keypress
    $(document).on('keypress', '.cart-quantity', function(e) {
        const index = parseInt($(this).data('index'));
        if (!cart[index]) return true;
        
        const allowsDecimal = allowsDecimals(cart[index].unit_short_name);
        const char = String.fromCharCode(e.which);
        
        // Allow numbers, decimal point (only for units that allow decimals), backspace, delete, etc.
        if (allowsDecimal) {
            // For Kg, Sq.ft: allow numbers and one decimal point
            if (!/[0-9.]/.test(char) || (char === '.' && $(this).val().indexOf('.') !== -1)) {
                e.preventDefault();
                return false;
            }
        } else {
            // For Piece, Set, Pair: only allow integers
            if (!/[0-9]/.test(char)) {
                e.preventDefault();
                return false;
            }
        }
        
        return true;
    });
    
    // Edit cart item
    window.editCartItem = function(index) {
        if (!cart[index]) return;
        
        const item = cart[index];
        currentProduct = {
            id: item.product_id,
            name: item.product_name,
            sku: item.product_sku,
            price: item.price,
            unit: {
                name: item.unit_name,
                short_name: item.unit_short_name
            }
        };
        
        $('#modal-product-name').text(item.product_name);
        $('#modal-product-price').text('MYR ' + parseFloat(item.price).toFixed(2));
        $('#modal-product-unit').text(item.unit_name);
        $('#quantity-unit-label').text(item.unit_short_name);
        $('#product-quantity').val(parseFloat(item.quantity).toFixed(allowsDecimals(item.unit_short_name) ? 1 : 0));
        // Set min and step based on unit type
        if (allowsDecimals(item.unit_short_name)) {
            $('#product-quantity').attr('min', '0.1');
            $('#product-quantity').attr('step', '0.1');
        } else {
            $('#product-quantity').attr('min', '1');
            $('#product-quantity').attr('step', '1');
        }
        
        updateModalTotal();
        
        // Update add button to save instead
        $('#add-to-cart-btn').text('Update').off('click').on('click', function() {
            const quantity = parseFloat($('#product-quantity').val()) || 0;
            if (quantity <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Quantity',
                    text: 'Please enter a valid quantity',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
                return;
            }
            
            // Round based on unit type
            const roundedQuantity = allowsDecimals(item.unit_short_name) ? 
                parseFloat(quantity.toFixed(1)) : 
                Math.round(quantity);
            
            cart[index].quantity = roundedQuantity;
            cart[index].subtotal = parseFloat((roundedQuantity * cart[index].price).toFixed(2));
            
            bootstrap.Modal.getInstance(document.getElementById('productQuantityModal')).hide();
            $('#add-to-cart-btn').text('Add to Cart').off('click').on('click', addToCartHandler);
            updateCartDisplay();
            updateTotals();
            updateProductCheckmarks();
        });
        
        const modal = new bootstrap.Modal(document.getElementById('productQuantityModal'));
        modal.show();
    };
    
    // Remove cart item
    window.removeCartItem = function(index) {
        Swal.fire({
            title: 'Remove Item?',
            text: 'Are you sure you want to remove this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-secondary',
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                cart.splice(index, 1);
                updateCartDisplay();
                updateTotals();
                updateProductCheckmarks();
                Swal.fire({
                    icon: 'success',
                    title: 'Removed',
                    text: 'Item has been removed from cart',
                    showConfirmButton: false,
                    timer: 1500,
                    buttonsStyling: false
                });
            }
        });
    };
    
    // Clear cart
    $('#clear-cart').on('click', function() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Clear Cart?',
            text: 'Are you sure you want to clear all items from the cart?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear all',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-secondary',
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                updateCartDisplay();
                updateTotals();
                updateProductCheckmarks();
                Swal.fire({
                    icon: 'success',
                    title: 'Cleared',
                    text: 'All items have been removed from cart',
                    showConfirmButton: false,
                    timer: 1500,
                    buttonsStyling: false
                });
            }
        });
    });
    
    // Update totals
    function updateTotals() {
        let subtotal = 0;
        cart.forEach(function(item) {
            subtotal += item.subtotal;
        });
        
        const taxPercent = parseFloat($('#order-tax').val()) || 0;
        const shipping = parseFloat($('#order-shipping').val()) || 0;
        const discount = parseFloat($('#order-discount').val()) || 0;
        
        const tax = (subtotal * taxPercent) / 100;
        const grandTotal = subtotal + tax + shipping - discount;
        
        $('#subtotal-amount').text('MYR ' + subtotal.toFixed(2));
        $('#tax-amount').text('MYR ' + tax.toFixed(2));
        $('#shipping-amount').text('MYR ' + shipping.toFixed(2));
        $('#discount-amount').text('MYR ' + discount.toFixed(2));
        $('#grand-total').text('MYR ' + grandTotal.toFixed(2));
        $('#grand-total-display').text('MYR ' + grandTotal.toFixed(2));
    }
    
    // Tax, shipping, discount change handlers
    $('#order-tax, #order-shipping, #order-discount').on('input', function() {
        updateTotals();
    });
    
    // Payment method selection (using event delegation)
    $(document).on('click', '.payment-method-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Payment button clicked!');
        
        // Remove active class and styles from all payment methods
        $('.payment-method-btn').each(function() {
            $(this).removeClass('active');
            $(this).css({
                'border': '3px solid #e9ecef',
                'background': '#ffffff',
                'box-shadow': 'none',
                'transform': 'none'
            });
        });
        
        // Add active class and styles to clicked method
        $(this).addClass('active');
        $(this).css({
            'border': '3px solid #0067e2',
            'background': 'linear-gradient(135deg, #e6f2ff 0%, #cce5ff 100%)',
            'box-shadow': '0 6px 20px rgba(0, 103, 226, 0.5)',
            'transform': 'translateY(-3px) scale(1.02)'
        });
        
        // Change text color to orange
        $(this).find('span').css('color', '#0067e2');
        $(this).find('span').css('font-weight', '700');
        
        // Remove grayscale from icon
        $(this).find('img').css('filter', 'grayscale(0%)');
        
        // Store selected payment method
        selectedPaymentMethod = $(this).data('method');
        
        console.log('Payment method selected:', selectedPaymentMethod);
        console.log('Active class added:', $(this).hasClass('active'));
    });
    
    // Complete order
    $('#complete-order').on('click', function() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Empty Cart',
                text: 'Please add items to cart first',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false
            });
            return;
        }
        
        if (!selectedPaymentMethod) {
            Swal.fire({
                icon: 'warning',
                title: 'Payment Method Required',
                text: 'Please select a payment method',
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false
            });
            return;
        }
        
        const items = cart.map(function(item) {
            return {
                product_id: item.product_id,
                quantity: item.quantity,
                price: item.price
            };
        });
        
        const taxPercent = parseFloat($('#order-tax').val()) || 0;
        const shipping = parseFloat($('#order-shipping').val()) || 0;
        const discount = parseFloat($('#order-discount').val()) || 0;
        
        let subtotal = 0;
        cart.forEach(function(item) {
            subtotal += item.subtotal;
        });
        
        const tax = (subtotal * taxPercent) / 100;
        const total = subtotal + tax + shipping - discount;
        
        $.ajax({
            url: '<?php echo e(route("pos.checkout")); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                items: items,
                customer_name: $('#customer-select').val() || 'Walk-in Customer',
                payment_method: selectedPaymentMethod,
                tax: tax,
                discount: discount,
                shipping: shipping,
                subtotal: subtotal
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Completed!',
                        html: 'Order completed successfully!<br><strong>Order #' + response.order_number + '</strong><br><small class="text-muted mt-2">View in "View Orders" or "Purchases" menu</small>',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Clear everything
                        cart = [];
                        selectedPaymentMethod = null;
                        
                        // Reset form fields
                        $('#order-tax, #order-shipping, #order-discount').val(0);
                        
                        // Remove active state and styles from payment methods
                        $('.payment-method-btn').each(function() {
                            $(this).removeClass('active');
                            $(this).css({
                                'border': '3px solid #e9ecef',
                                'background': '#ffffff',
                                'box-shadow': 'none',
                                'transform': 'none'
                            });
                            $(this).find('span').css({
                                'color': '#6c757d',
                                'font-weight': '600'
                            });
                            $(this).find('img').css('filter', 'grayscale(50%)');
                        });
                        
                        // Update displays
                        updateCartDisplay();
                        updateTotals();
                        updateProductCheckmarks();
                        
                        // Scroll to top of page smoothly
                        $('html, body').animate({
                            scrollTop: 0
                        }, 600, 'swing');
                        
                        // Alternative: instant scroll (uncomment if you prefer instant)
                        // window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'An error occurred',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false
                    });
                }
            },
            error: function(xhr) {
                const error = xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            }
        });
    });
    
    // Void order
    $('#void-order').on('click', function() {
        if (cart.length === 0) return;
        Swal.fire({
            title: 'Void Order?',
            text: 'Are you sure you want to void this order? All items will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, void it',
            cancelButtonText: 'Cancel',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-secondary',
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                selectedPaymentMethod = null;
                $('#order-tax, #order-shipping, #order-discount').val(0);
                
                // Reset payment method styles
                $('.payment-method-btn').each(function() {
                    $(this).removeClass('active');
                    $(this).css({
                        'border': '3px solid #e9ecef',
                        'background': '#ffffff',
                        'box-shadow': 'none',
                        'transform': 'none'
                    });
                    $(this).find('span').css({
                        'color': '#6c757d',
                        'font-weight': '600'
                    });
                    $(this).find('img').css('filter', 'grayscale(50%)');
                });
                
                updateCartDisplay();
                updateTotals();
                updateProductCheckmarks();
                Swal.fire({
                    icon: 'success',
                    title: 'Order Voided',
                    text: 'Order has been voided',
                    showConfirmButton: false,
                    timer: 1500,
                    buttonsStyling: false
                });
            }
        });
    });
    
    // Reset button
    $('.btn-info').on('click', function(e) {
        const btnText = $(this).text().trim();
        if (btnText === 'Reset') {
            e.preventDefault();
            if (cart.length > 0) {
                Swal.fire({
                    title: 'Reset POS?',
                    text: 'This will clear the cart and reset all fields. Are you sure?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reset it',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-secondary',
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        selectedPaymentMethod = null;
                        $('#order-tax, #order-shipping, #order-discount').val(0);
                        
                        // Reset payment method styles
                        $('.payment-method-btn').each(function() {
                            $(this).removeClass('active');
                            $(this).css({
                                'border': '3px solid #e9ecef',
                                'background': '#ffffff',
                                'box-shadow': 'none',
                                'transform': 'none'
                            });
                            $(this).find('span').css({
                                'color': '#6c757d',
                                'font-weight': '600'
                            });
                            $(this).find('img').css('filter', 'grayscale(50%)');
                        });
                        
                        $('#product-search').val('');
                        $('#clear-search').hide();
                        updateCartDisplay();
                        updateTotals();
                        updateProductCheckmarks();
                    }
                });
            }
        }
    });
    
    // View Orders modal
    $('#orders').on('show.bs.modal', function() {
        loadOrders('all');
    });
    
    // Transactions modal
    $('#recents').on('show.bs.modal', function() {
        loadTransactions();
    });
    
    // Load orders function
    function loadOrders(type = 'all') {
        $('#orders-list').html('<div class="text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        $.ajax({
            url: '<?php echo e(route("pos.orders")); ?>',
            method: 'GET',
            data: { type: type },
            success: function(response) {
                console.log('Orders response:', response);
                if (response.success) {
                    renderOrders(response.orders);
                } else {
                    $('#orders-list').html('<div class="text-center py-4"><p class="text-danger">Failed to load orders</p></div>');
                }
            },
            error: function(xhr) {
                console.error('Error loading orders:', xhr);
                let errorMsg = 'Error loading orders';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#orders-list').html('<div class="text-center py-4"><p class="text-danger">' + errorMsg + '</p></div>');
            }
        });
    }
    
    // Load transactions function
    function loadTransactions() {
        $.ajax({
            url: '<?php echo e(route("pos.orders")); ?>',
            method: 'GET',
            data: { type: 'recent' },
            success: function(response) {
                if (response.success) {
                    renderTransactions(response.orders);
                }
            },
            error: function(xhr) {
                $('#transactions-list').html('<div class="text-center py-4"><p class="text-danger">Error loading transactions</p></div>');
            }
        });
    }
    
    // Render orders
    function renderOrders(orders) {
        const container = $('#orders-list');
        
        console.log('Rendering orders:', orders);
        
        // Handle both paginated (orders.data) and non-paginated (orders array) responses
        let ordersList = orders.data || orders;
        
        console.log('Orders list:', ordersList, 'Length:', ordersList ? ordersList.length : 0);
        
        if (!ordersList || ordersList.length === 0) {
            container.html('<div class="text-center py-4"><p class="text-muted">No orders found<br><small>Create an order from POS to see it here</small></p></div>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Date</th><th>Action</th></tr></thead><tbody>';
        
        ordersList.forEach(function(order) {
            const date = new Date(order.created_at).toLocaleString();
            const statusBadge = order.payment_status === 'paid' ? 'bg-success' : 'bg-warning';
            const itemsCount = order.items ? order.items.length : 0;
            
            html += `
                <tr>
                    <td><strong>${order.order_number}</strong></td>
                    <td>${order.customer_name || 'Walk-in Customer'}</td>
                    <td>${itemsCount} items</td>
                    <td><strong>MYR ${parseFloat(order.total).toFixed(2)}</strong></td>
                    <td><span class="badge ${statusBadge}">${order.payment_status}</span></td>
                    <td>${date}</td>
                    <td>
                        <button class="btn btn-sm btn-primary view-order-details" data-order-id="${order.id}">
                            <i class="fa fa-eye"></i> View
                        </button>
                    </td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.html(html);
    }
    
    // Render transactions
    function renderTransactions(orders) {
        const container = $('#transactions-list');
        
        if (!orders || orders.length === 0) {
            container.html('<div class="text-center py-4"><p class="text-muted">No recent transactions</p></div>');
            return;
        }
        
        let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment Method</th><th>Status</th><th>Date</th></tr></thead><tbody>';
        
        orders.forEach(function(order) {
            const date = new Date(order.created_at).toLocaleString();
            const statusBadge = order.payment_status === 'paid' ? 'bg-success' : 'bg-warning';
            const paymentMethod = order.payment_method ? order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1) : 'N/A';
            
            html += `
                <tr>
                    <td><strong>${order.order_number}</strong></td>
                    <td>${order.customer_name || 'Walk-in Customer'}</td>
                    <td>${order.items.length} items</td>
                    <td><strong>MYR ${parseFloat(order.total).toFixed(2)}</strong></td>
                    <td>${paymentMethod}</td>
                    <td><span class="badge ${statusBadge}">${order.payment_status}</span></td>
                    <td>${date}</td>
                </tr>
            `;
        });
        
        html += '</tbody></table></div>';
        container.html(html);
    }
    
    // View order details (delegated event)
    $(document).on('click', '.view-order-details', function() {
        const orderId = $(this).data('order-id');
        viewOrderDetails(orderId);
    });
    
    // View order details function
    function viewOrderDetails(orderId) {
        $.ajax({
            url: '<?php echo e(route("pos.order", ":id")); ?>'.replace(':id', orderId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showOrderDetailsModal(response.order);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not load order details',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            }
        });
    }
    
    // Show order details modal
    function showOrderDetailsModal(order) {
        let itemsHtml = '';
        order.items.forEach(function(item) {
            itemsHtml += `
                <tr>
                    <td>${item.product_name}</td>
                    <td>${item.quantity}</td>
                    <td>MYR ${parseFloat(item.price).toFixed(2)}</td>
                    <td>MYR ${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>
            `;
        });
        
        const modalHtml = `
            <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
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
                                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-success">${order.payment_status}</span></p>
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
                                <h5><strong>Total:</strong> MYR ${parseFloat(order.total).toFixed(2)}</h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remove existing modal if any
        $('#orderDetailsModal').remove();
        
        // Append and show new modal
        $('body').append(modalHtml);
        const detailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        detailsModal.show();
        
        // Clean up on hide
        $('#orderDetailsModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }
    
    // Escape key to close stuck modals
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            if ($('.modal-backdrop').length > 0) {
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            }
        }
    });
    
    // Safety: Clean up stuck backdrops every 5 seconds
    setInterval(function() {
        // If there's a backdrop but no visible modal, remove it
        if ($('.modal-backdrop').length > 0 && !$('.modal.show').length) {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        }
    }, 5000);
    
    // Load customers into dropdown
    function loadCustomers() {
        $.ajax({
            url: '<?php echo e(route("customers.list")); ?>',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    const select = $('#customer-select');
                    select.empty();
                    select.append('<option value="">Walk in Customer</option>');
                    
                    response.customers.forEach(function(customer) {
                        const displayText = customer.name + (customer.phone ? ' - ' + customer.phone : '');
                        select.append(`<option value="${customer.id}" data-email="${customer.email || ''}" data-phone="${customer.phone || ''}">${displayText}</option>`);
                    });
                }
            },
            error: function(xhr) {
                console.error('Error loading customers:', xhr);
            }
        });
    }
    
    // Create customer form submission
    $('#create-customer-form').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#save-customer-btn');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: '<?php echo e(route("customers.store")); ?>',
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                name: $('#customer-name').val(),
                email: $('#customer-email').val(),
                phone: $('#customer-phone').val(),
                address: $('#customer-address').val(),
                city: $('#customer-city').val(),
                country: $('#customer-country').val()
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                        timer: 2000
                    });
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('create')).hide();
                    
                    // Clear form
                    $('#create-customer-form')[0].reset();
                    $('#customer-country').val('Malaysia');
                    
                    // Reload customers
                    loadCustomers();
                    
                    // Select the newly created customer
                    setTimeout(function() {
                        $('#customer-select').val(response.customer.id);
                    }, 100);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Error creating customer';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Load customers on page load
    loadCustomers();
    
    // Initialize payment method styles on page load
    $('.payment-method-btn').each(function() {
        $(this).css({
            'border': '3px solid #e9ecef',
            'background': '#ffffff',
            'box-shadow': 'none',
            'transform': 'none'
        });
        $(this).find('span').css({
            'color': '#6c757d',
            'font-weight': '600'
        });
        $(this).find('img').css('filter', 'grayscale(50%)');
    });
    
    // Initialize feather icons for search
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Initialize
    updateCartDisplay();
    updateTotals();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/pos.blade.php ENDPATH**/ ?>