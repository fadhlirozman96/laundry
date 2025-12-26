<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Rapy | Laundry Management System</title>
<!-- Stylesheets -->
<link href="<?php echo e(asset('landing/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('landing/assets/css/slick-theme.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('landing/assets/css/slick.css')); ?>">
<link href="<?php echo e(asset('landing/assets/css/style.css')); ?>" rel="stylesheet">

<link rel="shortcut icon" href="<?php echo e(asset('landing/assets/images/favicon.png')); ?>" type="image/x-icon">
<link rel="icon" href="<?php echo e(asset('landing/assets/images/favicon.png')); ?>" type="image/x-icon">
<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
</head>

<body>

<div class="page-wrapper">

    <!-- Main Header-->
	<header class="main-header header-style-two">
        <div class="header-lower">
            <div class="container">
                <!-- Main box -->
                <div class="main-box">
                    <div class="logo-box">
                        <div class="logo"><a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('landing/assets/images/logo-2.svg')); ?>" alt="" title="Rapy"></a></div>
                    </div>

                    <!--Nav Box-->
                    <div class="nav-outer">    
                        <nav class="nav main-menu">
                            <ul class="navigation">
                                <li><a href="<?php echo e(url('/')); ?>">Home</a></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#features">Features</a></li>
                                <li><a href="#pricing">Pricing</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </nav>
                        <!-- Main Menu End-->
                    </div>                                                          
                    <div class="outer-box">
                        <div class="btn">
                            <a href="<?php echo e(route('login')); ?>" class="theme-btn v2">Login</a>
                        </div>
                        <div class="mobile-nav-toggler">
                            <i class="fa fa-bars"></i>
                        </div>
                    </div>
                </div>
                <!-- Mobile Menu  -->
            </div>
        </div>
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
        
            <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
            <nav class="menu-box">
                <div class="upper-box">
                    <div class="nav-logo"><a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('landing/assets/images/logo.png')); ?>" alt="" title=""></a></div>
                    <div class="close-btn"><i class="icon fa fa-times"></i></div>
                </div>
        
                <ul class="navigation clearfix">
                    <!--Keep This Empty / Menu will come through Javascript-->
                </ul>
                <ul class="contact-list-one">
                    <li>
                        <!-- Contact Info Box -->
                        <div class="contact-info-box">
                            <i class="icon lnr-icon-phone-handset"></i>
                            <span class="title">Call Now</span>
                            <a href="tel:+60123456789">+60 12-345-6789</a>
                        </div>
                    </li>
                    <li>
                        <!-- Contact Info Box -->
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-envelope1"></span>
                            <span class="title">Send Email</span>
                            <a href="mailto:support@rapy.com">support@rapy.com</a>
                        </div>
                    </li>
                    <li>
                        <!-- Contact Info Box -->
                        <div class="contact-info-box">
                            <span class="icon lnr-icon-clock"></span>
                            <span class="title">Business Hours</span>
                            Mon - Sat 8:00 - 6:30, Sunday - CLOSED
                        </div>
                    </li>
                </ul>
        
        
                <ul class="social-links">
                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </nav>
        </div><!-- End Mobile Menu -->

        <!-- Sticky Header  -->
        <div class="sticky-header">
            <div class="container">
                <div class="inner-container">
                    <!--Logo-->
                    <div class="logo">
                        <a href="<?php echo e(url('/')); ?>" title=""><img src="<?php echo e(asset('landing/assets/images/logo.png')); ?>" alt="" title=""></a>
                    </div>
        
                    <!--Right Col-->
                    <div class="nav-outer">
                        <!-- Main Menu -->
                        <nav class="main-menu">
                            <div class="navbar-collapse show collapse clearfix">
                                <ul class="navigation clearfix">
                                    <!--Keep This Empty / Menu will come through Javascript-->
                                </ul>
                            </div>
                        </nav><!-- Main Menu End-->
        
                        <!--Mobile Navigation Toggler-->
                        <div class="mobile-nav-toggler">
                            <i class="fa fa-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End Sticky Menu -->
	</header>
	<!--End Main Header -->

    <!-- banner-section -->
    <section class="banner-section-two">
        <div class="container">
            <div class="row align-items-center">
                <!-- content-column -->
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <div class="banner-text-v2">
                        <span class="sub-title wow fadeInUp">#1 Complete Laundry Management Solution</span>
                        <h1 class="wow fadeInUp" data-wow-delay="100ms">Streamline Your <span>Laundry Business</span> Operations</h1>
                        <div class="text wow fadeInUp" data-wow-delay="200ms">All-in-one platform with POS, order tracking, inventory management, 
                            employee scheduling, quality control, and real-time analytics for your laundry business.
                        </div>
                        <div class="btn-box wow fadeInUp" data-wow-delay="300ms">
                            <a href="<?php echo e(route('login')); ?>" class="theme-btn">Get Started</a>
                        </div>
                        <div class="rating-sec">
                            <ul class="rating">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><span>4.9 Out of 5.0</span></li>
                            </ul>
                            <span class="contact"><img src="<?php echo e(asset('landing/assets/images/icons/phone.svg')); ?>" alt="" /> Phone:  +60 12-345-6789</span>
                        </div>
                    </div>
                </div>
                <!-- image-column -->
                <div class="col-lg-5">
                    <div class="banner-img-v2">
                        <img src="<?php echo e(asset('landing/assets/images/resource/banner1-3.png')); ?>" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End banner-section -->

    <!-- about-section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="sec-title-two text-center wow fadeInUp">
                <span class="sub-title">Transform your laundry business with modern technology</span>
                <h2>Everything You Need <br>to Run Your Laundry Business</h2>
            </div>
            <div class="row g-0">
                <!-- image-column -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="about-img-v2 wow fadeInLeft">
                        <img src="<?php echo e(asset('landing/assets/images/resource/about1-1.png')); ?>" alt="">
                    </div>
                </div>
                <!-- content-column -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="about-content wow fadeInRight">
                        <div class="text">Rapy is a complete laundry management system that helps you manage orders from pickup to delivery, 
                            process payments, track machines, manage staff, and grow your business with powerful analytics and reporting tools.
                        </div>
                        <div class="content-box">
                            <h6 class="title">What Makes Rapy Different</h6>
                            <div class="text">Built specifically for laundries - from small shops to large chains. Features include integrated POS, 
                                QR code tracking, quality control workflows, multi-store management, employee scheduling, machine monitoring, 
                                and comprehensive financial reporting.
                            </div>
                            <a href="<?php echo e(route('login')); ?>" class="about-btn theme-btn">Start Your Free Trial</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End about-section -->
    
    <!-- funfact-section -->
    <section class="funfact-section pt-0">
        <div class="container">
            <div class="fact-box">
                <div class="fact-content wow fadeInUp">
                    <h2>Ready to Transform Your Laundry Business?<br>Get Started Today.</h2>
                    <div class="text">Join hundreds of laundries already using Rapy. No credit card required for trial.</div>
                    <a href="<?php echo e(route('login')); ?>" class="fac-btn theme-btn">Start Free Trial</a>
                </div>
                <div class="fact-counter">
                    <div class="row">
                        <!-- Counter block-->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp">
                            <div class="counter-block">
                                <div class="content">
                                    <div class="count-box"><span class="count-text" data-speed="3000" data-stop="500">0</span>+</div>
                                    <h6 class="counter-title">Active laundry businesses</h6>
                                </div>
                            </div>
                        </div>
    
                        <!--Counter block-->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
                            <div class="counter-block">
                                <div class="content">
                                    <div class="count-box"><span class="count-text" data-speed="3000" data-stop="50">0</span>k+</div>
                                    <h6 class="counter-title">Orders processed monthly</h6>
                                </div>
                            </div>
                        </div>
    
                        <!--Counter block-->
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="600ms">
                            <div class="counter-block">
                                <div class="content">
                                    <div class="count-box"><span class="count-text" data-speed="3000" data-stop="98">0</span>%</div>
                                    <h6 class="counter-title">Customer satisfaction rate</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End funfact-section -->

    <!-- feature-section -->
    <section class="feature-section" id="features">
        <div class="container">
            <div class="row g-0">
                <!-- content-column -->
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="feature-text">
                        <div class="sec-title-two wow fadeInLeft">
                            <h2>Complete Laundry Management Features</h2>
                            <div class="text">Everything you need in one powerful platform</div>
                        </div>
                        <div class="image-box wow fadeInUp">
                            <figure class="image"><img src="<?php echo e(asset('landing/assets/images/resource/feature1-1.png')); ?>" alt=""></figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="block-column">
                        <!-- feature-block -->
                        <div class="feature-block wow fadeInLeft">
                            <div class="inner-box">
                                <div class="icon-box"><i class="flaticon-cog-1"></i></div>
                                <h4 class="title">POS & Order Management</h4>
                                <div class="text">Complete point-of-sale system with order tracking, 
                                    QR codes, payment processing, coupons, and real-time status updates.
                                </div>
                            </div>
                        </div>
                        <!-- feature-block -->
                        <div class="feature-block v2 wow fadeInUp" data-wow-delay="100ms">
                            <div class="inner-box">
                                <div class="icon-box"><i class="flaticon-settings-2"></i></div>
                                <h4 class="title">Quality Control & Machine Tracking</h4>
                                <div class="text">Built-in QC workflows and machine usage monitoring 
                                    to ensure quality service and optimal equipment utilization.
                                </div>
                            </div>
                        </div>
                        <!-- feature-block -->
                        <div class="feature-block wow fadeInRight" data-wow-delay="200ms">
                            <div class="inner-box">
                                <div class="icon-box"><i class="flaticon-folder-outline"></i></div>
                                <h4 class="title">Employee & Store Management</h4>
                                <div class="text">Manage multiple stores, staff schedules, attendance, 
                                    payroll, and track performance across all locations.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End feature-section -->

    <!-- main-section-two -->
    <section class="main-section-two">
        <!-- why-choose-us-section -->
        <div class="why-choose-us-section">
            <div class="container">
                <div class="sec-title-two text-center wow fadeInUp">
                    <span class="sub-title">Built specifically for laundry businesses</span>
                    <h2>Why Laundry Businesses Choose Rapy</h2>
                </div>
                <div class="row">
                    <!-- content-box -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="content-box wow fadeInUp">
                            <div class="text">Purpose-built for laundries with industry-specific features 
                                like service pricing per unit (Kg/Pc/Set), QR tracking, quality control, 
                                machine monitoring, and multi-payment options (Cash/Card/QR).
                            </div>
                        </div>
                    </div>
                    <!-- choose-block -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="choose-block wow fadeInUp">
                            <div class="icon-box">
                                <i class="flaticon-monitor"></i>
                            </div>
                            <h6 class="title"><a href="#">Intuitive Dashboard</a></h6>
                        </div>
                    </div>
                    <!-- choose-block -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="choose-block wow fadeInUp" data-wow-delay="100ms">
                            <div class="icon-box">
                                <i class="flaticon-shield"></i>
                            </div>
                            <h6 class="title"><a href="#">Multi-Store Support</a></h6>
                        </div>
                    </div>
                    <!-- choose-block -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="choose-block wow fadeInUp" data-wow-delay="200ms">
                            <div class="icon-box">
                                <i class="flaticon-settings"></i>
                            </div>
                            <h6 class="title"><a href="#">Advanced Reporting</a></h6>
                        </div>
                    </div>
                    <!-- choose-block -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="choose-block wow fadeInUp" data-wow-delay="300ms">
                            <div class="icon-box">
                                <i class="flaticon-support-2"></i>
                            </div>
                            <h6 class="title"><a href="#">Dedicated Support</a></h6>
                        </div>
                    </div>
                    <!-- content-box-two -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="content-box-two wow fadeInUp" data-wow-delay="400ms">
                            <div class="rating-sec">
                                <ul class="rating">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><span>4.9 Out of 5.0</span></li>
                                </ul>
                                <a href="<?php echo e(route('login')); ?>" class="choose-btn theme-btn">Try It Free</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End why-choose-us-section -->

        <!-- growth-section --> 
        <div class="growth-section">
            <div class="container">
                <div class="growth-box">
                    <div class="row">
                        <!-- content-column -->
                        <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
                            <div class="growth-column wow fadeInUp">
                                <h2>Complete Laundry Business Solution</h2>
                                <div class="content-box">
                                    <ul class="list">
                                        <li><i class="fa-solid fa-check"></i>Integrated POS with multiple payment methods</li>
                                        <li><i class="fa-solid fa-check"></i>QR code order tracking & customer notifications</li>
                                        <li><i class="fa-solid fa-check"></i>Quality control workflows & machine monitoring</li>
                                        <li><i class="fa-solid fa-check"></i>Employee scheduling, attendance & payroll</li>
                                        <li><i class="fa-solid fa-check"></i>Multi-store management with role-based access</li>
                                        <li><i class="fa-solid fa-check"></i>Sales, expense & profit reports with analytics</li>
                                        <li><i class="fa-solid fa-check"></i>Coupon system & customer management</li>
                                        <li><i class="fa-solid fa-check"></i>Audit trail & activity logging</li>
                                    </ul>
                                    <div class="btn-box">
                                        <a href="<?php echo e(route('login')); ?>" class="theme-btn">Get Started Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- image-column -->
                        <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
                            <div class="image-box wow fadeInRight">
                                <figure class="image"><img src="<?php echo e(asset('landing/assets/images/resource/grwoth1-1.png')); ?>" alt=""></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End growth-section -->
    </section>
    <!-- End main-section-two -->

    <!-- testimonial-section -->
    <section class="testimonial-section"> 
        <div class="container">
            <div class="sec-title-two text-center wow fadeInUp">
                <span class="sub-title">See what laundry business owners say</span>
                <h2>Trusted by <span>500+ Laundries</span></h2>
            </div>
            <div class="testi-box">
                <div class="testimonial-slider">
                    <div class="testimonial-slide">
                        <div class="content-box">
                            <ul class="rating">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <div class="text">"The POS system and QR tracking have been game-changers for us. 
                                Our customers love being able to track their orders in real-time. Efficiency increased by 60%!"
                            </div>
                            <div class="auther-info">
                                <h4 class="name">Ahmad Ibrahim</h4>
                                <span class="designation">Owner at Fresh Laundry KL</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="content-box">
                            <ul class="rating">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <div class="text">"Managing 5 outlets was challenging until we found Rapy. 
                                The multi-store management and employee scheduling features are phenomenal!"
                            </div>
                            <div class="auther-info">
                                <h4 class="name">Siti Nurhaliza</h4>
                                <span class="designation">CEO at SpinCycle Chain</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-slide">
                        <div class="content-box">
                            <ul class="rating">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                            <div class="text">"The quality control module ensures consistent service quality. 
                                Machine tracking helps us schedule maintenance. Best laundry software we've used!"
                            </div>
                            <div class="auther-info">
                                <h4 class="name">David Tan</h4>
                                <span class="designation">Operations Manager at CleanPro</span>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </section>
    <!-- End testimonial-section -->

    <!-- pricing-section-two -->
    <section class="pricing-section-two pt-0" id="pricing">
        <div class="container">
            <div class="row">
                <!-- content-column -->
                <div class="col-xl-5 col-lg-12 col-md-12 col-sm-12">
                    <div class="pricing-column wow fadeInLeft">
                        <div class="sec-title-two">
                            <h2>Flexible Pricing Plans</h2>
                            <div class="text">Choose the plan that fits your business. All plans include POS, 
                                order management, reporting, and customer support. No hidden fees.
                            </div>
                        </div>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="pills-monthly-tab" data-bs-toggle="pill" data-bs-target="#pills-monthly" type="button" role="tab" aria-controls="pills-monthly" aria-selected="true">Monthly</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="pills-yearly-tab" data-bs-toggle="pill" data-bs-target="#pills-yearly" type="button" role="tab" aria-controls="pills-yearly" aria-selected="false">Yearly</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-12 col-md-12 col-sm-12">
                    <div class="inner-column wow fadeInRight" data-wow-delay="100ms">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-monthly" role="tabpanel" aria-labelledby="pills-monthly-tab">
                                <div class="row">
                                    <!-- pricin-block-two -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="pricin-block-two active">
                                            <div class="price-box">
                                                <span class="sub-title">Starter Plan</span>
                                                <h2><span>RM</span>199<small>/ month</small></h2>
                                                <div class="text">Perfect for single-location 
                                                    laundries just getting started.
                                                </div>
                                            </div>
                                            <div class="content-box">
                                                <ul class="list">
                                                    <li><i class="fa-solid fa-check"></i>POS & Order Management</li>
                                                    <li><i class="fa-solid fa-check"></i>1 Store Location</li>
                                                    <li><i class="fa-solid fa-check"></i>QR Order Tracking</li>
                                                    <li><i class="fa-solid fa-check"></i>Customer Management</li>
                                                    <li><i class="fa-solid fa-check"></i>Basic Reporting</li>
                                                    <li><i class="fa-solid fa-check"></i>Email Support</li>
                                                </ul>
                                                <a href="<?php echo e(route('login')); ?>" class="choose-btn theme-btn">Get Started</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- pricin-block-two -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="pricin-block-two">
                                            <div class="price-box two">
                                                <span class="sub-title">Business Plan</span>
                                                <h2><span>RM</span>399<small>/ month</small></h2>
                                                <div class="text">For growing laundries with 
                                                    multiple outlets and teams.
                                                </div>
                                            </div>
                                            <div class="content-box">
                                                <ul class="list">
                                                    <li><i class="fa-solid fa-check"></i>Everything in Starter</li>
                                                    <li><i class="fa-solid fa-check"></i>Up to 5 Store Locations</li>
                                                    <li><i class="fa-solid fa-check"></i>Quality Control Module</li>
                                                    <li><i class="fa-solid fa-check"></i>Machine Monitoring</li>
                                                    <li><i class="fa-solid fa-check"></i>Employee Management</li>
                                                    <li><i class="fa-solid fa-check"></i>Advanced Analytics</li>
                                                    <li><i class="fa-solid fa-check"></i>Priority Support 24/7</li>
                                                </ul>
                                                <a href="<?php echo e(route('login')); ?>" class="choose-btn theme-btn">Get Started</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-yearly" role="tabpanel" aria-labelledby="pills-yearly-tab">
                                <div class="row">
                                    <!-- pricin-block-two -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="pricin-block-two active">
                                            <div class="price-box">
                                                <span class="sub-title">Starter Plan</span>
                                                <h2><span>RM</span>1,999<small>/ year</small></h2>
                                                <div class="text">Save 17% with annual billing. 
                                                    Perfect for single outlets.
                                                </div>
                                            </div>
                                            <div class="content-box">
                                                <ul class="list">
                                                    <li><i class="fa-solid fa-check"></i>POS & Order Management</li>
                                                    <li><i class="fa-solid fa-check"></i>1 Store Location</li>
                                                    <li><i class="fa-solid fa-check"></i>QR Order Tracking</li>
                                                    <li><i class="fa-solid fa-check"></i>Customer Management</li>
                                                    <li><i class="fa-solid fa-check"></i>Basic Reporting</li>
                                                    <li><i class="fa-solid fa-check"></i>Email Support</li>
                                                </ul>
                                                <a href="<?php echo e(route('login')); ?>" class="choose-btn theme-btn">Get Started</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- pricin-block-two -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="pricin-block-two">
                                            <div class="price-box two">
                                                <span class="sub-title">Business Plan</span>
                                                <h2><span>RM</span>3,999<small>/ year</small></h2>
                                                <div class="text">Save 17% with annual billing. 
                                                    Best for multi-outlet chains.
                                                </div>
                                            </div>
                                            <div class="content-box">
                                                <ul class="list">
                                                    <li><i class="fa-solid fa-check"></i>Everything in Starter</li>
                                                    <li><i class="fa-solid fa-check"></i>Up to 5 Store Locations</li>
                                                    <li><i class="fa-solid fa-check"></i>Quality Control Module</li>
                                                    <li><i class="fa-solid fa-check"></i>Machine Monitoring</li>
                                                    <li><i class="fa-solid fa-check"></i>Employee Management</li>
                                                    <li><i class="fa-solid fa-check"></i>Advanced Analytics</li>
                                                    <li><i class="fa-solid fa-check"></i>Priority Support 24/7</li>
                                                </ul>
                                                <a href="<?php echo e(route('login')); ?>" class="choose-btn theme-btn">Get Started</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End pricing-section-two -->

    <!-- team-section -->
    <section class="team-section pt-0">
        <div class="container">
            <div class="sec-title-two text-center wow fadeInUp">
                <h3>Trusted By Leading Brands</h3>
            </div>
            <div class="outer-box">
                <ul class="member-list active">
                    <li class="circle"></li>
                    <li><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-1.png')); ?>" alt=""></div></li>
                    <li class="two"><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-2.png')); ?>" alt=""></div></li>
                    <li class="three"><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-3.png')); ?>" alt=""></div></li>
                    <li class="circle two"></li>
                </ul>
                <ul class="member-list two">
                    <li class="circle two"></li>
                    <li class="four"><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-4.png')); ?>" alt=""></div></li>
                    <li class="five"><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-5.png')); ?>" alt=""></div></li>
                    <li class="six"><div class="image-box"><img src="<?php echo e(asset('landing/assets/images/resource/client1-6.png')); ?>" alt=""></div></li>
                    <li class="circle"></li>
                </ul>
            </div>
        </div>
    </section>
    <!-- End team-section -->

    <!-- Main Footer -->
    <footer class="main-footer footer-style-two" id="contact">
        <!-- Widgets Section -->
        <div class="widgets-section">
            <div class="container">
                <div class="row">
                    <!-- Footer COlumn -->
                    <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="400ms">
                        <div class="footer-widget social-widget">
                            <div class="content-box">
                                <h3 class="title">About Rapy</h3>
                                <div class="text">Rapy is a comprehensive laundry management system designed to help 
                                    businesses streamline operations, improve customer service, and grow their revenue.
                                </div>
                                <ul class="social-icons">
                                    <li><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                    <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
    
                    <!-- Footer COlumn -->
                    <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="600ms">
                        <div class="footer-column">
                            <div class="footer-block-sec">
                                <div class="row">
                                    <!-- footer-block -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="footer-block">
                                            <div class="icon-box"><i class="fa-solid fa-ear-muffs"></i></div>
                                            <h6 class="title">Contact Info</h6>
                                            <div class="text">Phone:  +60 12-345-6789<br>
                                                Email:   support@rapy.com
                                            </div>
                                        </div>
                                    </div>
                                    <!-- footer-block -->
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="footer-block">
                                            <div class="icon-box"><i class="fa-solid fa-location-dot"></i></div>
                                            <h6 class="title">Our Address</h6>
                                            <div class="text">Kuala Lumpur,<br> 
                                                Malaysia
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-widget about-widget">
                                <h4 class="widget-title">Subscribe Newsletter</h4>
                                <div class="subscribe-form">
                                    <form method="post" action="#">
                                        <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <input type="email" name="email" class="email" value="" placeholder="Enter your email.." required="">
                                            <button type="button" class="theme-btn"><span class="btn-title">Subscribe</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!--  Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="inner-container">
                    <div class="copyright-text">Copyright <?php echo e(date('Y')); ?> <a href="<?php echo e(url('/')); ?>" title="">Rapy</a>. All rights reserved.</div>
                    <ul class="footer-nav">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!--End Main Footer -->

    
</div><!-- End Page Wrapper -->

<script src="<?php echo e(asset('landing/assets/js/jquery.js')); ?>"></script> 
<script src="<?php echo e(asset('landing/assets/js/popper.min.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/slick-animation.min.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/jquery.fancybox.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/wow.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/appear.js')); ?>"></script>
<script src="<?php echo e(asset('landing/assets/js/script.js')); ?>"></script>
</body>
</html>

<?php /**PATH C:\laragon\www\laundry\resources\views/landing.blade.php ENDPATH**/ ?>