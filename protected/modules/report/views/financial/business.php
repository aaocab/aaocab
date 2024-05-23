<div class="panel">  
    <div class="panel-body">
        <div class="mb10"><b>Today</b></div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>   
                    <th>Last week</th>
                    <th>Week to date</th>
                    <th>Today – 2</th>
                    <th>Today – 1</th>
                    <th>Today</th>
                    <th>Tomorrow</th>      
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th> Trips booked </th>    
                    <td><?= $trips_booked['result_booked']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_booked']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_booked']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_booked']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_booked']['today']; ?></td>
                    <td> N/A </td>
                </tr>
                <tr> <th> Trips on the way</th>
                    <td><?= $trips_booked['result_ontheway']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_ontheway']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_ontheway']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_ontheway']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_ontheway']['today']; ?></td>
                    <td><?= $trips_booked['result_ontheway']['tomorrow']; ?></td>
                </tr>
                <tr> <th>Trips started/ing</th> 
                    <td><?= $trips_booked['result_started']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_started']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_started']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_started']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_started']['today']; ?></td>
                    <td><?= $trips_booked['result_started']['tomorrow']; ?></td>
                </tr>
                <tr> <th> GMV</th>
                    <td><?= $trips_booked['result_gmv']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_gmv']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_gmv']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_gmv']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_gmv']['today']; ?></td>
                    <td>N/A </td>
                </tr>
                <tr><th>Advance payment</th> 
					<td><?= $trips_booked['result_advancepaid']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_advancepaid']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_advancepaid']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_advancepaid']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_advancepaid']['today']; ?></td>
                    <td> N/A </td>
                </tr>
                <tr>
                    <th> Cancellations </th>
                    <td><?= $trips_booked['result_cancelled']['last_week']; ?></td>  
                    <td><?= $trips_booked['result_cancelled']['week_to_date']; ?></td>
                    <td> <?= $trips_booked['result_cancelled']['2day_before']; ?></td>
                    <td> <?= $trips_booked['result_cancelled']['1day_before']; ?></td>
                    <td><?= $trips_booked['result_cancelled']['today']; ?></td>
                    <td> N/A </td>
                </tr>
                <tr> <th>NPS score</th> 
                    <td><?= $nps['last_week']; ?></td>  
                    <td><?= $nps['week_to_date']; ?></td>
                    <td> N/A </td>
                    <td> N/A </td>
                    <td> N/A </td>
                    <td> N/A </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

