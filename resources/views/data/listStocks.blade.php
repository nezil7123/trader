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
                        <h5 class="card-title">Stocks</h5>

                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Symbol</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <th scope="row"><a href="#">#{{$sl++}}</a></th>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->symbol}}</td>
                                    <td><a class="badge bg-{{ $item->status ? 'success' : 'danger' }} change-status" data-id={{$item->id}}>{{$status[$item->status]}}</a></td>
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
  $(document).on('click', '.change-status' ,function(){
   var id = $(this).attr('data-id');
   var item = this;
   $.get("{{ url('updateStatus') }}/"+id, function(data, status){
    if(data == '1'){
        $(item).removeClass('bg-danger');
        $(item).addClass('bg-success');
        $(item).html("Active");
    }else{
        $(item).addClass('bg-danger');
        $(item).removeClass('bg-success');
        $(item).html("InActive");
    }
  });
  });
});
</script>
