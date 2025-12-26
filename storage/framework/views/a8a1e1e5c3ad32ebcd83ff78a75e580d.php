<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Main</h6>
                    <ul>
                        <li class="submenu">
                            <li class="<?php echo e(Request::is('index', '/', 'sales-dashboard') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('index')); ?>"><i data-feather="grid"></i><span>Dashboard</span></a>
                        </li>
                        </li>
                        
                        <!-- Business Owner Only: Subscription & Notifications -->
                        <?php if(auth()->user()->hasRole('business_owner')): ?>
                        <li class="<?php echo e(Request::is('business-owner/subscription*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('business-owner.subscription')); ?>">
                                <i data-feather="credit-card"></i><span>My Subscription</span>
                            </a>
                        </li>
                        <li class="<?php echo e(Request::is('business-owner/notifications*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('business-owner.notifications')); ?>">
                                <i data-feather="bell"></i><span>Notifications</span>
                                <?php
                                    $unreadCount = auth()->user()->unreadNotifications()->count();
                                ?>
                                <?php if($unreadCount > 0): ?>
                                <span class="badge bg-danger ms-2"><?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('chat', 'file-manager', 'file-archived','file-document','file-favourites','file-manager-seleted','file-recent','file-shared','notes', 'todo', 'email', 'calendar', 'call-history', 'audio-call', 'video-call','file-manager-deleted') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="smartphone"></i><span>Application</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('chat')); ?>"
                                        class="<?php echo e(Request::is('chat') ? 'active' : ''); ?>">Chat</a></li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);"
                                        class="<?php echo e(Request::is('video-call', 'audio-call', 'call-history') ? 'active subdrop' : ''); ?>">Call<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a class="<?php echo e(Request::is('video-call') ? 'active' : ''); ?>"
                                                href="<?php echo e(url('video-call')); ?>">Video Call</a></li>
                                        <li><a class="<?php echo e(Request::is('audio-call') ? 'active' : ''); ?>"
                                                href="<?php echo e(url('audio-call')); ?>">Audio Call</a></li>
                                        <li><a class="<?php echo e(Request::is('call-history') ? 'active' : ''); ?>"
                                                href="<?php echo e(url('call-history')); ?>">Call History</a></li>
                                    </ul>
                                </li>
                                <li><a class="<?php echo e(Request::is('calendar') ? 'active' : ''); ?>"
                                        href="<?php echo e(url('calendar')); ?>">Calendar</a></li>
                                <li><a class="<?php echo e(Request::is('email') ? 'active' : ''); ?>"
                                        href="<?php echo e(url('email')); ?>">Email</a></li>
                                <li><a class="<?php echo e(Request::is('todo') ? 'active' : ''); ?>" href="<?php echo e(url('todo')); ?>">To
                                        Do</a></li>
                                <li><a class="<?php echo e(Request::is('notes') ? 'active' : ''); ?>"
                                        href="<?php echo e(url('notes')); ?>">Notes</a></li>
                                <li><a class="<?php echo e(Request::is('file-manager', 'file-archived','file-document','file-favourites','file-manager-seleted','file-recent','file-shared','file-manager-deleted') ? 'active' : ''); ?>"
                                        href="<?php echo e(url('file-manager')); ?>">File Manager</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">RAPY SaaS Platform</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('superadmin/dashboard') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.dashboard')); ?>"><i data-feather="activity"></i><span>SaaS Dashboard</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('superadmin/businesses*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.businesses.index')); ?>"><i data-feather="briefcase"></i><span>Business Management</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('superadmin/subscriptions*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.subscriptions')); ?>"><i data-feather="repeat"></i><span>Subscriptions</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('superadmin/plans*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.plans')); ?>"><i data-feather="package"></i><span>Plan Management</span></a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('superadmin/payments*', 'superadmin/invoices*', 'superadmin/grace-periods*') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="credit-card"></i><span>SaaS Billing</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(route('superadmin.invoices')); ?>"
                                        class="<?php echo e(Request::is('superadmin/invoices*') ? 'active' : ''); ?>">Invoices</a></li>
                                <li><a href="<?php echo e(route('superadmin.payments')); ?>"
                                        class="<?php echo e(Request::is('superadmin/payments') ? 'active' : ''); ?>">Payment History</a></li>
                                <li><a href="<?php echo e(route('superadmin.grace-periods')); ?>"
                                        class="<?php echo e(Request::is('superadmin/grace-periods') ? 'active' : ''); ?>">Grace Periods</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('superadmin/users*', 'superadmin/security-settings*') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="user-check"></i><span>User & Identity</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(route('superadmin.users.index')); ?>"
                                        class="<?php echo e(Request::is('superadmin/users') ? 'active' : ''); ?>">All Users</a></li>
                                <li><a href="<?php echo e(route('superadmin.security-settings')); ?>"
                                        class="<?php echo e(Request::is('superadmin/security-settings') ? 'active' : ''); ?>">Security Settings</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('superadmin/roles-permissions*', 'superadmin/store-role-mapping*') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="shield"></i><span>Roles & Permissions</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(route('superadmin.roles-permissions')); ?>"
                                        class="<?php echo e(Request::is('superadmin/roles-permissions') ? 'active' : ''); ?>">SaaS Roles</a></li>
                                <li><a href="<?php echo e(route('superadmin.store-role-mapping')); ?>"
                                        class="<?php echo e(Request::is('superadmin/store-role-mapping') ? 'active' : ''); ?>">Store Role Mapping</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e(Request::is('superadmin/store-containers*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.store-containers.index')); ?>"><i data-feather="box"></i><span>Store Containers</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('superadmin/usage-limits*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('superadmin.usage-limits.index')); ?>"><i data-feather="bar-chart-2"></i><span>Usage & Limits</span></a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('superadmin/settings*') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="settings"></i><span>SaaS Configuration</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(route('superadmin.settings.branding')); ?>"
                                        class="<?php echo e(Request::is('superadmin/settings/branding') ? 'active' : ''); ?>">Global Branding</a></li>
                                <li><a href="<?php echo e(route('superadmin.settings.currency')); ?>"
                                        class="<?php echo e(Request::is('superadmin/settings/currency') ? 'active' : ''); ?>">Default Currency</a></li>
                                <li><a href="<?php echo e(route('superadmin.settings.tax')); ?>"
                                        class="<?php echo e(Request::is('superadmin/settings/tax') ? 'active' : ''); ?>">Default Tax</a></li>
                                <li><a href="<?php echo e(route('superadmin.settings.timezone')); ?>"
                                        class="<?php echo e(Request::is('superadmin/settings/timezone') ? 'active' : ''); ?>">Timezone Settings</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('superadmin/logs*', 'superadmin/support*', 'superadmin/impersonation-history*') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="life-buoy"></i><span>Support & Logs</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(route('superadmin.logs.system')); ?>"
                                        class="<?php echo e(Request::is('superadmin/logs/system') ? 'active' : ''); ?>">System Logs</a></li>
                                <li><a href="<?php echo e(route('superadmin.logs.error')); ?>"
                                        class="<?php echo e(Request::is('superadmin/logs/error') ? 'active' : ''); ?>">Error Logs</a></li>
                                <li><a href="<?php echo e(route('superadmin.support.tickets')); ?>"
                                        class="<?php echo e(Request::is('superadmin/support/tickets') ? 'active' : ''); ?>">Support Tickets</a></li>
                                <li><a href="<?php echo e(route('superadmin.impersonation-history')); ?>"
                                        class="<?php echo e(Request::is('superadmin/impersonation-history') ? 'active' : ''); ?>">Impersonation History</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                 <li class="submenu-open">
                    <h6 class="submenu-hdr">Laundry Operations</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('laundry') || Request::is('laundry/') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('laundry.dashboard')); ?>"><i data-feather="home"></i><span>Dashboard</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('laundry/orders*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('laundry.orders')); ?>"><i data-feather="package"></i><span>Orders</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('laundry/qc*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('laundry.qc.index')); ?>"><i data-feather="check-square"></i><span>Quality Control</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('laundry/machines*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('laundry.machines')); ?>"><i data-feather="settings"></i><span>Machines</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Services</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('product-list','product-details') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('product-list')); ?>"><i data-feather="box"></i><span>Service</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('add-product','edit-product') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('add-product')); ?>"><i data-feather="plus-square"></i><span>Create
                                    Service</span></a></li>
                        <li class="<?php echo e(Request::is('category-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('category-list')); ?>"><i
                                    data-feather="codepen"></i><span>Category</span></a></li>
                        <li class="<?php echo e(Request::is('units') ? 'active' : ''); ?>"><a href="<?php echo e(url('units')); ?>"><i
                                    data-feather="layers"></i><span>Units</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && auth()->user()->isBusinessOwner()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Stores</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('store-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('store-list')); ?>"><i data-feather="home"></i><span>Manage Stores</span></a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <!-- <li class="submenu-open">
                    <h6 class="submenu-hdr">Stock</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('manage-stocks') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('manage-stocks')); ?>"><i data-feather="package"></i><span>Manage
                                    Stock</span></a></li>
                        <li class="<?php echo e(Request::is('stock-adjustment') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('stock-adjustment')); ?>"><i data-feather="clipboard"></i><span>Stock
                                    Adjustment</span></a></li>
                        <li class="<?php echo e(Request::is('stock-transfer') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('stock-transfer')); ?>"><i data-feather="truck"></i><span>Stock
                                    Transfer</span></a></li>
                    </ul>
                </li> -->
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Sales</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('sales-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('sales-list')); ?>"><i
                                    data-feather="shopping-cart"></i><span>Sales</span></a></li>
                        <li class="<?php echo e(Request::is('quotation-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('quotation-list')); ?>"><i
                                    data-feather="save"></i><span>Quotation</span></a>
                        </li>
                        <!-- <li class="<?php echo e(Request::is('sales-returns') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('sales-returns')); ?>"><i data-feather="copy"></i><span>Sales
                                    Return</span></a></li> -->
                        <li class="<?php echo e(Request::is('invoice-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('invoice-report')); ?>"><i
                                    data-feather="file-text"></i><span>Invoices</span></a></li>
                        
                        <!-- <li class="<?php echo e(Request::is('pos') ? 'active' : ''); ?>"><a href="<?php echo e(url('pos')); ?>"><i
                                    data-feather="hard-drive"></i><span>POS</span></a></li> -->
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Promo</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('coupons') ? 'active' : ''); ?>"><a href="<?php echo e(url('coupons')); ?>"><i
                                    data-feather="shopping-cart"></i><span>Coupons</span></a>
                        </li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Purchases</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('purchase-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('purchase-list')); ?>"><i
                                    data-feather="shopping-bag"></i><span>Purchases</span></a></li>
                        <li class="<?php echo e(Request::is('purchase-order-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('purchase-order-report')); ?>"><i
                                    data-feather="file-minus"></i><span>Purchase Order</span></a></li>
                        <li class="<?php echo e(Request::is('purchase-returns') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('purchase-returns')); ?>"><i data-feather="refresh-cw"></i><span>Purchase
                                    Return</span></a></li>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Finance & Accounts</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('expense-list', 'expense-category') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="file-text"></i><span>Expenses</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('expense-list')); ?>"
                                        class="<?php echo e(Request::is('expense-list') ? 'active' : ''); ?>">Expenses</a></li>
                                <li><a href="<?php echo e(url('expense-category')); ?>"
                                        class="<?php echo e(Request::is('expense-category') ? 'active' : ''); ?>">Expense
                                        Category</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Peoples</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('customers') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('customers')); ?>"><i data-feather="user"></i><span>Customers</span></a>
                        </li>
                        <!-- <li class="<?php echo e(Request::is('suppliers') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('suppliers')); ?>"><i data-feather="users"></i><span>Suppliers</span></a>
                        </li> -->
                        <!-- <li class="<?php echo e(Request::is('warehouse') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('warehouse')); ?>"><i
                                    data-feather="archive"></i><span>Warehouses</span></a>
                        </li> -->
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Website</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('storefront-preview', 'storefront-cms', 'storefront-theme') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="globe"></i><span>Storefront</span><span class="menu-arrow"></span></a>
                            <ul>
                                <?php
                                    $selectedStore = null;
                                    if (session('selected_store_id')) {
                                        $selectedStore = \App\Models\Store::find(session('selected_store_id'));
                                    } elseif (auth()->check() && auth()->user()) {
                                        $selectedStore = auth()->user()->getAccessibleStores()->first() ?? null;
                                    }
                                ?>
                                <?php if($selectedStore): ?>
                                <li><a href="<?php echo e(route('storefront.index', $selectedStore->slug)); ?>" target="_blank"
                                        class="<?php echo e(Request::is('storefront-preview') ? 'active' : ''); ?>">
                                        <i data-feather="eye" style="width: 14px; height: 14px; margin-right: 5px;"></i>Preview Website</a>
                                </li>
                                <li><a href="<?php echo e(route('storefront-cms', ['store_id' => $selectedStore->id])); ?>"
                                        class="<?php echo e(Request::is('storefront-cms', 'storefront-theme') ? 'active' : ''); ?>">Theme & CMS</a>
                                </li>
                                <?php else: ?>
                                <li><a href="<?php echo e(route('storefront-cms')); ?>"
                                        class="<?php echo e(Request::is('storefront-cms', 'storefront-theme') ? 'active' : ''); ?>">Theme & CMS</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">HRM</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('employees-grid','employees-list','edit-employee','add-employee') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('employees-grid')); ?>"><i
                                    data-feather="user"></i><span>Employees</span></a></li>
                        <li class="<?php echo e(Request::is('department-grid','department-list') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('department-grid')); ?>"><i
                                    data-feather="users"></i><span>Departments</span></a></li>
                        <li class="<?php echo e(Request::is('designation') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('designation')); ?>"><i
                                    data-feather="git-merge"></i><span>Designation</span></a></li>
                        <li class="<?php echo e(Request::is('shift') ? 'active' : ''); ?>"><a href="<?php echo e(url('shift')); ?>"><i
                                    data-feather="shuffle"></i><span>Shifts</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('attendance-employee', 'attendance-admin') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="book-open"></i><span>Attendence</span><span
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
                                class="<?php echo e(Request::is('leaves-admin', 'leaves-employee', 'leave-types') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="calendar"></i><span>Leaves</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('leaves-admin')); ?>"
                                        class="<?php echo e(Request::is('leaves-admin') ? 'active' : ''); ?>">Admin Leaves</a>
                                </li>
                                <li><a href="<?php echo e(url('leaves-employee')); ?>"
                                        class="<?php echo e(Request::is('leaves-employee') ? 'active' : ''); ?>">Employee
                                        Leaves</a></li>
                                <li><a href="<?php echo e(url('leave-types')); ?>"
                                        class="<?php echo e(Request::is('leave-types') ? 'active' : ''); ?>">Leave Types</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e(Request::is('holidays') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('holidays')); ?>"><i
                                    data-feather="credit-card"></i><span>Holidays</span></a>
                        </li>
                        <li class="submenu">
                            <a href="<?php echo e(url('payroll-list')); ?>"
                                class="<?php echo e(Request::is('payroll-list', 'payslip') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="dollar-sign"></i><span>Payroll</span><span
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
               
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Reports</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('sales-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('sales-report')); ?>"><i data-feather="bar-chart-2"></i><span>Sales
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('purchase-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('purchase-report')); ?>"><i data-feather="pie-chart"></i><span>Purchase
                                    report</span></a></li>
                        <li class="<?php echo e(Request::is('inventory-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('inventory-report')); ?>"><i data-feather="inbox"></i><span>Inventory
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('invoice-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('invoice-report')); ?>"><i data-feather="file"></i><span>Invoice
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('supplier-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('supplier-report')); ?>"><i data-feather="user-check"></i><span>Supplier
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('customer-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('customer-report')); ?>"><i data-feather="user"></i><span>Customer
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('expense-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('expense-report')); ?>"><i data-feather="file"></i><span>Expense
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('income-report') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('income-report')); ?>"><i data-feather="bar-chart"></i><span>Income
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('tax-reports') ? 'active' : ''); ?>"><a
                                href="<?php echo e(url('tax-reports')); ?>"><i data-feather="database"></i><span>Tax
                                    Report</span></a></li>
                        <li class="<?php echo e(Request::is('profit-loss') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('profit-loss')); ?>"><i data-feather="pie-chart"></i><span>Profit &
                                    Loss</span></a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isBusinessOwner())): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">User Management</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('users') ? 'active' : ''); ?>"><a href="<?php echo e(route('users.index')); ?>"><i
                                    data-feather="user-check"></i><span>Users</span></a>
                        </li>
                        <?php if(auth()->user()->isSuperAdmin()): ?>
                        <li class="<?php echo e(Request::is('roles-permissions','permissions') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('roles-permissions')); ?>"><i data-feather="shield"></i><span>Roles &
                                    Permissions</span></a></li>
                        <li class="<?php echo e(Request::is('delete-account') ? 'active' : ''); ?>"><a
                                href="<?php echo e(route('delete-account')); ?>"><i data-feather="lock"></i><span>Delete Account
                                    Request</span></a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Security & Audit</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('audit-trail*') ? 'active' : ''); ?>"><a href="<?php echo e(route('audit-trail.index')); ?>"><i
                                    data-feather="activity"></i><span>Audit Trail</span></a>
                        </li>
                        <li class="<?php echo e(Request::is('audit-trail/user*') ? 'active' : ''); ?>"><a href="<?php echo e(route('audit-trail.user')); ?>"><i
                                    data-feather="user"></i><span>My Activity</span></a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Pages</h6>
                    <ul>
                        <li class="<?php echo e(Request::is('profile') ? 'active' : ''); ?>"><a href="<?php echo e(url('profile')); ?>"><i
                                    data-feather="user"></i><span>Profile</span></a></li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i
                                    data-feather="shield"></i><span>Authentication</span><span
                                    class="menu-arrow"></span></a>
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
                            <a href="javascript:void(0);"><i data-feather="file-minus"></i><span>Error
                                    Pages</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('error-404')); ?>">404 Error </a></li>
                                <li><a href="<?php echo e(url('error-500')); ?>">500 Error </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('countries', 'states') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="map"></i><span>Places</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('countries')); ?>"
                                        class="<?php echo e(Request::is('countries') ? 'active' : ''); ?>">Countries</a></li>
                                <li><a href="<?php echo e(url('states')); ?>"
                                        class="<?php echo e(Request::is('states') ? 'active' : ''); ?>">States</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e(Request::is('blank-page') ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('blank-page')); ?>"><i data-feather="file"></i><span>Blank Page</span>
                            </a>
                        </li>
                        <li class="<?php echo e(Request::is('coming-soon') ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('coming-soon')); ?>"><i data-feather="send"></i><span>Coming Soon</span>
                            </a>
                        </li>
                        <li class="<?php echo e(Request::is('under-maintenance') ? 'active' : ''); ?>">
                            <a href="<?php echo e(url('under-maintenance')); ?>"><i
                                    data-feather="alert-triangle"></i><span>Under
                                    Maintenance</span> </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Settings</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('general-settings', 'security-settings', 'notification', 'connected-apps') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="settings"></i><span>General
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
                                class="<?php echo e(Request::is('system-settings', 'company-settings', 'localization-settings', 'prefixes', 'preference', 'appearance', 'social-authentication', 'language-settings','language-settings-web') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="globe"></i><span>Website
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
                                class="<?php echo e(Request::is('invoice-settings', 'printer-settings', 'pos-settings', 'custom-fields') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="smartphone"></i>
                                <span>App Settings</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('email-settings', 'sms-gateway', 'otp-settings', 'gdpr-settings') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="monitor"></i>
                                <span>System Settings</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('payment-gateway-settings', 'bank-settings-grid', 'bank-settings-list','tax-rates', 'currency-settings') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="dollar-sign"></i>
                                <span>Settings</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('storage-settings', 'ban-ip-address') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="hexagon"></i>
                                <span>Other Settings</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(url('storage-settings')); ?>"
                                        class="<?php echo e(Request::is('storage-settings') ? 'active' : ''); ?>">Storage</a>
                                </li>
                                <li><a href="<?php echo e(url('ban-ip-address')); ?>"
                                        class="<?php echo e(Request::is('ban-ip-address') ? 'active' : ''); ?>">Ban IP
                                        Address</a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e(Request::is('logout') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('logout')); ?>"><i data-feather="log-out"></i><span>Logout</span> </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">UI Interface</h6>
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('ui-alerts', 'ui-accordion', 'ui-avatar', 'ui-badges', 'ui-borders', 'ui-buttons', 'ui-buttons-group', 'ui-breadcrumb', 'ui-cards', 'ui-carousel', 'ui-colors', 'ui-dropdowns', 'ui-grid', 'ui-images', 'ui-lightbox', 'ui-modals', 'ui-media', 'ui-offcanvas', 'ui-pagination', 'ui-popovers', 'ui-progress', 'ui-placeholders', 'ui-rangeslider', 'ui-spinner', 'ui-sweetalerts', 'ui-nav-tabs', 'ui-toasts', 'ui-tooltips', 'ui-typography', 'ui-video') ? 'active subdrop' : ''); ?>">
                                <i data-feather="layers"></i><span>Base UI</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('ui-ribbon', 'ui-clipboard', 'ui-drag-drop', 'ui-rating', 'ui-text-editor', 'ui-counter', 'ui-scrollbar', 'ui-stickynote', 'ui-timeline') ? 'active subdrop' : ''); ?>">
                                <i data-feather="layers"></i><span>Advanced UI</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('chart-apex', 'chart-c3', 'chart-js', 'chart-morris', 'chart-flot', 'chart-peity') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="bar-chart-2"></i>
                                <span>Charts</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('icon-fontawesome', 'icon-feather', 'icon-ionic', 'icon-material', 'icon-pe7', 'icon-simpleline', 'icon-themify', 'icon-weather', 'icon-typicon', 'icon-flag') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="database"></i>
                                <span>Icons</span><span class="menu-arrow"></span>
                            </a>
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
                                class="<?php echo e(Request::is('form-wizard', 'form-select2', 'form-validation', 'form-floating-labels', 'form-vertical', 'form-horizontal', 'form-basic-inputs', 'form-checkbox-radios', 'form-input-groups', 'form-grid-gutters', 'form-select', 'form-mask', 'form-fileupload') ? 'active subdrop' : ''); ?>">
                                <i data-feather="edit"></i><span>Forms</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li class="submenu submenu-two">
                                    <a href="javascript:void(0);"
                                        class="<?php echo e(Request::is('form-basic-inputs', 'form-checkbox-radios', 'form-input-groups', 'form-grid-gutters', 'form-select', 'form-mask', 'form-fileupload') ? 'active subdrop' : ''); ?>">Form
                                        Elements<span class="menu-arrow inside-submenu"></span></a>
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
                                        class="<?php echo e(Request::is('form-horizontal', 'form-vertical', 'form-floating-labels') ? 'active subdrop' : ''); ?>">Layouts<span
                                            class="menu-arrow inside-submenu"></span></a>
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
                                class="<?php echo e(Request::is('tables-basic', 'data-tables') ? 'active subdrop' : ''); ?>"><i
                                    data-feather="columns"></i><span>Tables</span><span class="menu-arrow"></span></a>
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
                <?php endif; ?>
                <?php if(auth()->check() && !auth()->user()->isSuperAdmin()): ?>
                <li class="submenu-open">
                    <h6 class="submenu-hdr">Help</h6>
                    <ul>
                        <li><a href="javascript:void(0);"><i
                                    data-feather="file-text"></i><span>Documentation</span></a></li>
                        <li><a href="javascript:void(0);"><i data-feather="lock"></i><span>Changelog v2.0.3</span></a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="file-minus"></i><span>Multi
                                    Level</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="javascript:void(0);">Level 1.1</a></li>
                                <li class="submenu submenu-two"><a href="javascript:void(0);">Level 1.2<span
                                            class="menu-arrow inside-submenu"></span></a>
                                    <ul>
                                        <li><a href="javascript:void(0);">Level 2.1</a></li>
                                        <li class="submenu submenu-two submenu-three"><a
                                                href="javascript:void(0);">Level 2.2<span
                                                    class="menu-arrow inside-submenu inside-submenu-two"></span></a>
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
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
<?php /**PATH C:\laragon\www\laundry\resources\views/layout/partials/sidebar.blade.php ENDPATH**/ ?>