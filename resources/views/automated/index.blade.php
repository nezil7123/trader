<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <?php 
                    $types = array(
                        '1' => 'Limit Order',
                        '2' => 'Market Order',
                        '3' => 'Stop Order / SL-M',
                        '4' => 'Stop Limit Order/ SL-L'
                    );
                    $productTypes = array(
                     
                    );
                    $status = array(
                        '1' => 'Active',
                        '0' => 'Inactive',
                    );
                    $sl = 1;
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Alert Logs</h5>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Product Type</th>
                                    <th scope="col">Take Profit</th>
                                    <th scope="col">Stop Loss</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $item)
                                <tr>
                                    <th scope="row"><a href="#">#{{$sl++}}</a></th>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->symbol}}</td>
                                    <td>{{ $types[$item->type]}}</td>
                                    <td>{{$item->product_type}}</td>
                                    <td>{{$item->take_profit}}</td>
                                    <td>{{$item->stop_loss}}</td>
                                    <td><span class="badge bg-{{ $item->status ? 'success' : 'danger' }}">{{$status[$item->status]}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Sales -->
        </div>
    </section>

</x-app-layout>
