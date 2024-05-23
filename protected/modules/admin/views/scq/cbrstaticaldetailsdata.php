<div class="h5 mt0" style="color: red">* This report is based on followup create date</div>
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="text-align:center">Queue Name</th>
                            <th style="text-align:center">Team Name</th>							
                            <th style="text-align:center">Count Assigned (Assigned &Not closed)</th>
                            <th style="text-align:center">Count closed (Assigned & Closed) </th>
                            <th style="text-align:center">Count Assignable Now</th>
                            <th style="text-align:center">Total time to close</th>
                            <th style="text-align:center">Total time to Assign</th>
                            <th style="text-align:center">Avg time to Assign(Minute)</th>
                            <th style="text-align:center">Avg time taken to close(Minute)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($result as $row)
                        {
                            $i++;
                            ?>
                            <tr>
                                <td style="text-align:center"><?php echo $row['followUpType'] ?></td>
                                <td style="text-align:center"><?php
                                    if ($row['scq_follow_up_queue_type'] == 9 && $row['team_name'] != null)
                                    {
                                        echo $row['team_name'];
                                    }
                                    else if ($row['scq_follow_up_queue_type'] == 9 && $row['team_name'] == null)
                                    {
                                        $teamName = Teams::getByID($row['scq_to_be_followed_up_by_id']);
                                        echo $teamName;
                                    }
                                    else
                                    {
                                        $teamId   = Teams::getTeamIdFromCached($row['scq_follow_up_queue_type']);
                                        $teamName = Teams::getByID($teamId);
                                        echo $teamName;
                                    }
                                    ?></td>
                                <td style="text-align:center"><?php echo $row['assignedCount'] ?></td>
                                <td style="text-align:center"><?php echo $row['closedCount'] ?></td>
                                <td style="text-align:center"><?php echo $row['assignableNowCount'] ?></td>
                                <td style="text-align:center"><?php echo $row['ClosedMinute'] ?></td>
                                <td style="text-align:center"><?php echo $row['TotalAssignedMinute'] ?></td>
                                <td style="text-align:center"><?php echo $row['totalAssignedCount'] == 0 ? 0 : round(($row['TotalAssignedMinute'] / $row['totalAssignedCount']), 2) ?></td>
                                <td style="text-align:center"><?php echo $row['closedCount'] == 0 ? 0 : round(($row['ClosedMinute'] / $row['closedCount']), 2) ?></td>
                            </tr>
                            <?php
                        }

                        if ($i == 0)
                        {
                            ?>
                            <tr >
                                <td colspan="5">No Record found</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
