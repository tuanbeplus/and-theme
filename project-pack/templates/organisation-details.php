<?php 
/**
 * Organisation details box
 */
?>

<h2>Organisation details</h2>
<table class="form-table" id="org-details__box">
    <tbody>
        <?php foreach ($template_org_data['org_details'] as $key => $value): ?>
            <tr>
                <th><?php echo $key; ?></th>
                <td><?php echo $value; ?></td>
            </tr>
        <?php endforeach; ?>
            <tr>
                <th>Member Journey</th>
                <td>
                    <ul>
                    <?php foreach ($template_org_data['member_journey'] as $key => $value): ?>
                        <?php if (isset($key)): ?>
                            <li class="<?php if ($value == true) echo 'completed'; ?>">
                                <?php echo str_replace('_', ' ', str_replace('__c', '', $key)); ?>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Contact details</th>
                <td>
                    <ul>
                    <?php foreach ($template_org_data['contact'] as $key => $value): ?>
                        <li>
                            <strong><?php echo $key. ': '; ?></strong>
                            <?php echo $value; ?>
                        </li>
                    <?php endforeach; ?> 
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Opportunities</th>
                <td>
                    <ul>
                    <?php foreach ($template_org_data['opportunity'] as $key => $opportunity): ?>
                        <li>
                            <?php echo $opportunity['Name']; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
    </tbody>
</table>

