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
                                                    <span><i data-feather="check" class="feather-16"></i></span>
                                                </a>
                                                <h6 class="cat-name"><a href="javascript:void(0);"><?php echo e($product->category->name ?? 'N/A'); ?></a></h6>
                                                <h6 class="product-name"><a href="javascript:void(0);"><?php echo e($product->name); ?></a></h6>
                                                <div class="d-flex align-items-center justify-content-between price">
                                                    <span><?php echo e($product->quantity); ?> <?php echo e($product->unit->name ?? 'Pcs'); ?></span>
                                                    <p>$<?php echo e(number_format($product->price, 2)); ?></p>
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
                                        <option>Walk in Customer</option>
                                        <option>John</option>
                                        <option>Smith</option>
                                        <option>Ana</option>
                                        <option>Elza</option>
                                    </select>
                                </div>
                                <a href="#" class="btn btn-primary btn-icon" data-bs-toggle="modal"
                                    data-bs-target="#create"><i data-feather="user-plus" class="feather-16"></i></a>
                            </div>
                            <div class="input-block">
                                <select class="select">
                                    <option>Search Products</option>
                                    <option>IPhone 14 64GB</option>
                                    <option>MacBook Pro</option>
                                    <option>Rolex Tribute V3</option>
                                    <option>Red Nike Angelo</option>
                                    <option>Airpod 2</option>
                                    <option>Oldest</option>
                                </select>
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
                                        <td class="text-end" id="subtotal-amount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Tax</td>
                                        <td class="text-end" id="tax-amount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping</td>
                                        <td class="text-end" id="shipping-amount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="danger">Discount</td>
                                        <td class="danger text-end" id="discount-amount">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td class="text-end"><strong id="grand-total">$0.00</strong></td>
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
                                Grand Total : <span id="grand-total-display">$0.00</span>
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
                        <h5 id="modal-total-price" class="text-primary">$0.00</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="add-to-cart-btn">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectedStoreId = <?php echo e(session('selected_store_id') ?? 'null'); ?>;
    
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
            const productHtml = `
                <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 pe-2 product-item" data-product-id="${product.id}" data-category-id="${product.category_id}">
                    <div class="product-info default-cover card">
                        <a href="javascript:void(0);" class="img-bg add-to-cart" data-product-id="${product.id}">
                            <img src="${product.image || '<?php echo e(URL::asset('/build/img/products/pos-product-01.png')); ?>'}" alt="${product.name}">
                            <span><i data-feather="check" class="feather-16"></i></span>
                        </a>
                        <h6 class="cat-name"><a href="javascript:void(0);">${product.category ? product.category.name : 'N/A'}</a></h6>
                        <h6 class="product-name"><a href="javascript:void(0);">${product.name}</a></h6>
                        <div class="d-flex align-items-center justify-content-between price">
                            <span>${product.quantity} ${product.unit ? product.unit.name : 'Pcs'}</span>
                            <p>$${parseFloat(product.price).toFixed(2)}</p>
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
    
    // Add to cart handler - show modal
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        
        // Get product details
        $.ajax({
            url: '<?php echo e(route("pos.product", ":id")); ?>'.replace(':id', productId),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showProductModal(response.product);
                }
            },
            error: function(xhr) {
                console.error('Error loading product:', xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error loading product details.',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
            }
        });
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
        
        $('#modal-product-name').text(product.name);
        $('#modal-product-price').text('$' + parseFloat(product.price).toFixed(2));
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
        
        const modal = new bootstrap.Modal(document.getElementById('productQuantityModal'));
        modal.show();
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
        
        $('#modal-total-price').text('$' + total.toFixed(2));
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
                            <p>$${parseFloat(item.price).toFixed(2)}/${item.unit_short_name}</p>
                            <small class="text-muted">${parseFloat(item.quantity).toFixed(allowsDecimals(item.unit_short_name) ? 1 : 0)} ${item.unit_short_name} × $${parseFloat(item.price).toFixed(2)} = $${parseFloat(item.subtotal).toFixed(2)}</small>
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
        $('#modal-product-price').text('$' + parseFloat(item.price).toFixed(2));
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
        
        $('#subtotal-amount').text('$' + subtotal.toFixed(2));
        $('#tax-amount').text('$' + tax.toFixed(2));
        $('#shipping-amount').text('$' + shipping.toFixed(2));
        $('#discount-amount').text('$' + discount.toFixed(2));
        $('#grand-total').text('$' + grandTotal.toFixed(2));
        $('#grand-total-display').text('$' + grandTotal.toFixed(2));
    }
    
    // Tax, shipping, discount change handlers
    $('#order-tax, #order-shipping, #order-discount').on('input', function() {
        updateTotals();
    });
    
    // Payment method selection
    $('.payment-method-btn').on('click', function() {
        $('.payment-method-btn').removeClass('active');
        $(this).addClass('active');
        selectedPaymentMethod = $(this).data('method');
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
                        html: 'Order completed successfully!<br><strong>Order #' + response.order_number + '</strong>',
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false
                    }).then(() => {
                        cart = [];
                        selectedPaymentMethod = null;
                        $('#order-tax, #order-shipping, #order-discount').val(0);
                        $('.payment-method-btn').removeClass('active');
                        updateCartDisplay();
                        updateTotals();
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
                $('.payment-method-btn').removeClass('active');
                updateCartDisplay();
                updateTotals();
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