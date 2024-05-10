<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">

                    <div class="card-body">
                        <h5 class="card-title">Automations</h5>
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <a class="btn btn-outline-primary" href="<?= url('/logs/ALL') ?>">ALL</a>
                            @foreach ($types as $type)
                                <a class="btn btn-outline-primary"
                                    href="<?= url('/logs/' . urlencode($type->title)) ?>">{{ $type->title }}</a>
                            @endforeach
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Message</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <?php
                            $profit = 0;
                            function random_color_part()
                            {
                                return str_pad(dechex(mt_rand(180, 255)), 2, '0', STR_PAD_LEFT);
                            }
                            
                            function random_color()
                            {
                                return random_color_part() . random_color_part() . random_color_part();
                            }
                            $color = random_color();
                            $date = '';
                            ?>
                            <tbody>
                                @foreach ($logs as $log)
                                    <?php
                                    if ($log->type == 'buy') {
                                        $profit -= $log->price * ($log->qty ? $log->qty : 1000);
                                    } else {
                                        $profit += $log->price * ($log->qty ? $log->qty : 1000);
                                    }
                                    
                                    if (date('d-m-Y', strtotime($log->created_at)) != $date) {
                                        $color = random_color();
                                    }
                                    $date = date('d-m-Y', strtotime($log->created_at));
                                    ?>
                                    <tr style="background: #<?= $color ?>">
                                        <th scope="row">{{ $log->id }}</th>
                                        <td>{{ $log->title }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($log->created_at)) }}</td>
                                        <td>{{ $log->message }}</td>
                                        <td>{{ $log->type }}</td>
                                        <td>{{ number_format($log->price, 2) }}</td>
                                        <td>{{ number_format($log->price * 1000, 2) }}</td>
                                        <td>{{ $profit }}</td>
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
