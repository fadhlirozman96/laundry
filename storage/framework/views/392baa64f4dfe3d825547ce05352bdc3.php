<!-- Sidebar -->
<div class="sidebar horizontal-sidebar">
    <div id="sidebar-menu-3" class="sidebar-menu">
        <ul class="nav">
            <li class="submenu">
                <a href="<?php echo e(url('index')); ?>"
                    class="<?php echo e(Request::is('index', '/', 'sales-dashboard', 'video-call', 'audio-call', 'call-history', 'chat', 'calendar', 'email', 'todo', 'notes', 'file-manager', 'file-archived','file-document','file-favourites','file-manager-seleted','file-recent','file-shared') ? 'active subdrop' : ''); ?>"><i
                        data-feather="grid"></i><span> Main Menu</span>
                    <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('index', '/', 'sales-dashboard') ? 'active subdrop' : ''); ?>"><span>Dashboard</span>
                            <span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('index')); ?>" class="<?php echo e(Request::is('index') ? 'active' : ''); ?>">Admin
                                    Dashboard</a></li>
                            <li><a href="<?php echo e(url('sales-dashboard')); ?>"
                                    class="<?php echo e(Request::is('sales-dashboard') ? 'active' : ''); ?>">Sales Dashboard</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('video-call', 'audio-call', 'call-history', 'chat', 'calendar', 'email', 'todo', 'notes', 'file-manager', 'file-archived','file-document','file-favourites','file-manager-seleted','file-recent','file-shared','file-manager-deleted') ? 'active subdrop' : ''); ?> "><span>Application</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('chat')); ?>" class="<?php echo e(Request::is('chat') ? 'active' : ''); ?>">Chat</a>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);"
                                    class="<?php echo e(Request::is('video-call', 'audio-call', 'call-history') ? 'active subdrop' : ''); ?> "><span>Call</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('video-call')); ?>"
                                            class="<?php echo e(Request::is('video-call') ? 'active' : ''); ?>">Video Call</a></li>
                                    <li><a href="<?php echo e(url('audio-call')); ?>"
                                            class="<?php echo e(Request::is('audio-call') ? 'active' : ''); ?>">Audio Call</a></li>
                                    <li><a href="<?php echo e(url('call-history')); ?>"
                                            class="<?php echo e(Request::is('call-history') ? 'active' : ''); ?>">Call History</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(url('calendar')); ?>"
                                    class="<?php echo e(Request::is('calendar') ? 'active' : ''); ?>">Calendar</a></li>
                            <li><a href="<?php echo e(url('email')); ?>"
                                    class="<?php echo e(Request::is('email') ? 'active' : ''); ?>">Email</a></li>
                            <li><a href="<?php echo e(url('todo')); ?>" class="<?php echo e(Request::is('todo') ? 'active' : ''); ?>">To
                                    Do</a></li>
                            <li><a href="<?php echo e(url('notes')); ?>"
                                    class="<?php echo e(Request::is('notes') ? 'active' : ''); ?>">Notes</a></li>
                            <li><a href="<?php echo e(url('file-manager')); ?>"
                                    class="<?php echo e(Request::is('file-manager', 'file-archived','file-document','file-favourites','file-manager-seleted','file-recent','file-shared','file-manager-deleted') ? 'active' : ''); ?>">File Manager</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('product-list','product-details','edit-product','add-product', 'category-list', 'units') ? 'active subdrop' : ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/product.svg')); ?>" alt="img"><span> Inventory
                    </span> <span class="menu-arrow"></span></a>
                <ul>
                    <li><a
                            href="<?php echo e(url('product-list')); ?>"class="<?php echo e(Request::is('product-list','product-details') ? 'active' : ''); ?>"><span>Products</span></a>
                    </li>
                    <li><a href="<?php echo e(url('add-product')); ?>"class="<?php echo e(Request::is('add-product','edit-product') ? 'active' : ''); ?>"><span>Create
                                Product</span></a></li>
                    <li><a
                            href="<?php echo e(url('category-list')); ?>"class="<?php echo e(Request::is('category-list') ? 'active' : ''); ?>"><span>Category</span></a>
                    </li>
                    <li><a
                            href="<?php echo e(url('units')); ?>"class="<?php echo e(Request::is('units') ? 'active' : ''); ?>"><span>Units</span></a>
                    </li>
                </ul>
            </li>
            <?php if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isBusinessOwner())): ?>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('store-list') ? 'active subdrop' : ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/app-icon-01.svg')); ?>" alt="img"><span> Stores
                    </span> <span class="menu-arrow"></span></a>
                <ul>
                    <li><a
                            href="<?php echo e(url('store-list')); ?>"class="<?php echo e(Request::is('store-list') ? 'active' : ''); ?>"><span>Manage Stores</span></a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('sales-list', 'invoice-report', 'sales-returns', 'quotation-list', 'pos', 'coupons', 'purchase-list', 'purchase-order-report', 'purchase-returns', 'manage-stocks', 'stock-adjustment', 'stock-transfer', 'expense-list', 'expense-category') ? 'active subdrop' : ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/purchase1.svg')); ?>" alt="img"><span>Sales &amp;
                        Purchase</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('sales-list', 'invoice-report', 'sales-returns', 'quotation-list', 'pos', 'coupons') ? 'active subdrop' : ''); ?>"><span>Sales</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('sales-list')); ?>"
                                    class="<?php echo e(Request::is('sales-list') ? 'active' : ''); ?>"><span>Sales</span></a></li>
                            <li><a href="<?php echo e(url('invoice-report')); ?>"
                                    class="<?php echo e(Request::is('invoice-report') ? 'active' : ''); ?>"><span>Invoices</span></a>
                            </li>
                            <li><a href="<?php echo e(url('sales-returns')); ?>"
                                    class="<?php echo e(Request::is('sales-returns') ? 'active' : ''); ?>"><span>Sales
                                        Return</span></a></li>
                            <li><a href="<?php echo e(url('quotation-list')); ?>"
                                    class="<?php echo e(Request::is('quotation-list') ? 'active' : ''); ?>"><span>Quotation</span></a>
                            </li>
                            <li><a href="<?php echo e(url('pos')); ?>"
                                    class="<?php echo e(Request::is('pos') ? 'active' : ''); ?>"><span>POS</span></a></li>
                            <li><a href="<?php echo e(url('coupons')); ?>"
                                    class="<?php echo e(Request::is('coupons') ? 'active' : ''); ?>"><span>Coupons</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('purchase-list', 'purchase-order-report', 'purchase-returns', 'manage-stocks', 'stock-adjustment', 'stock-transfer') ? 'active subdrop' : ''); ?> "><span>Purchase</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('purchase-list')); ?>"
                                    class="<?php echo e(Request::is('purchase-list') ? 'active' : ''); ?>"><span>Purchases</span></a>
                            </li>
                            <li><a href="<?php echo e(url('purchase-order-report')); ?>"
                                    class="<?php echo e(Request::is('purchase-order-report') ? 'active' : ''); ?>"><span>Purchase
                                        Order</span></a></li>
                            <li><a href="<?php echo e(url('purchase-returns')); ?>"
                                    class="<?php echo e(Request::is('purchase-returns') ? 'active' : ''); ?>"><span>Purchase
                                        Return</span></a></li>
                            <li><a href="<?php echo e(url('manage-stocks')); ?>"
                                    class="<?php echo e(Request::is('manage-stocks') ? 'active' : ''); ?>"><span>Manage
                                        Stock</span></a></li>
                            <li><a href="<?php echo e(url('stock-adjustment')); ?>"
                                    class="<?php echo e(Request::is('stock-adjustment') ? 'active' : ''); ?>"><span>Stock
                                        Adjustment</span></a></li>
                            <li><a href="<?php echo e(url('stock-transfer')); ?>"
                                    class="<?php echo e(Request::is('stock-transfer') ? 'active' : ''); ?>"><span>Stock
                                        Transfer</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('expense-list', 'expense-category') ? 'active subdrop' : ''); ?> "><span>Expenses</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('expense-list')); ?>"
                                    class="<?php echo e(Request::is('expense-list') ? 'active' : ''); ?>">Expenses</a></li>
                            <li><a href="<?php echo e(url('expense-category')); ?>"
                                    class="<?php echo e(Request::is('expense-category') ? 'active' : ''); ?>">Expense Category</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('customers','suppliers','store-list','warehouse','roles-permissions','permissions','delete-account','ui-alerts','ui-accordion','ui-avatar','ui-badges','ui-borders','ui-buttons','ui-buttons-group','ui-breadcrumb','ui-cards','ui-carousel','ui-colors','ui-dropdowns','ui-grid','ui-images','ui-lightbox','ui-modals','ui-media','ui-offcanvas','ui-pagination','ui-popovers','ui-progress','ui-placeholders','ui-rangeslider','ui-spinner','ui-sweetalerts','ui-nav-tabs','ui-toasts','ui-tooltips','ui-typography','ui-video','ui-ribbon','ui-clipboard','ui-drag-drop','ui-rating','ui-text-editor','ui-counter','ui-scrollbar','ui-stickynote','ui-timeline','chart-apex','chart-c3','chart-js','chart-morris','chart-flot','chart-peity','icon-fontawesome','icon-feather','icon-ionic','icon-material','icon-pe7','icon-simpleline','icon-themify','icon-weather','icon-typicon','icon-flag','form-wizard','form-select2','form-validation','form-floating-labels','form-vertical','form-horizontal','form-basic-inputs','form-checkbox-radios','form-input-groups','form-grid-gutters','form-select','form-mask','form-fileupload','form-horizontal','form-vertical','form-floating-labels','form-validation','form-select2','form-wizard','tables-basic','data-tables')? 'active subdrop': ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/users1.svg')); ?>" alt="img"><span>User Management</span> <span
                        class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('customers', 'suppliers', 'store-list', 'warehouse') ? 'active subdrop' : ''); ?>"><span>People</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('customers')); ?>"
                                    class="<?php echo e(Request::is('customers') ? 'active' : ''); ?>"><span>Customers</span></a>
                            </li>
                            <li><a href="<?php echo e(url('suppliers')); ?>"
                                    class="<?php echo e(Request::is('suppliers') ? 'active' : ''); ?>"><span>Suppliers</span></a>
                            </li>
                            <li><a href="<?php echo e(url('store-list')); ?>"
                                    class="<?php echo e(Request::is('store-list') ? 'active' : ''); ?>"><span>Stores</span></a>
                            </li>
                            <li><a href="<?php echo e(url('warehouse')); ?>"
                                    class="<?php echo e(Request::is('warehouse') ? 'active' : ''); ?>"><span>Warehouses</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('roles-permissions', 'delete-account','permissions') ? 'active subdrop' : ''); ?> "><span>Roles
                                &amp; Permissions</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('roles-permissions')); ?>"
                                    class="<?php echo e(Request::is('roles-permissions','permissions') ? 'active' : ''); ?>"><span>Roles &
                                        Permissions</span></a></li>
                            <li><a href="<?php echo e(url('delete-account')); ?>"
                                    class="<?php echo e(Request::is('delete-account') ? 'active' : ''); ?>"><span>Delete Account
                                        Request</span></a></li>

                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('ui-alerts', 'ui-accordion', 'ui-avatar', 'ui-badges', 'ui-borders', 'ui-buttons', 'ui-buttons-group', 'ui-breadcrumb', 'ui-cards', 'ui-carousel', 'ui-colors', 'ui-dropdowns', 'ui-grid', 'ui-images', 'ui-lightbox', 'ui-modals', 'ui-media', 'ui-offcanvas', 'ui-pagination', 'ui-popovers', 'ui-progress', 'ui-placeholders', 'ui-rangeslider', 'ui-spinner', 'ui-sweetalerts', 'ui-nav-tabs', 'ui-toasts', 'ui-tooltips', 'ui-typography', 'ui-video') ? 'active subdrop' : ''); ?> "><span>Base
                                UI</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('ui-alerts')); ?>"
                                    class="<?php echo e(Request::is('ui-alerts') ? 'active' : ''); ?>">Alerts</a></li>
                            <li><a href="<?php echo e(url('ui-accordion')); ?>"
                                    class="<?php echo e(Request::is('ui-accordion') ? 'active' : ''); ?>">Accordion</a></li>
                            <li><a href="<?php echo e(url('ui-avatar')); ?>"
                                    class="<?php echo e(Request::is('ui-avatar') ? 'active' : ''); ?>">Avatar</a></li>
                            <li><a href="<?php echo e(url('ui-badges')); ?>"
                                    class="<?php echo e(Request::is('ui-badges') ? 'active' : ''); ?>">Badges</a></li>
                            <li><a href="<?php echo e(url('ui-borders')); ?>"
                                    class="<?php echo e(Request::is('ui-borders') ? 'active' : ''); ?>">Border</a></li>
                            <li><a href="<?php echo e(url('ui-buttons')); ?>"
                                    class="<?php echo e(Request::is('ui-buttons') ? 'active' : ''); ?>">Buttons</a></li>
                            <li><a href="<?php echo e(url('ui-buttons-group')); ?>"
                                    class="<?php echo e(Request::is('ui-buttons-group') ? 'active' : ''); ?>">Button
                                    Group</a></li>
                            <li><a href="<?php echo e(url('ui-breadcrumb')); ?>"
                                    class="<?php echo e(Request::is('ui-breadcrumb') ? 'active' : ''); ?>">Breadcrumb</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-cards')); ?>"
                                    class="<?php echo e(Request::is('ui-cards') ? 'active' : ''); ?>">Card</a></li>
                            <li><a href="<?php echo e(url('ui-carousel')); ?>"
                                    class="<?php echo e(Request::is('ui-carousel') ? 'active' : ''); ?>">Carousel</a></li>
                            <li><a href="<?php echo e(url('ui-colors')); ?>"
                                    class="<?php echo e(Request::is('ui-colors') ? 'active' : ''); ?>">Colors</a></li>
                            <li><a href="<?php echo e(url('ui-dropdowns')); ?>"
                                    class="<?php echo e(Request::is('ui-dropdowns') ? 'active' : ''); ?>">Dropdowns</a></li>
                            <li><a href="<?php echo e(url('ui-grid')); ?>"
                                    class="<?php echo e(Request::is('ui-grid') ? 'active' : ''); ?>">Grid</a></li>
                            <li><a href="<?php echo e(url('ui-images')); ?>"
                                    class="<?php echo e(Request::is('ui-images') ? 'active' : ''); ?>">Images</a></li>
                            <li><a href="<?php echo e(url('ui-lightbox')); ?>"
                                    class="<?php echo e(Request::is('ui-lightbox') ? 'active' : ''); ?>">Lightbox</a></li>
                            <li><a href="<?php echo e(url('ui-media')); ?>"
                                    class="<?php echo e(Request::is('ui-media') ? 'active' : ''); ?>">Media</a></li>
                            <li><a href="<?php echo e(url('ui-modals')); ?>"
                                    class="<?php echo e(Request::is('ui-modals') ? 'active' : ''); ?>">Modals</a></li>
                            <li><a href="<?php echo e(url('ui-offcanvas')); ?>"
                                    class="<?php echo e(Request::is('ui-offcanvas') ? 'active' : ''); ?>">Offcanvas</a></li>
                            <li><a href="<?php echo e(url('ui-pagination')); ?>"
                                    class="<?php echo e(Request::is('ui-pagination') ? 'active' : ''); ?>">Pagination</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-popovers')); ?>"
                                    class="<?php echo e(Request::is('ui-popovers') ? 'active' : ''); ?>">Popovers</a></li>
                            <li><a href="<?php echo e(url('ui-progress')); ?>"
                                    class="<?php echo e(Request::is('ui-progress') ? 'active' : ''); ?>">Progress</a></li>
                            <li><a href="<?php echo e(url('ui-placeholders')); ?>"
                                    class="<?php echo e(Request::is('ui-placeholders') ? 'active' : ''); ?>">Placeholders</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-rangeslider')); ?>"
                                    class="<?php echo e(Request::is('ui-rangeslider') ? 'active' : ''); ?>">Range Slider</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-spinner')); ?>"
                                    class="<?php echo e(Request::is('ui-spinner') ? 'active' : ''); ?>">Spinner</a></li>
                            <li><a href="<?php echo e(url('ui-sweetalerts')); ?>"
                                    class="<?php echo e(Request::is('ui-sweetalerts') ? 'active' : ''); ?>">Sweet Alerts</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-nav-tabs')); ?>"
                                    class="<?php echo e(Request::is('ui-nav-tabs') ? 'active' : ''); ?>">Tabs</a></li>
                            <li><a href="<?php echo e(url('ui-toasts')); ?>"
                                    class="<?php echo e(Request::is('ui-toasts') ? 'active' : ''); ?>">Toasts</a></li>
                            <li><a href="<?php echo e(url('ui-tooltips')); ?>"
                                    class="<?php echo e(Request::is('ui-tooltips') ? 'active' : ''); ?>">Tooltips</a></li>
                            <li><a href="<?php echo e(url('ui-typography')); ?>"
                                    class="<?php echo e(Request::is('ui-typography') ? 'active' : ''); ?>">Typography</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-video')); ?>"
                                    class="<?php echo e(Request::is('ui-video') ? 'active' : ''); ?>">Video</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('ui-ribbon', 'ui-clipboard', 'ui-drag-drop', 'ui-rating', 'ui-text-editor', 'ui-counter', 'ui-scrollbar', 'ui-stickynote', 'ui-timeline') ? 'active subdrop' : ''); ?> "><span>Advanced
                                UI</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('ui-ribbon')); ?>"
                                    class="<?php echo e(Request::is('ui-ribbon') ? 'active' : ''); ?>">Ribbon</a></li>
                            <li><a href="<?php echo e(url('ui-clipboard')); ?>"
                                    class="<?php echo e(Request::is('ui-clipboard') ? 'active' : ''); ?>">Clipboard</a></li>
                            <li><a href="<?php echo e(url('ui-drag-drop')); ?>"
                                    class="<?php echo e(Request::is('ui-drag-drop') ? 'active' : ''); ?>">Drag & Drop</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-rating')); ?>"
                                    class="<?php echo e(Request::is('ui-rating') ? 'active' : ''); ?>">Rating</a></li>
                            <li><a href="<?php echo e(url('ui-text-editor')); ?>"
                                    class="<?php echo e(Request::is('ui-text-editor') ? 'active' : ''); ?>">Text Editor</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-counter')); ?>"
                                    class="<?php echo e(Request::is('ui-counter') ? 'active' : ''); ?>">Counter</a></li>
                            <li><a href="<?php echo e(url('ui-scrollbar')); ?>"
                                    class="<?php echo e(Request::is('ui-scrollbar') ? 'active' : ''); ?>">Scrollbar</a></li>
                            <li><a href="<?php echo e(url('ui-stickynote')); ?>"
                                    class="<?php echo e(Request::is('ui-stickynote') ? 'active' : ''); ?>">Sticky Note</a>
                            </li>
                            <li><a href="<?php echo e(url('ui-timeline')); ?>"
                                    class="<?php echo e(Request::is('ui-timeline') ? 'active' : ''); ?>">Timeline</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('chart-apex', 'chart-c3', 'chart-js', 'chart-morris', 'chart-flot', 'chart-peity') ? 'active subdrop' : ''); ?> "><span>Charts</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('chart-apex')); ?>"
                                    class="<?php echo e(Request::is('chart-apex') ? 'active' : ''); ?>">Apex Charts</a></li>
                            <li><a href="<?php echo e(url('chart-c3')); ?>"
                                    class="<?php echo e(Request::is('chart-c3') ? 'active' : ''); ?>">Chart C3</a></li>
                            <li><a href="<?php echo e(url('chart-js')); ?>"
                                    class="<?php echo e(Request::is('chart-js') ? 'active' : ''); ?>">Chart Js</a></li>
                            <li><a href="<?php echo e(url('chart-morris')); ?>"
                                    class="<?php echo e(Request::is('chart-morris') ? 'active' : ''); ?>">Morris Charts</a>
                            </li>
                            <li><a href="<?php echo e(url('chart-flot')); ?>"
                                    class="<?php echo e(Request::is('chart-flot') ? 'active' : ''); ?>">Flot Charts</a></li>
                            <li><a href="<?php echo e(url('chart-peity')); ?>"
                                    class="<?php echo e(Request::is('chart-peity') ? 'active' : ''); ?>">Peity Charts</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('icon-fontawesome', 'icon-feather', 'icon-ionic', 'icon-material', 'icon-pe7') ? 'active subdrop' : ''); ?> "><span>Primary
                                Icons</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('icon-fontawesome')); ?>"
                                    class="<?php echo e(Request::is('icon-fontawesome') ? 'active' : ''); ?>">Fontawesome
                                    Icons</a></li>
                            <li><a href="<?php echo e(url('icon-feather')); ?>"
                                    class="<?php echo e(Request::is('icon-feather') ? 'active' : ''); ?>">Feather Icons</a>
                            </li>
                            <li><a href="<?php echo e(url('icon-ionic')); ?>"
                                    class="<?php echo e(Request::is('icon-ionic') ? 'active' : ''); ?>">Ionic Icons</a></li>
                            <li><a href="<?php echo e(url('icon-material')); ?>"
                                    class="<?php echo e(Request::is('icon-material') ? 'active' : ''); ?>">Material Icons</a>
                            </li>
                            <li><a href="<?php echo e(url('icon-pe7')); ?>"
                                    class="<?php echo e(Request::is('icon-pe7') ? 'active' : ''); ?>">Pe7 Icons</a></li>

                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('icon-simpleline', 'icon-themify', 'icon-weather', 'icon-typicon', 'icon-flag') ? 'active subdrop' : ''); ?> "><span>Secondary
                                Icons</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('icon-simpleline')); ?>"
                                    class="<?php echo e(Request::is('icon-simpleline') ? 'active' : ''); ?>">Simpleline
                                    Icons</a></li>
                            <li><a href="<?php echo e(url('icon-themify')); ?>"
                                    class="<?php echo e(Request::is('icon-themify') ? 'active' : ''); ?>">Themify Icons</a>
                            </li>
                            <li><a href="<?php echo e(url('icon-weather')); ?>"
                                    class="<?php echo e(Request::is('icon-weather') ? 'active' : ''); ?>">Weather Icons</a>
                            </li>
                            <li><a href="<?php echo e(url('icon-typicon')); ?>"
                                    class="<?php echo e(Request::is('icon-typicon') ? 'active' : ''); ?>">Typicon Icons</a>
                            </li>
                            <li><a href="<?php echo e(url('icon-flag')); ?>"
                                    class="<?php echo e(Request::is('icon-flag') ? 'active' : ''); ?>">Flag Icons</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('form-wizard', 'form-select2', 'form-validation', 'form-floating-labels', 'form-vertical', 'form-horizontal', 'form-basic-inputs', 'form-checkbox-radios', 'form-input-groups', 'form-grid-gutters', 'form-select', 'form-mask', 'form-fileupload') ? 'active subdrop' : ''); ?> "><span>
                                Forms</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);"><span>Form Elements</span><span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('form-basic-inputs')); ?>"
                                            class="<?php echo e(Request::is('form-basic-inputs') ? 'active' : ''); ?>">Basic
                                            Inputs</a></li>
                                    <li><a href="<?php echo e(url('form-checkbox-radios')); ?>"
                                            class="<?php echo e(Request::is('form-checkbox-radios') ? 'active' : ''); ?>">Checkbox
                                            & Radios</a></li>
                                    <li><a href="<?php echo e(url('form-input-groups')); ?>"
                                            class="<?php echo e(Request::is('form-input-groups') ? 'active' : ''); ?>">Input
                                            Groups</a></li>
                                    <li><a href="<?php echo e(url('form-grid-gutters')); ?>"
                                            class="<?php echo e(Request::is('form-grid-gutters') ? 'active' : ''); ?>">Grid &
                                            Gutters</a></li>
                                    <li><a href="<?php echo e(url('form-select')); ?>"
                                            class="<?php echo e(Request::is('form-select') ? 'active' : ''); ?>">Form
                                            Select</a></li>
                                    <li><a href="<?php echo e(url('form-mask')); ?>"
                                            class="<?php echo e(Request::is('form-mask') ? 'active' : ''); ?>">Input
                                            Masks</a></li>
                                    <li><a href="<?php echo e(url('form-fileupload')); ?>"
                                            class="<?php echo e(Request::is('form-fileupload') ? 'active' : ''); ?>">File
                                            Uploads</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two">
                                <a href="javascript:void(0);"
                                    class="<?php echo e(Request::is('form-horizontal', 'form-vertical', 'form-floating-labels') ? 'active subdrop' : ''); ?>"><span>
                                        Layouts</span><span class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('form-horizontal')); ?>"
                                            class="<?php echo e(Request::is('form-horizontal') ? 'active' : ''); ?>">Horizontal
                                            Form</a></li>
                                    <li><a href="<?php echo e(url('form-vertical')); ?>"
                                            class="<?php echo e(Request::is('form-vertical') ? 'active' : ''); ?>">Vertical
                                            Form</a></li>
                                    <li><a href="<?php echo e(url('form-floating-labels')); ?>"
                                            class="<?php echo e(Request::is('form-floating-labels') ? 'active' : ''); ?>">Floating
                                            Labels</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(url('form-validation')); ?>"
                                    class="<?php echo e(Request::is('form-validation') ? 'active' : ''); ?>">Form
                                    Validation</a></li>
                            <li><a href="<?php echo e(url('form-select2')); ?>"
                                    class="<?php echo e(Request::is('form-select2') ? 'active' : ''); ?>">Select2</a></li>
                            <li><a href="<?php echo e(url('form-wizard')); ?>"
                                    class="<?php echo e(Request::is('form-wizard') ? 'active' : ''); ?>">Form Wizard</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('tables-basic', 'data-tables') ? 'active subdrop' : ''); ?> "><span>Tables</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('tables-basic')); ?>"
                                    class="<?php echo e(Request::is('tables-basic') ? 'active' : ''); ?>">Basic Tables </a>
                            </li>
                            <li><a href="<?php echo e(url('data-tables')); ?>"
                                    class="<?php echo e(Request::is('data-tables') ? 'active' : ''); ?>">Data Table </a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('error-404', 'error-500', 'blank-page', 'coming-soon', 'under-maintenance', 'countries', 'states', 'employees-grid', 'employees-list','add-employee','edit-employee','department-grid', 'department-list','designation', 'shift', 'attendance-employee', 'attendance-admin', 'leaves-admin', 'leaves-employee', 'leave-types', 'holidays', 'payroll-list', 'payslip') ? 'active subdrop' : ''); ?>"><i
                        data-feather="user"></i><span>Profile</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li><a href="<?php echo e(url('profile')); ?>"><span>Profile</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Authentication</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Login<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('signin')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('signin-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('signin-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Register<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('register')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('register-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('register-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Forgot Password<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('forgot-password')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('forgot-password-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('forgot-password-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Reset Password<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('reset-password')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('reset-password-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('reset-password-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Email Verification<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('email-verification')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('email-verification-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('email-verification-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">2 Step Verification<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="<?php echo e(url('two-step-verification')); ?>">Cover</a></li>
                                    <li><a href="<?php echo e(url('two-step-verification-2')); ?>">Illustration</a></li>
                                    <li><a href="<?php echo e(url('two-step-verification-3')); ?>">Basic</a></li>
                                </ul>
                            </li>
                            <li><a href="<?php echo e(url('lock-screen')); ?>">Lock Screen</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('error-404', 'error-500', 'blank-page', 'coming-soon', 'under-maintenance') ? 'active' : ''); ?> "><span>Pages</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('error-404')); ?>"
                                    class="<?php echo e(Request::is('error-404') ? 'active' : ''); ?>">404 Error </a></li>
                            <li><a href="<?php echo e(url('error-500')); ?>"
                                    class="<?php echo e(Request::is('error-500') ? 'active' : ''); ?>">500 Error </a></li>
                            <li><a href="<?php echo e(url('blank-page')); ?>"
                                    class="<?php echo e(Request::is('blank-page') ? 'active' : ''); ?>"><span>Blank Page</span>
                                </a></li>
                            <li><a href="<?php echo e(url('coming-soon')); ?>"
                                    class="<?php echo e(Request::is('coming-soon') ? 'active' : ''); ?>"><span>Coming Soon</span>
                                </a></li>
                            <li><a href="<?php echo e(url('under-maintenance')); ?>"
                                    class="<?php echo e(Request::is('under-maintenance') ? 'active' : ''); ?>"><span>Under
                                        Maintenance</span> </a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('countries', 'states') ? 'active subdrop' : ''); ?> "><span>Places</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('countries')); ?>"
                                    class="<?php echo e(Request::is('countries') ? 'active' : ''); ?>">Countries</a></li>
                            <li><a href="<?php echo e(url('states')); ?>"
                                    class="<?php echo e(Request::is('states') ? 'active' : ''); ?>">States</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('employees-grid', 'employees-list','edit-employee','add-employee','department-grid','department-list', 'designation', 'shift') ? 'active subdrop' : ''); ?> "><span>Employees</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a class="<?php echo e(Request::is('employees-grid','employees-list','edit-employee','add-employee') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('employees-grid')); ?>"><span>Employees</span></a></li>
                            <li><a class="<?php echo e(Request::is('department-grid','department-list') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('department-grid')); ?>"><span>Departments</span></a></li>
                            <li><a class="<?php echo e(Request::is('designation') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('designation')); ?>"><span>Designation</span></a></li>
                            <li><a class="<?php echo e(Request::is('shift') ? 'active' : ''); ?>"
                                    href="<?php echo e(url('shift')); ?>"><span>Shifts</span></a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('attendance-employee', 'attendance-admin') ? 'active subdrop' : ''); ?> "><span>Attendence</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('attendance-employee')); ?>"
                                    class="<?php echo e(Request::is('attendance-employee') ? 'active' : ''); ?>">Employee</a>
                            </li>
                            <li><a href="<?php echo e(url('attendance-admin')); ?>"
                                    class="<?php echo e(Request::is('attendance-admin') ? 'active' : ''); ?>">Admin</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('leaves-admin', 'leaves-employee', 'leave-types', 'holidays') ? 'active subdrop' : ''); ?> "><span>Leaves
                                &amp; Holidays</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('leaves-admin')); ?>"
                                    class="<?php echo e(Request::is('leaves-admin') ? 'active' : ''); ?>">Admin Leaves</a>
                            </li>
                            <li><a href="<?php echo e(url('leaves-employee')); ?>"
                                    class="<?php echo e(Request::is('leaves-employee') ? 'active' : ''); ?>">Employee
                                    Leaves</a></li>
                            <li><a href="<?php echo e(url('leave-types')); ?>"
                                    class="<?php echo e(Request::is('leave-types') ? 'active' : ''); ?>">Leave Types</a></li>
                            <li><a href="<?php echo e(url('holidays')); ?>"
                                    class="<?php echo e(Request::is('holidays') ? 'active' : ''); ?>"><span>Holidays</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="<?php echo e(url('payroll-list')); ?>"
                            class="<?php echo e(Request::is('payroll-list', 'payslip') ? 'active subdrop' : ''); ?> "><span>Payroll</span><span
                                class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('payroll-list')); ?>"
                                    class="<?php echo e(Request::is('payroll-list') ? 'active' : ''); ?>">Employee Salary</a>
                            </li>
                            <li><a href="<?php echo e(url('payslip')); ?>"
                                    class="<?php echo e(Request::is('payslip') ? 'active' : ''); ?>">Payslip</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('sales-report', 'purchase-report', 'inventory-report', 'invoice-report', 'supplier-report', 'customer-report', 'expense-report', 'income-report', 'tax-reports', 'profit-and-loss') ? 'active subdrop' : ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/printer.svg')); ?>" alt="img"><span>Reports</span> <span
                        class="menu-arrow"></span></a>
                <ul>
                    <li><a class="<?php echo e(Request::is('sales-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('sales-report')); ?>"><span>Sales
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('purchase-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('purchase-report')); ?>"><span>Purchase
                                report</span></a></li>
                    <li><a class="<?php echo e(Request::is('inventory-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('inventory-report')); ?>"><span>Inventory
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('invoice-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('invoice-report')); ?>"><span>Invoice
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('supplier-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('supplier-report')); ?>"><span>Supplier
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('customer-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('customer-report')); ?>"><span>Customer
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('expense-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('expense-report')); ?>"><span>Expense
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('income-report') ? 'active' : ''); ?>"
                            href="<?php echo e(url('income-report')); ?>"><span>Income
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('tax-reports') ? 'active' : ''); ?>"
                            href="<?php echo e(url('tax-reports')); ?>"><span>Tax
                                Report</span></a></li>
                    <li><a class="<?php echo e(Request::is('profit-and-loss') ? 'active' : ''); ?>"
                            href="<?php echo e(url('profit-and-loss')); ?>"><span>Profit &
                                Loss</span></a></li>
                </ul>
            </li>
            <li class="submenu">
                <a href="javascript:void(0);"
                    class="<?php echo e(Request::is('general-settings', 'security-settings', 'notification', 'connected-apps', 'system-settings', 'company-settings', 'localization-settings', 'prefixes', 'preference', 'appearance', 'social-authentication', 'language-settings','language-settings-web', 'invoice-settings', 'printer-settings', 'pos-settings', 'custom-fields', 'email-settings', 'sms-gateway', 'otp-settings', 'gdpr-settings', 'payment-gateway-settings', 'bank-settings-grid', 'bank-settings-list','tax-rates', 'currency-settings', 'storage-settings', 'ban-ip-address') ? 'active' : ''); ?>"><img
                        src="<?php echo e(URL::asset('/build/img/icons/settings.svg')); ?>" alt="img"><span>
                        Settings</span> <span class="menu-arrow"></span></a>
                <ul>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('general-settings', 'security-settings', 'notification', 'connected-apps') ? 'active subdrop' : ''); ?>"><span>General
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('general-settings')); ?>"
                                    class="<?php echo e(Request::is('general-settings') ? 'active' : ''); ?>">Profile</a>
                            </li>
                            <li><a href="<?php echo e(url('security-settings')); ?>"
                                    class="<?php echo e(Request::is('security-settings') ? 'active' : ''); ?>">Security</a>
                            </li>
                            <li><a href="<?php echo e(url('notification')); ?>"
                                    class="<?php echo e(Request::is('notification') ? 'active' : ''); ?>">Notifications</a>
                            </li>
                            <li><a href="<?php echo e(url('connected-apps')); ?>"
                                    class="<?php echo e(Request::is('connected-apps') ? 'active' : ''); ?>">Connected
                                    Apps</a></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('system-settings', 'company-settings', 'localization-settings', 'prefixes', 'preference', 'appearance', 'social-authentication', 'language-settings','language-settings-web') ? 'active subdrop' : ''); ?>"><span>Website
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('system-settings')); ?>"
                                    class="<?php echo e(Request::is('system-settings') ? 'active' : ''); ?>">System
                                    Settings</a></li>
                            <li><a href="<?php echo e(url('company-settings')); ?>"
                                    class="<?php echo e(Request::is('company-settings') ? 'active' : ''); ?>">Company
                                    Settings </a></li>
                            <li><a href="<?php echo e(url('localization-settings')); ?>"
                                    class="<?php echo e(Request::is('localization-settings') ? 'active' : ''); ?>">Localization</a>
                            </li>
                            <li><a href="<?php echo e(url('prefixes')); ?>"
                                    class="<?php echo e(Request::is('prefixes') ? 'active' : ''); ?>">Prefixes</a></li>
                            <li><a href="<?php echo e(url('preference')); ?>"
                                    class="<?php echo e(Request::is('preference') ? 'active' : ''); ?>">Preference</a></li>
                            <li><a href="<?php echo e(url('appearance')); ?>"
                                    class="<?php echo e(Request::is('appearance') ? 'active' : ''); ?>">Appearance</a></li>
                            <li><a href="<?php echo e(url('social-authentication')); ?>"
                                    class="<?php echo e(Request::is('social-authentication') ? 'active' : ''); ?>">Social
                                    Authentication</a></li>
                            <li><a href="<?php echo e(url('language-settings')); ?>"
                                    class="<?php echo e(Request::is('language-settings','language-settings-web') ? 'active' : ''); ?>">Language</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('invoice-settings', 'printer-settings', 'pos-settings', 'custom-fields') ? 'active subdrop' : ''); ?>"><span>App
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('invoice-settings')); ?>"
                                    class="<?php echo e(Request::is('invoice-settings') ? 'active' : ''); ?>">Invoice</a>
                            </li>
                            <li><a href="<?php echo e(url('printer-settings')); ?>"
                                    class="<?php echo e(Request::is('printer-settings') ? 'active' : ''); ?>">Printer</a>
                            </li>
                            <li><a href="<?php echo e(url('pos-settings')); ?>"
                                    class="<?php echo e(Request::is('pos-settings') ? 'active' : ''); ?>">POS</a></li>
                            <li><a href="<?php echo e(url('custom-fields')); ?>"
                                    class="<?php echo e(Request::is('custom-fields') ? 'active' : ''); ?>">Custom Fields</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('email-settings', 'sms-gateway', 'otp-settings', 'gdpr-settings') ? 'active subdrop' : ''); ?>"><span>System
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('email-settings')); ?>"
                                    class="<?php echo e(Request::is('email-settings') ? 'active' : ''); ?>">Email</a></li>
                            <li><a href="<?php echo e(url('sms-gateway')); ?>"
                                    class="<?php echo e(Request::is('sms-gateway') ? 'active' : ''); ?>">SMS Gateways</a>
                            </li>
                            <li><a href="<?php echo e(url('otp-settings')); ?>"
                                    class="<?php echo e(Request::is('otp-settings') ? 'active' : ''); ?>">OTP</a></li>
                            <li><a href="<?php echo e(url('gdpr-settings')); ?>"
                                    class="<?php echo e(Request::is('gdpr-settings') ? 'active' : ''); ?>">GDPR Cookies</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('payment-gateway-settings', 'bank-settings-grid', 'bank-settings-list','tax-rates', 'currency-settings') ? 'active subdrop' : ''); ?>"><span>Financial
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('payment-gateway-settings')); ?>"
                                    class="<?php echo e(Request::is('payment-gateway-settings') ? 'active' : ''); ?>">Payment
                                    Gateway</a></li>
                            <li><a href="<?php echo e(url('bank-settings-grid')); ?>"
                                    class="<?php echo e(Request::is('bank-settings-grid','bank-settings-list') ? 'active' : ''); ?>">Bank
                                    Accounts</a></li>
                            <li><a href="<?php echo e(url('tax-rates')); ?>"
                                    class="<?php echo e(Request::is('tax-rates') ? 'active' : ''); ?>">Tax Rates</a></li>
                            <li><a href="<?php echo e(url('currency-settings')); ?>"
                                    class="<?php echo e(Request::is('currency-settings') ? 'active' : ''); ?>">Currencies</a>
                            </li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="javascript:void(0);"
                            class="<?php echo e(Request::is('storage-settings', 'ban-ip-address') ? 'active subdrop' : ''); ?> "><span>Other
                                Settings</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="<?php echo e(url('storage-settings')); ?>"
                                    class="<?php echo e(Request::is('storage-settings') ? 'active' : ''); ?>">Storage</a>
                            </li>
                            <li><a href="<?php echo e(url('ban-ip-address')); ?>"
                                    class="<?php echo e(Request::is('ban-ip-address') ? 'active' : ''); ?>">Ban IP
                                    Address</a></li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0);"><span>Documentation</span></a></li>
                    <li><a href="javascript:void(0);"><span>Changelog v2.0.3</span></a></li>
                    <li class="submenu">
                        <a href="javascript:void(0);"><span>Multi Level</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="javascript:void(0);">Level 1.1</a></li>
                            <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span
                                        class="menu-arrow inside-submenu"></span></a>
                                <ul>
                                    <li><a href="javascript:void(0);">Level 2.1</a></li>
                                    <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Level
                                            2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
                                        <ul>
                                            <li><a href="javascript:void(0);">Level 3.1</a></li>
                                            <li><a href="javascript:void(0);">Level 3.2</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /Sidebar -->
<?php /**PATH C:\laragon\www\laundry\resources\views/layout/partials/horizontal-sidebar.blade.php ENDPATH**/ ?>