<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <?php 
                    $types = array(
                        '1' => 'Long',
                        '2' => 'Short'
                    );
                    $productTypes = array(
                     
                    );
                    $status = array(
                        '1' => 'Hold',
                        '0' => 'Complete',
                    );

                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">LTP</th>
                                    <th scope="col">Qty</th>
                                    <th scope="col">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data->netPositions as $item)
                                <tr>
                                    <th scope="row"><a href="#">#{{$item->slNo}}</a></th>
                                    <td>{{$item->symbol}}</td>
                                    <td>{{$item->ltp}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{number_format($item->realized_profit,2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Sales -->
            <div class="col-xl-12">

                <div class="card">
                    <div class="card-body pt-3">
                        
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                               

                                <h5 class="card-title">Summary</h5>
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 label ">Count</div>
                                    <div class="col-lg-2 col-md-8">Open</div>
                                    <div class="col-lg-2 col-md-8">Total profit</div>
                                    <div class="col-lg-2 col-md-8">Realized profit</div>
                                    <div class="col-lg-2 col-md-8">Unrealized profit</div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 label ">{{ number_format($data->overall->count_total,2) }}</div>
                                    <div class="col-lg-2 col-md-4 label ">{{ number_format($data->overall->count_open,2) }}</div>
                                    <div class="col-lg-2 col-md-4 label ">{{ number_format($data->overall->pl_total,2) }}</div>
                                    <div class="col-lg-2 col-md-4 label ">{{ number_format($data->overall->pl_realized,2) }}</div>
                                    <div class="col-lg-2 col-md-4 label ">{{ number_format($data->overall->pl_unrealized,2) }}</div>
                                </div>

                               

                               
                            </div>

                            

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>
