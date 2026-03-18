<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Cristalcopo - Dashboard</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/skin_color.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    @include('layouts.includes.header')

    @include('layouts.includes.menu')

    <div class="content-wrapper overflow-visible">
        <div class="container-full">
            <section class="content">
                <div class="row">
                    <div class="col-xxl-8">
                        <div class="row">
                            <div class="col-xxl-4 col-lg-4 col-md-6 col-12">
                                <div class="box rounded-4" style="background: linear-gradient(-60deg, #1b84ff, #7fafff 35%, #1b84ff 37%);">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-15 bg-primary-light w-50 h-50 rounded-circle text-center p-0 align-content-center">
                                                <i class="feather-dollar-sign fs-22"></i>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="badge badge-primary-light badge-pill"><b><span class="feather-arrow-up"></span> +10%</b></span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical text-white"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Daily</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-15">
                                            <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                                <p class="m-0 text-white">Total invites</p>
                                                <h1 class="my-1 fw-500 text-white">$1,200</h1>
                                                <p class="m-0 text-white">Since Last Week</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-4 col-lg-4 col-md-6 col-12">
                                <div class="box rounded-4 b-1">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-15 bg-primary-light w-50 h-50 rounded-circle text-center p-0 align-content-center">
                                                <i class="mdi mdi-account-multiple fs-26"></i>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="badge badge-danger-light badge-pill"><b><span class="feather-arrow-down"></span> -8%</b></span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Daily</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-15">
                                            <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                                <p class="m-0">Total Customers</p>
                                                <h1 class="my-1 fw-500">2,102</h1>
                                                <p class="m-0">Since Last Week</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-4 col-lg-4 col-md-12 col-12">
                                <div class="box rounded-4 b-1">
                                    <div class="box-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-15 bg-primary-light w-50 h-50 rounded-circle text-center p-0 align-content-center">
                                                <i class="mdi mdi-cart fs-26"></i>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <span class="badge badge-primary-light badge-pill"><b><span class="feather-arrow-up"></span> +10%</b></span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Daily</a>
                                                        <a class="dropdown-item" href="#">Weekly</a>
                                                        <a class="dropdown-item" href="#">Yearly</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-15">
                                            <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                                <p class="m-0">Total Orders</p>
                                                <h1 class="my-1 fw-500">2,458</h1>
                                                <p class="m-0">Since Last Week</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-lg-12">
                                <div class="box rounded-4 b-1">
                                    <div class="box-header b-0 pb-0 d-flex justify-content-between align-items-center">
                                        <h3 class="fw-600 m-0">Sales Analytics</h3>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-outline btn-sm rounded-pill dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">This Month</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Daily</a>
                                                <a class="dropdown-item" href="#">This Weekly</a>
                                                <a class="dropdown-item" href="#">This Yearly</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div id="chart-container"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-lg-12 col-12">
                                <div class="box rounded-4 b-1">
                                    <div class="box-header no-border pb-0">
                                        <h3 class="fw-600 m-0">Latest Orders</h3>
                                        <div class="box-controls pull-right">
                                            <p class="m-0">View All</p>
                                        </div>
                                    </div>
                                    <div class="box-body px-10">
                                        <div class="table-responsive">
                                            <table class="table table-hover m-0 text-nowrap">
                                                <thead class="text-fade bg-light">
                                                    <tr>
                                                        <th class="b-0">Order Id</th>
                                                        <th class="b-0">Madicine Name</th>
                                                        <th class="b-0">Price</th>
                                                        <th class="b-0">Status</th>
                                                        <th class="b-0">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>#ORD121</td>
                                                        <td>Metformin</td>
                                                        <td>$10.50</td>
                                                        <td><span class="badge badge-pill badge-success-light">Delivered</span></td>
                                                        <td>
                                                            <a href="#" class="text-dark"><i class="fa fa-eye fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-trash-o fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-share fs-20" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>#ORD122</td>
                                                        <td>Omeprazole</td>
                                                        <td>$15.05</td>
                                                        <td><span class="badge badge-pill badge-success-light">Delivered</span></td>
                                                        <td>
                                                            <a href="#" class="text-dark"><i class="fa fa-eye fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-trash-o fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-share fs-20" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>#ORD123</td>
                                                        <td>Atorvastatin</td>
                                                        <td>$13.01</td>
                                                        <td><span class="badge badge-pill badge-warning-light">Pending</span></td>
                                                        <td>
                                                            <a href="#" class="text-dark"><i class="fa fa-eye fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-trash-o fs-20 me-5" aria-hidden="true"></i></a>
                                                            <a href="#" class="text-dark"><i class="fa fa-share fs-20" aria-hidden="true"></i></a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-12">
                        <div class="row">
                            <div class="col-xxl-12 col-lg-6">
                                <div class="box rounded-4 b-1 bg-transparent shadow-none custom-package">
                                    <div class="box-body align-content-end">
                                        <div>
                                            <p class="fw-600 fs-18 text-white">Discover How to Maximize Your Pharmacy's Efficiency.</p>
                                            <button class="btn btn-primary rounded-3 b-1 btn-md">Learn More <span class="feather-external-link"></span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-12 col-lg-6">
                                <div class="box rounded-4 b-1">
                                    <div class="box-header b-0 pb-0 d-flex justify-content-between align-items-center">
                                        <h3 class="fw-600 m-0">Top Selling</h3>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-outline btn-sm rounded-pill dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">This Month</button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#">Daily</a>
                                                <a class="dropdown-item" href="#">This Weekly</a>
                                                <a class="dropdown-item" href="#">This Yearly</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div id="chart-selling"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-4 col-md-6 col-12">
                        <div class="box rounded-4">
                            <div class="box-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-10 bg-success-light w-50 h-50 rounded-circle text-center p-0 align-content-center"><i class="mdi mdi-check-circle fs-22"></i></div>
                                        <p class="m-0 fw-600">Completed Payment</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical"></span></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Daily</a>
                                            <a class="dropdown-item" href="#">Weekly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-15">
                                    <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                        <p class="m-0">Revenue: $25,000</p>
                                        <h1 class="my-1 fw-500">200</h1>
                                        <p class="m-0">Since Last Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-4 col-md-6 col-12">
                        <div class="box rounded-4">
                            <div class="box-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-10 bg-warning-light w-50 h-50 rounded-circle text-center p-0 align-content-center"><i class="mdi mdi-alert fs-22"></i></div>
                                        <p class="m-0 fw-600">Pending Payments</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical"></span></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Daily</a>
                                            <a class="dropdown-item" href="#">Weekly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-15">
                                    <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                        <p class="m-0">Revenue: $10,000</p>
                                        <h1 class="my-1 fw-500">40</h1>
                                        <p class="m-0">Since Last Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-lg-4 col-md-12 col-12">
                        <div class="box rounded-4">
                            <div class="box-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-10 bg-danger-light w-50 h-50 rounded-circle text-center p-0 align-content-center"><i class="mdi mdi-close-circle fs-22"></i></div>
                                        <p class="m-0 fw-600">Failed Payments</p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary bg-none btn-sm p-0 fs-20" data-bs-toggle="dropdown" href="#" aria-expanded="false"><span class="feather-more-vertical"></span></button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Daily</a>
                                            <a class="dropdown-item" href="#">Weekly</a>
                                            <a class="dropdown-item" href="#">Yearly</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-15">
                                    <div class="d-flex flex-column flex-grow-1 fw-500 me-20">
                                        <p class="m-0">Revenue: $5,000</p>
                                        <h1 class="my-1 fw-500">5</h1>
                                        <p class="m-0">Since Last Week</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    @include('layouts.includes.footer')

    <aside class="control-sidebar">
        <div class="rpanel-title">
            <span class="pull-right btn btn-circle btn-danger" data-toggle="control-sidebar">
                <i class="ion ion-close text-white"></i>
            </span>
        </div>

        <ul class="nav nav-tabs control-sidebar-tabs">
            <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab"><i class="mdi mdi-message-text"></i></a></li>
            <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i class="mdi mdi-playlist-check"></i></a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane" id="control-sidebar-home-tab">
                <div class="flexbox">
                    <a href="javascript:void(0)" class="text-grey"><i class="ti-more"></i></a>
                    <p>Users</p>
                    <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
                </div>

                <div class="lookup lookup-sm lookup-right d-none d-lg-block">
                    <input type="text" name="s" placeholder="Search" class="w-p100">
                </div>

                <div class="media-list media-list-hover mt-20">
                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-success" href="#">
                            <img src="{{ asset('assets/images/avatar/1.jpg') }}" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16"><a class="hover-primary" href="#"><strong>Tyler</strong></a></p>
                            <p>Praesent tristique diam...</p>
                            <span>Just now</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-danger" href="#">
                            <img src="{{ asset('assets/images/avatar/2.jpg') }}" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16"><a class="hover-primary" href="#"><strong>Luke</strong></a></p>
                            <p>Cras tempor diam ...</p>
                            <span>33 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-warning" href="#">
                            <img src="{{ asset('assets/images/avatar/3.jpg') }}" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16"><a class="hover-primary" href="#"><strong>Evan</strong></a></p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>

                    <div class="media py-10 px-0">
                        <a class="avatar avatar-lg status-primary" href="#">
                            <img src="{{ asset('assets/images/avatar/4.jpg') }}" alt="...">
                        </a>
                        <div class="media-body">
                            <p class="fs-16"><a class="hover-primary" href="#"><strong>Evan</strong></a></p>
                            <p>In posuere tortor vel...</p>
                            <span>42 min ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="control-sidebar-settings-tab">
                <div class="flexbox">
                    <a href="javascript:void(0)" class="text-grey"><i class="ti-more"></i></a>
                    <p>Todo List</p>
                    <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
                </div>

                <ul class="todo-list mt-20">
                    <li class="py-15 px-5 by-1">
                        <input type="checkbox" id="basic_checkbox_1" class="filled-in">
                        <label for="basic_checkbox_1" class="mb-0 h-15"></label>
                        <span class="text-line">Nulla vitae purus</span>
                        <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                        <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
                    </li>
                    <li class="py-15 px-5">
                        <input type="checkbox" id="basic_checkbox_2" class="filled-in">
                        <label for="basic_checkbox_2" class="mb-0 h-15"></label>
                        <span class="text-line">Phasellus interdum</span>
                        <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                        <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
                    </li>
                    <li class="py-15 px-5 by-1">
                        <input type="checkbox" id="basic_checkbox_3" class="filled-in">
                        <label for="basic_checkbox_3" class="mb-0 h-15"></label>
                        <span class="text-line">Quisque sodales</span>
                        <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                        <div class="tools"><i class="fa fa-edit"></i><i class="fa fa-trash-o"></i></div>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <div class="control-sidebar-bg"></div>
