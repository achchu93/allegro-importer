<div id="parameters">
    <h2><?php _e("Parameters", "allegro-import"); ?></h2>
    <?php foreach($parameters->groups as $group): ?>
        <?php if($group->label): ?>
        <h4><?php echo $group->label; ?></h4>
        <?php endif; ?>
        <table class="parameter-table">
            <?php
            $sidelength = ceil(count($group->singleValueParams) / 2);
            for($i=0; $i < $sidelength; $i++): ?>
                <tr>
                <th><?php echo $group->singleValueParams[$i]->name; ?></th>
                <td><?php echo $group->singleValueParams[$i]->value->name; ?></td>
                <?php if(isset($group->singleValueParams[$i + $sidelength])): ?>
                <th><?php echo $group->singleValueParams[$i + $sidelength]->name; ?></th>
                <td><?php echo $group->singleValueParams[$i + $sidelength]->value->name; ?></td>
                <?php endif; ?>
                </tr>
            <?php endfor; ?>
        </table>
    <?php endforeach; ?>
</div>