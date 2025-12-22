<div class="sidebars settings-sidebar theiaStickySidebar" id="sidebar2">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu5" class="sidebar-menu">
            <ul>
                <li class="submenu-open">
                    <ul>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('general-settings', 'security-settings', 'notification', 'connected-apps') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="settings"></i><span>General Settings</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('general-settings')); ?>"
                                        class="<?php echo e(Request::is('general-settings') ? 'active' : ''); ?>">Profile</a></li>
                                <li><a href="<?php echo e(url('security-settings')); ?>"
                                        class="<?php echo e(Request::is('security-settings') ? 'active' : ''); ?>">Security</a></li>
                                <li><a href="<?php echo e(url('notification')); ?>"
                                        class="<?php echo e(Request::is('notification') ? 'active' : ''); ?>">Notifications</a></li>
                                <li class="<?php echo e(Request::is('connected-apps') ? 'active' : ''); ?>"><a
                                        href="<?php echo e(url('connected-apps')); ?>">Connected Apps</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('system-settings', 'company-settings', 'localization-settings', 'prefixes', 'preference', 'appearance', 'social-authentication', 'language-settings', 'language-settings-web') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="airplay"></i><span>Website Settings</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('system-settings')); ?>"
                                        class="<?php echo e(Request::is('system-settings') ? 'active' : ''); ?>">System
                                        Settings</a></li>
                                <li><a href="<?php echo e(url('company-settings')); ?>"
                                        class="<?php echo e(Request::is('company-settings') ? 'active' : ''); ?>">Company Settings
                                    </a>
                                </li>
                                <li><a href="<?php echo e(url('localization-settings')); ?>"
                                        class="<?php echo e(Request::is('localization-settings') ? 'active' : ''); ?>">Localization</a>
                                </li>
                                <li><a href="<?php echo e(url('prefixes')); ?>"
                                        class="<?php echo e(Request::is('prefixes') ? 'active' : ''); ?>">Prefixes</a></li>
                                <li><a href="<?php echo e(url('preference')); ?>"
                                        class="<?php echo e(Request::is('preference') ? 'active' : ''); ?>">Preference</a></li>
                                <li><a href="<?php echo e(url('appearance')); ?>"
                                        class="<?php echo e(Request::is('appearance') ? 'active' : ''); ?>">Appearance</a>
                                </li>
                                <li><a href="<?php echo e(url('social-authentication')); ?>"
                                        class="<?php echo e(Request::is('social-authentication') ? 'active' : ''); ?>">Social
                                        Authentication</a></li>
                                <li><a href="<?php echo e(url('language-settings')); ?>"
                                        class="<?php echo e(Request::is('language-settings', 'language-settings-web') ? 'active' : ''); ?>">Language</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('invoice-settings', 'printer-settings', 'pos-settings', 'custom-fields') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="archive"></i><span>App
                                    Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('invoice-settings')); ?>"
                                        class="<?php echo e(Request::is('invoice-settings') ? 'active' : ''); ?>">Invoice</a></li>
                                <li><a href="<?php echo e(url('printer-settings')); ?>"
                                        class="<?php echo e(Request::is('printer-settings') ? 'active' : ''); ?>">Printer </a></li>
                                <li><a href="<?php echo e(url('pos-settings')); ?>"
                                        class="<?php echo e(Request::is('pos-settings') ? 'active' : ''); ?>">POS</a></li>
                                <li><a href="<?php echo e(url('custom-fields')); ?>"
                                        class="<?php echo e(Request::is('custom-fields') ? 'active' : ''); ?>">Custom Fields</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('email-settings', 'sms-gateway', 'otp-settings', 'gdpr-settings') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="server"></i><span>System
                                    Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('email-settings')); ?>"
                                        class="<?php echo e(Request::is('email-settings') ? 'active' : ''); ?>">Email</a></li>
                                <li><a href="<?php echo e(url('sms-gateway')); ?>"
                                        class="<?php echo e(Request::is('sms-gateway') ? 'active' : ''); ?>">SMS Gateways</a></li>
                                <li><a href="<?php echo e(url('otp-settings')); ?>"
                                        class="<?php echo e(Request::is('otp-settings') ? 'active' : ''); ?>">OTP</a></li>
                                <li><a href="<?php echo e(url('gdpr-settings')); ?>"
                                        class="<?php echo e(Request::is('gdpr-settings') ? 'active' : ''); ?>">GDPR Cookies</a>
                                </li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="<?php echo e(Request::is('payment-gateway-settings', 'bank-settings-grid', 'bank-settings-list', 'tax-rates', 'currency-settings') ? 'active subdrop' : ''); ?> "><i
                                    data-feather="credit-card"></i><span>Financial
                                    Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('payment-gateway-settings')); ?>"
                                        class="<?php echo e(Request::is('payment-gateway-settings') ? 'active' : ''); ?>">Payment
                                        Gateway</a></li>
                                <li><a href="<?php echo e(url('bank-settings-grid')); ?>"
                                        class="<?php echo e(Request::is('bank-settings-grid', 'bank-settings-list') ? 'active' : ''); ?>">Bank
                                        Accounts </a>
                                </li>
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
                                    data-feather="layout"></i><span>Other
                                    Settings</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="<?php echo e(url('storage-settings')); ?>"
                                        class="<?php echo e(Request::is('storage-settings') ? 'active' : ''); ?>">Storage</a></li>
                                <li><a href="<?php echo e(url('ban-ip-address')); ?>"
                                        class="<?php echo e(Request::is('ban-ip-address') ? 'active' : ''); ?>">Ban IP Address </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\laundry\resources\views/components/settings-sidebar.blade.php ENDPATH**/ ?>