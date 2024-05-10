<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <?php 
                    $types = array(
                        '1' => 'Buy',
                        '-1' => 'Sell'
                    );
                    $productTypes = array(
                     
                    );
                    $status = array(
                        1 => "Profit",
                        0 => "Loss"
                    );

                    ?>
                  
                    
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>

                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Day</th>
                                    <th scope="col">Strategy</th>
                                    <th scope="col">Order Type</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $item)
                                <tr>
                                    <th scope="row"><a href="#">#{{$item->id}}</a></th>
                                    <td>{{$item->symbol}}</td>
                                    <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ date('h:i:s', strtotime($item->created_at)) }}</td>
                                    <td>{{$item->day}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{ $types[$item->side]}}</td>
                                   
                                    <td><span class="badge bg-{{ $item->profit ? 'success' : 'danger'}}">{{$status[$item->profit]}}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div><!-- End Recent Sales -->
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card recent-sales overflow-auto">
                  <div class="card-body">
                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Strategy</th>
                                    <th scope="col">Total Trade</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Loss</th>
                                    <th scope="col">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($codeLog as $item)
                            <tr>
                                <th scope="row"><a href="#">#{{$item->id}}</a></th>
                                <td>{{$item->code}}</td>
                                <td>{{$item->count}}</td>
                                <td>{{$item->profit}}</td>
                                <td>{{$item->count - $item->profit}}</td>
                                <td>{{number_format(($item->profit/$item->count) * 100,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                   </div>
            </div>
            <div class="col-md-6">
                 <div class="card recent-sales overflow-auto">
                  <div class="card-body">
                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Total Trade</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Loss</th>
                                    <th scope="col">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($symbolLog as $item)
                            <tr>
                                <th scope="row"><a href="#">#{{$item->id}}</a></th>
                                <td>{{$item->symbol}}</td>
                                <td>{{$item->count}}</td>
                                <td>{{$item->profit}}</td>
                                <td>{{$item->count - $item->profit}}</td>
                                <td>{{number_format(($item->profit/$item->count) * 100,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($weekData as $week => $weekItem)
            <div class="col-md-4">
                <div class="card recent-sales overflow-auto">
                    <div class="card-body">
                        <h4 class="py-2">{{ $week }}</h4>
                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Total Trade</th>
                                    <th scope="col">Profit</th>
                                    <th scope="col">Loss</th>
                                    <th scope="col">%</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($weekItem as $item)
                            <tr>
                                <th scope="row"><a href="#">#{{$item->id}}</a></th>
                                <td>{{$item->code}}</td>
                                <td>{{$item->count}}</td>
                                <td>{{$item->profit}}</td>
                                <td>{{$item->count - $item->profit}}</td>
                                <td>{{number_format(($item->profit/$item->count) * 100,2) }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        
    </section>

</x-app-layout>
