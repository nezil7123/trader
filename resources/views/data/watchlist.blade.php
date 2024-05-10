<x-app-layout>
    <section class="section profile">
        <div class="row">
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <?php 
                        usort($data, function($a, $b) {return $a->v->chp < $b->v->chp;});
                    ?>
                    <div class="card-body">
                        <h5 class="card-title">Watchlist</h5>

                        <table class="table table-borderless table-striped datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Change</th>
                                    <th scope="col">LTP</th>
                                    <th scope="col">Open</th>
                                    <th scope="col">High</th>
                                    <th scope="col">Low</th>
                                    <th scope="col">Spread</th>
                                    <th scope="col">Volume</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=1;
                                ?>
                                @foreach($data as $row)
                                    <tr>
                                        <td>
                                            <?=$i++ ?>
                                        </td>
                                        <td>
                                            {{ $row->v->short_name }}
                                        </td>
                                        <td>
                                            {{ $row->v->chp }}%
                                        </td>
                                        <td>
                                            {{ $row->v->lp }}
                                        </td>
                                         <td>
                                            {{ $row->v->open_price }}
                                        </td>
                                        <td>
                                            {{ $row->v->high_price }}
                                        </td>
                                         <td>
                                            {{ $row->v->low_price }}
                                        </td>
                                         <td>
                                            {{ $row->v->spread }}
                                        </td>
                                        <td>
                                            {{ $row->v->volume }}
                                        </td>
                                        <td>
                                            <a href="{{ url('stock-buy/'.urlencode($row->v->symbol).'/'.$row->v->ask) }}" class="btn btn-success">Buy</a>
                                            <a href="{{ url('stock-sell/'.urlencode($row->v->symbol).'/'.$row->v->bid) }}" class="btn btn-danger">Sell</a>
                                            <a target="_blank" href="https://in.tradingview.com/chart/?symbol=NSE:{{urlencode(str_replace('-EQ','',$row->v->short_name))}}" class="btn btn-info">Chart</a>
                                        </td>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>


    $(document).ready(function(){
         $.get("https://www.nseindia.com/api/live-analysis-variations?index=gainers", function(data, status){
            console.log(data);
          });
    });
</script>
