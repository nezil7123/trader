<x-app-layout>
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
             <?php 
                $showLoginPop = false;
                if($orders == "logout")
                {
                    $orders = [];
                    $showLoginPop = true;
                }
                    $types = array(
                        '1' => 'Buy',
                        '-1' => 'Sell'
                    );
                    $productTypes = array(
                     
                    );
                    $status = array(
                        1 => "Canceled",
                        2 => "Traded / Filled",
                        3 => "(Not used currently)",
                        4 => "Transit",
                        5 => "Rejected",
                        6 => "Pending",
                        7 => "Expired",
                    );

                    ?>

            <!-- Left side columns -->
            @if(!$showLoginPop)
            <div class="col-12">
                <div class="row">

                    <!-- Sales Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">

                            <div class="card-body">
                                <h5 class="card-title">Orders <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-cart"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ sizeof($orders) }}</h6>
                                        <span class="text-success small pt-1 fw-bold"></span> <span
                                            class="text-muted small pt-2 ps-1">Orders</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Sales Card -->
                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Revenue <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-rupee"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>₹{{ number_format($positions->overall->pl_total,2) }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ number_format(($positions->overall->pl_total/3000) * 100,2) }}%</span> <span
                                            class="text-muted small pt-2 ps-1">increase</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Revenue Card -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">

                            <div class="card-body">
                                <h5 class="card-title">Positions <span>| Today</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-currency-rupee"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ @$positions->overall->count_total }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ @$positions->overall->count_open }}</span> <span
                                            class="text-muted small pt-2 ps-1">open</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->


                    <!-- Recent Sales -->
                    <div class="col-12">
                        <div class="card recent-sales overflow-auto">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>

                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Recent Orders <span>| Today</span></h5>

                                <table class="table table-borderless datatable">
                                    <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Traded Price</th>
                                    <th scope="col">Order Type</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $item)
                                <tr>
                                    <th scope="row"><a href="#">#{{$item->id}}</a></th>
                                    <td>{{$item->symbol}}</td>
                                    <td>{{$item->orderDateTime}}</td>
                                    <td>{{$item->tradedPrice}}</td>
                                    <td>{{ $types[$item->side]}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td><span class="badge bg-primary">{{$status[$item->status]}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                                </table>

                            </div>

                        </div>
                    </div><!-- End Recent Sales -->

                </div>
            </div><!-- End Left side columns -->
            @endif


        </div>
    </section>
    
    @if($showLoginPop)
    <div class="" style="z-index: 2000; position: fixed; top: 0; left: 0; height: 100%; width: 100%; background: rgba(0,0,0,.5); display: flex; justify-content: center; align-items: center;">
        <h2 style="color: #FFF;">Please login to FYERS</h2>
        <br>&nbsp; &nbsp;
         <a class="btn btn-success" href="https://api.fyers.in/api/v2/generate-authcode?client_id={{Auth::user()->api_id}}&redirect_uri={{Auth::user()->redirect_uri}}&response_type=code&state=sample_state*/">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </a>
    </div>
        
    @endif

</x-app-layout>