</div>

<div class="sticky-toolbar">
    <a href="#" data-bs-toggle="tooltip" data-bs-placement="left" title="Buy Now" class="waves-effect waves-light btn btn-success btn-flat mb-5 btn-sm" target="_blank">
        <span class="icon-Money"><span class="path1"></span><span class="path2"></span></span>
    </a>
    <a href="https://themeforest.net/user/multipurposethemes/portfolio" data-bs-toggle="tooltip" data-bs-placement="left" title="Portfolio" class="waves-effect waves-light btn btn-danger btn-flat mb-5 btn-sm" target="_blank">
        <span class="icon-Image"></span>
    </a>
    <a id="chat-popup" href="#" data-bs-toggle="tooltip" data-bs-placement="left" title="Live Chat" class="waves-effect waves-light btn btn-warning btn-flat btn-sm">
        <span class="icon-Group-chat"><span class="path1"></span><span class="path2"></span></span>
    </a>
</div>

<div id="chat-box-body">
    <div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-lg btn-warning l-h-70">
        <div id="chat-overlay"></div>
        <span class="icon-Group-chat fs-30"><span class="path1"></span><span class="path2"></span></span>
    </div>

    <div class="chat-box">
        <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <button class="waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-45" type="button" data-bs-toggle="dropdown">
                    <span class="icon-Add-user fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
                <div class="dropdown-menu min-w-200">
                    <a class="dropdown-item fs-16" href="#"><span class="icon-Color me-15"></span>New Group</a>
                    <a class="dropdown-item fs-16" href="#"><span class="icon-Clipboard me-15"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></span>Contacts</a>
                    <a class="dropdown-item fs-16" href="#"><span class="icon-Group me-15"><span class="path1"></span><span class="path2"></span></span>Groups</a>
                    <a class="dropdown-item fs-16" href="#"><span class="icon-Active-call me-15"><span class="path1"></span><span class="path2"></span></span>Calls</a>
                    <a class="dropdown-item fs-16" href="#"><span class="icon-Settings1 me-15"><span class="path1"></span><span class="path2"></span></span>Settings</a>
                </div>
            </div>

            <div class="text-center flex-grow-1">
                <div class="text-dark fs-18">Mayra Sibley</div>
                <div>
                    <span class="badge badge-sm badge-dot badge-primary"></span>
                    <span class="text-faded fs-12">Active</span>
                </div>
            </div>

            <div class="chat-box-toggle">
                <button id="chat-box-toggle" class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45" type="button">
                    <span class="icon-Close fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
            </div>
        </div>

        <div class="chat-box-body">
            <div class="chat-box-overlay"></div>
            <div class="chat-logs">
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                        <span class="msg-avatar">
                            <img src="{{ asset('assets/images/avatar/2.jpg') }}" class="avatar avatar-lg">
                        </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-faded fs-12 mb-0">2 Hours</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Hi there, I'm Jesse and you?
                    </div>
                </div>

                <div class="chat-msg self">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">You</a>
                            <p class="text-faded fs-12 mb-0">3 minutes</p>
                        </div>
                        <span class="msg-avatar">
                            <img src="{{ asset('assets/images/avatar/3.jpg') }}" class="avatar avatar-lg">
                        </span>
                    </div>
                    <div class="cm-msg-text">
                        My name is Anne Clarc.
                    </div>
                </div>

                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                        <span class="msg-avatar">
                            <img src="{{ asset('assets/images/avatar/2.jpg') }}" class="avatar avatar-lg">
                        </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-faded fs-12 mb-0">40 seconds</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Nice to meet you Anne.<br>How can i help you?
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-input">
            <form>
                <input type="text" id="chat-input" placeholder="Send a message..."/>
                <button type="submit" class="chat-submit" id="chat-submit">
                    <span class="icon-Send fs-22"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/chat-popup.js') }}"></script>
<script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>

<script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="https://fastly.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
<script src="{{ asset('assets/vendor_components/OwlCarousel2/dist/owl.carousel.js') }}"></script>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script src="{{ asset('assets/js/demo.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
<script src="{{ asset('assets/js/pages/dashboard.js') }}"></script>

</body>
</html>
