<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    
                    <?php 
                    $dataArray = [];
                    foreach($logs as $title => $row)
                    {
                        $data = [];
                        foreach($row as $log)
                        {
                           if(!array_key_exists(date('d-m-Y',strtotime($log->created_at)),$data)) $data[date('d-m-Y',strtotime($log->created_at))] = 0;
                            if ($log->type == 'buy') {
                                  $data[date('d-m-Y',strtotime($log->created_at))]-= $log->price;    
                            } else {
                                 $data[date('d-m-Y',strtotime($log->created_at))]+= $log->price;    
                            }
                           
                        }
                        $profit = 0;
                        $weeksProfit = array(
                            'Mon' => 0,
                            'Tue' => 0,
                            'Wed' => 0,
                            'Thu' => 0,
                            'Fri' => 0,
                            );
                        $weeksLoss = array(
                            'Mon' => 0,
                            'Tue' => 0,
                            'Wed' => 0,
                            'Thu' => 0,
                            'Fri' => 0,
                            );    
                        foreach($data as $key => $value)
                        {
                            if($value > 0) {
                                $profit++;
                                $weeksProfit[date('D', strtotime($key))]++;
                            } else{
                               $weeksLoss[date('D', strtotime($key))]++;
                            }
                            
                        }
                        $summary = array(
                            'total' => sizeof($data),
                            'profit' => $profit,
                            'weekProfit'=> $weeksProfit,
                            'weekLoss' => $weeksLoss,
                            'data' => $data,
                            'title'=> $title
                            );
                        $dataArray[$title] = $summary;
                    }
                    
                    ?>

                    <div class="card-body">
                        <h5 class="card-title">Log Summary</h5>
                        @foreach($dataArray as $row)
                            <div class="col-12">
                                <table class="table table-striped">
                                    <tr>
                                        <td width="200"><strong>{{ $row['title'] }}</strong></td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td>Total Trades: {{ $row['total'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Profit: {{ $row['profit'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Total Loss: {{ $row['total'] - $row['profit'] }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Profit Percentage: {{ number_format(($row['profit']/$row['total']) * 100, 2) }}%</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <th>Day</th>
                                                    <th>Profit</th>
                                                    <th>Loss</th>
                                                </thead>
                                                <tbody>
                                                @foreach($row['weekProfit'] as $day => $dayData)
                                                    <tr>
                                                        <td>{{ $day }}</td>
                                                        <td>{{ $dayData }}</td>
                                                        <td>{{ $row['weekLoss'][$day] }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div><!-- End Recent Sales -->
        </div>
    </section>

</x-app-layout>
