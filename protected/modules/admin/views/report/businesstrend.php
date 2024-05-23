<div class="panel">  
    <div class="panel-body">
        <div class="mb10"><b>Trend Snapshot</b></div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>   
                    <th>Lifetime</th>
                    <th>YTD</th>
                    <th>Month – 2</th>
                    <th>Month – 1</th>
                    <th>Month to Date</th>      
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>GMV</th>    
                    <td><?= $gmv['lifetime']; ?></td>  
                    <td><?= $gmv['ytd']; ?></td>
                    <td><?= $gmv['month-2']; ?></td>
                    <td><?= $gmv['month-1']; ?></td>
                    <td><?= $gmv['mtd']; ?></td>
                </tr>
                <tr>
                    <th>Advance Received</th>    
                    <td><?= $advance_payment['lifetime']; ?></td>  
                    <td><?= $advance_payment['ytd']; ?></td>
                    <td><?= $advance_payment['month-2']; ?></td>
                    <td><?= $advance_payment['month-1']; ?></td>
                    <td><?= $advance_payment['mtd']; ?></td>
                </tr>
                <tr>
                    <th>Bookings Received</th>    
                    <td><?= $trips_booked['lifetime']; ?></td>  
                    <td><?= $trips_booked['ytd']; ?></td>
                    <td><?= $trips_booked['month-2']; ?></td>
                    <td><?= $trips_booked['month-1']; ?></td>
                    <td><?= $trips_booked['mtd']; ?></td>
                </tr>
                <tr>
                    <th>Bookings Cancelled</th>    
                    <td><?= $cancellations['lifetime']; ?></td>  
                    <td><?= $cancellations['ytd']; ?></td>
                    <td><?= $cancellations['month-2']; ?></td>
                    <td><?= $cancellations['month-1']; ?></td>
                    <td><?= $cancellations['mtd']; ?></td>
                </tr>
                <tr>
                    <th>Bookings Complete</th>    
                    <td><?= $trips_complete['lifetime']; ?></td>  
                    <td><?= $trips_complete['ytd']; ?></td>
                    <td><?= $trips_complete['month-2']; ?></td>
                    <td><?= $trips_complete['month-1']; ?></td>
                    <td><?= $trips_complete['mtd']; ?></td>
                </tr>
                <tr>
                    <th>Reviews</th>    
                    <td><?= $reviews['lifetime']; ?></td>  
                    <td><?= $reviews['ytd']; ?></td>
                    <td><?= $reviews['month-2']; ?></td>
                    <td><?= $reviews['month-1']; ?></td>
                    <td><?= $reviews['mtd']; ?></td>
                </tr>
                <tr>
                    <th>NPS Score</th>    
                    <td><?= $nps['lifetime']; ?></td>  
                    <td><?= $nps['ytd']; ?></td>
                    <td><?= $nps['month-2']; ?></td>
                    <td><?= $nps['month-1']; ?></td>
                    <td><?= $nps['mtd']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



