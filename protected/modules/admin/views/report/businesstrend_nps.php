<div class="panel">  
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Period</th>   
                    <th>Requested</th>
                    <th>Responses</th>
                    <th>Detractors</th>
                    <th>Passives</th>
                    <th>Promoters</th>
                    <th>NPS Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Current Week</th>    
                    <td><?= $nps['cw_requested']; ?></td>  
                    <td><?= $nps['cw_responded']; ?></td>
                    <td><?= $nps['cw_destractors']; ?></td>
                    <td><?= $nps['cw_passives']; ?></td>
                    <td><?= $nps['cw_promotors']; ?></td>
                    <td><?= $nps['cw_nps']; ?></td>
                </tr>
                <tr>
                    <th>Last Week</th>    
                    <td><?= $nps['lw_requested']; ?></td>  
                    <td><?= $nps['lw_responded']; ?></td>
                    <td><?= $nps['lw_destractors']; ?></td>
                    <td><?= $nps['lw_passives']; ?></td>
                    <td><?= $nps['lw_promotors']; ?></td>
                    <td><?= $nps['lw_nps']; ?></td>
                </tr>
                <tr>
                    <th>Current Month</th>    
                    <td><?= $nps['cm_requested']; ?></td>  
                    <td><?= $nps['cm_responded']; ?></td>
                    <td><?= $nps['cm_destractors']; ?></td>
                    <td><?= $nps['cm_passives']; ?></td>
                    <td><?= $nps['cm_promotors']; ?></td>
                    <td><?= $nps['cm_nps']; ?></td>
                </tr>
                <tr>
                    <th>Last Month</th>    
                    <td><?= $nps['lm_requested']; ?></td>  
                    <td><?= $nps['lm_responded']; ?></td>
                    <td><?= $nps['lm_destractors']; ?></td>
                    <td><?= $nps['lm_passives']; ?></td>
                    <td><?= $nps['lm_promotors']; ?></td>
                    <td><?= $nps['lm_nps']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>





