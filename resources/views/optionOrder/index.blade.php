<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="card-body">
                        <h5 class="card-title">Option Logs</h5>
                         <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <a class="btn btn-outline-primary" href="<?= url('/option-logs/TODAY') ?>">TODAY</a>
                            <a class="btn btn-outline-primary" href="<?= url('/option-logs/MONTH') ?>">MONTH</a>
                            <a class="btn btn-outline-primary" href="<?= url('/option-logs/ALL') ?>">ALL</a>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">LTP</th>
                                    <th scope="col">Entry</th>
                                    <th scope="col">Target</th>
                                    <th scope="col">Stoploss</th>
                                    <th scope="col">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $profit = 0;
                                ?>
                                @foreach ($logs as $log)
                                    <?php
                    
                                    $date = date('d-m-Y', strtotime($log->created_at));
                                    ?>
                                    <tr>
                                        <th scope="row">{{ $log->id }}</th>
                                        <td>{{ $log->symbol }} &nbsp;<span class="badge bg-info">{{ substr($log->symbol,18,7)}}</span></td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($log->created_at)) }}</td>
                                        <td>{{ $log->ltp }}</td>
                                        <td>{{ $log->price }}</td>
                                        <td>{{ number_format($log->price + $log->take_profit, 2) }}</td>
                                        <td>{{ number_format($log->price - $log->stop_loss, 2) }}</td>
                                        <td>
                                            <?php 
                                            if($log->profit == 1)
                                            {
                                                 echo "Profit";
                                                 $profit += ($log->price) * (18/100) * 25;
                                            }
                                            else if($log->profit == -1)
                                            {
                                                 echo "Loss";
                                                 $profit -= ($log->price) * (18/100) * 25;
                                            }
                                            else
                                            {
                                                echo "Pending";
                                            }
                                                
                                            
                                            ?>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th scope="row"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $profit }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div><!-- End Recent Sales -->
        </div>
    </section>

</x-app-layout>
