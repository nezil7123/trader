<x-app-layout>
    <section class="section profile">
        <div class="row">

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                               

                                <h5 class="card-title">Funds</h5>
                                <div class="row">
                                    <div class="col-lg-6 col-md-4 label ">Type</div>
                                    <div class="col-lg-3 col-md-8">Equity Amount</div>
                                    <div class="col-lg-3 col-md-8">Commodity Amount</div>
                                </div>
                                @foreach ($data->fund_limit as $item)
                                <div class="row">
                                    <div class="col-lg-6 col-md-4 label ">{{ $item->title }}</div>
                                    <div class="col-lg-3 col-md-8">{{ $item->equityAmount }}</div>
                                    <div class="col-lg-3 col-md-8">{{ $item->commodityAmount }}</div>
                                </div>
                                @endforeach
                               

                               
                            </div>

                            

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>
